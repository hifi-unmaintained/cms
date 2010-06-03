--
-- CMS SQLite structure
--

PRAGMA foreign_keys = 1;

CREATE TABLE page (
    id              INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    parent_id       INTEGER DEFAULT NULL,
    uri             TEXT NULL,
    redirect        TEXT NULL, -- if not null, redirect here (http(s):// -> outside, others are uri names)
    template        TEXT NULL, -- template file name
    title           TEXT NULL,
    description     TEXT NULL,
    keywords        TEXT NULL,
    created         DATETIME DEFAULT (datetime()),
    updated         DATETIME DEFAULT (datetime()),

    FOREIGN KEY (parent_id) REFERENCES page(id) ON DELETE RESTRICT ON UPDATE RESTRICT
);

CREATE TABLE page_data (
    page_id         INTEGER NOT NULL,
    field           TEXT NOT NULL,
    value           TEXT NULL,

    PRIMARY KEY (page_id, field),
    FOREIGN KEY (page_id) REFERENCES page(id) ON DELETE CASCADE ON UPDATE RESTRICT
);
