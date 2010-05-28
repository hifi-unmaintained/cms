<?php

class Hippo_Page
{
    private $__modified = array();
    private $data = false;
    private $fields = false;

    function __construct($uri)
    {
        if($uri === NULL) {
            $stmt = Hippo::$db->query("SELECT * FROM page WHERE uri IS NULL");
            $stmt->execute();
        } else {
            $stmt = Hippo::$db->query("SELECT * FROM page WHERE uri = ?");
            $stmt->execute(array($uri));
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if($row === false)
            throw new Exception('Page not found');

        $this->data = (object)false;
        foreach($row as $key => $value) {
            $this->data->$key = $value;
        }

        /* get all fields */
        $stmt = Hippo::$db->prepare("SELECT * FROM page_data WHERE page_id = ?");
        $stmt->execute(array($this->data->id));
        $this->fields = (object)false;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(!property_exists($this->data, $row['field']))
                $this->fields->$row['field'] = $row['value'];
        }
    }

    function __get($key)
    {
        if(property_exists($this->data, $key))
            return $this->data->$key;

        return $this->fields->$key;
    }

    function __set($key, $value)
    {
        if(property_exists($this->data, $key))
            throw new Exception('Trying to modify read-only field');

        if(!property_exists($this->fields, $key))
            throw new Exception('Trying to modify non-existent field');

        $this->fields->$key = $value;
        $this->__modified[$key] = true;
    }

    function save()
    {
        $stmt = Hippo::$db->prepare("UPDATE page_data SET value = ? WHERE page_id = ? AND field = ?");
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
        if(!property_exists($this->fields, $name)) {
            $stmt = Hippo::$db->prepare("INSERT INTO page_data(page_id, field) VALUES(?, ?)");
            $stmt->execute(array($this->data->id, $name));
            $stmt->closeCursor();

            $this->fields->$name = NULL;
        }

        $type = ucfirst(strtolower(preg_replace('/[^A-Za-z]/', '', $type)));
        $class = "Hippo_Field_{$type}";

        try {
            if(HIPPO_MODE == 'view') {
                $class::view($name, $this->fields->$name);
            } else if(HIPPO_MODE == 'edit') {
                $class::edit($name, $this->fields->$name);
            }
        } catch(Exception $e) {
            throw new Exception('Unkown field type, class not found for '.$type);
        }

    }
}
