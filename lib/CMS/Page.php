<?php

class CMS_Page
{
    private $__modified = array();
    private $data = false;
    private $fields = false;
    private $children = false;

    static private $cache = array();

    function __construct($arg)
    {
        if(is_array($arg) || is_object($art)) {
            $this->data = (object)$arg;
            return;
        }

        if($arg === NULL) {
            $stmt = CMS::$db->query("SELECT * FROM page WHERE uri IS NULL");
            $stmt->execute();
        } else if(is_integer($arg)) {
            $stmt = CMS::$db->query("SELECT * FROM page WHERE id = ?");
            $stmt->execute(array($arg));
        } else {
            $stmt = CMS::$db->query("SELECT * FROM page WHERE uri = ?");
            $stmt->execute(array($arg));
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if($row === false)
            throw new Exception('Page not found');

        $this->data = (object)false;
        foreach($row as $key => $value) {
            $this->data->$key = $value;
        }
    }

    static function get($arg = NULL)
    {
        if(!is_string($arg) && !is_integer($arg) && ($arg !== NULL))
            throw new Exception('Static getting support only id and uri');
        if(!array_key_exists($arg, CMS_Page::$cache))
            CMS_Page::$cache[$arg] = new CMS_Page($arg);
        return CMS_Page::$cache[$arg];
    }

    function updateFields()
    {
        $stmt = CMS::$db->prepare("SELECT * FROM page_data WHERE page_id = ?");
        $stmt->execute(array($this->data->id));
        $this->fields = (object)false;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(!property_exists($this->data, $row['field']))
                $this->fields->$row['field'] = $row['value'];
        }
    }

    function __get($key)
    {
        if($key == 'children') {
            $stmt = CMS::$db->query("SELECT * FROM page WHERE parent_id = ?");
            $stmt->execute(array($this->data->id));
            $this->children = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data = array();
                foreach($row as $key => $value) {
                    $data[$key] = $value;
                }
                $this->children[] = new CMS_Page($data);
            }

            return $this->children;
        }

        if($key == 'parent') {
            if($this->data->parent_id == NULL)
                return NULL;

            $stmt = CMS::$db->query("SELECT * FROM page WHERE id = ?");
            $stmt->execute(array($this->data->parent_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $data = array();
            foreach($row as $key => $value) {
                $data[$key] = $value;
            }

            $this->parent = new CMS_Page($data);
            return $this->parent;
        }

        if($key == 'uri') {
            if(CMS_MODE == 'edit')
                return CMS::baseUri().'page.php?id='.$this->id;

            if(CMS::$config->uri == 'id') {
                return CMS::baseUri().'index.php?id='.$this->id;
            }
        }

        if(property_exists($this->data, $key))
            return $this->data->$key;

        if($this->fields === false)
            $this->updateFields();

        return $this->fields->$key;
    }

    function __set($key, $value)
    {
        if(property_exists($this->data, $key))
            throw new Exception('Trying to modify read-only field');

        if($this->fields === false)
            $this->updateFields();

        if(!property_exists($this->fields, $key))
            throw new Exception('Trying to modify non-existent field');

        $this->fields->$key = $value;
        $this->__modified[$key] = true;
    }

    function save()
    {
        $stmt = CMS::$db->prepare("UPDATE page_data SET value = ? WHERE page_id = ? AND field = ?");
        foreach($this->__modified as $key => $yes) {
            $stmt->execute(array($this->fields->$key, $this->data->id, $key));
            $stmt->closeCursor();
        }
    }

    function getTemplate()
    {
        if(!property_exists($this->data, template) || $this->data->template == NULL)
            throw new Exception('Missing template name');

        $template = INCLUDE_ROOT."/site/".$this->data->template.".php";

        if(!file_exists($template))
            throw new Exception('Missing template file '.$this->data->template);

        return $template;
    }

    function field($name, $type = 'html', $options = array())
    {
        if($this->fields === false)
            $this->updateFields();

        if(!property_exists($this->fields, $name)) {
            $stmt = CMS::$db->prepare("INSERT INTO page_data(page_id, field) VALUES(?, ?)");
            $stmt->execute(array($this->data->id, $name));
            $stmt->closeCursor();

            $this->fields->$name = NULL;
        }

        $type = ucfirst(strtolower(preg_replace('/[^A-Za-z]/', '', $type)));
        $class = "CMS_Field_{$type}";

        try {
            if(CMS_MODE == 'view') {
                $class::view($this->id, $name, $this->fields->$name);
            } else if(CMS_MODE == 'edit') {
                $class::edit($this->id, $name, $this->fields->$name);
            }
        } catch(Exception $e) {
            throw new Exception('Unkown field type, class not found for '.$type);
        }

    }
}
