<?php
namespace App\Lib;

class Db_init_sqlite
{
    public $db;

    public function __construct($params = [
                                'type' => 'sqlite',
                                'database' => APPROOT.DS.'db'.DS.DB_NAME.'.sqlite',
                                'charset' => 'utf8mb4',
                                'collation' => 'utf8mb4_general_ci',
                                'error' => \PDO::ERRMODE_EXCEPTION,
                                'port' => 3306
                                ])
    {
        //init $db
        $this->db = new Medoo($params);
    }

    function create_tables()
    {
        //select from or created pages table if not exists
        $this->db->create("pages", [
            "page_id" => [
                "INT",
                "AUTO_INCREMENT"
            ],
            "page_alias" => [
                "VARCHAR(100)"
            ],
            "page_title" => [
                "VARCHAR(255)"
            ],
            "page_meta_description" => [
                "VARCHAR(100)"
            ],
            "page_meta_keywords" => [
                "VARCHAR(100)"
            ],
            "page_h1" => [
                "VARCHAR(100)"
            ],
            "page_small_description" => [
                "TEXT"
            ],
            "page_content" => [
                "TEXT"
            ],
            "page_publish" => [
                "CHAR",
                "DEFAULT Y"
            ],
            "PRIMARY KEY (<page_id>)"
        ]);

        $this->db->create("users", [
            "id" => [
                "INT",
                "AUTO_INCREMENT"
            ],
            "username" => [
                "VARCHAR(25)", 
                "NOT NULL",
                "UNIQUE"
            ],
            "password" => [
                "VARCHAR(255)", 
                "NOT NULL"
            ],
            "email" => [
                "VARCHAR(100)", 
                "NOT NULL"
            ],
            "email_status" => [
                "VARCHAR(10)"
            ],
            "PRIMARY KEY (<id>)",
	        "AUTO_INCREMENT" => 1
        ]);
    }
}