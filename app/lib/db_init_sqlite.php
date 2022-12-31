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
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
            ],
            "page_alias" => [
                "VARCHAR(100)",
                "UNIQUE"
            ],
            "page_templates" => [
                "VARCHAR(100)"
            ],
            "page_title" => [
                "VARCHAR(255)"
            ],
            "page_meta_description" => [
                "VARCHAR(100)"
            ],
            "page_meta_keywords" => [
                "VARCHAR(500)"
            ],
            "page_robots" => [
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
            ]
        ]);

        $this->db->create("users", [
            "id" => [
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
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
                "VARCHAR(100)"
            ],
            "email_status" => [
                "VARCHAR(10)"
            ]
        ]);

        $this->db->create("contacts", [
            "id" => [
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
            ],
            "contacts_type" => [
                "VARCHAR(25)"
            ],
            "contacts_data" => [
                "VARCHAR(100)",
                "UNIQUE"
            ]
        ]);
 /*       
        $this->db->insert("contacts", [
            [
                "contacts_type" => "telegram",
                "contacts_data" => "tg"
            ],
            [
                "contacts_type" => "vk",
                "contacts_data" => "vk"
            ],
            [
                "contacts_type" => "instagram",
                "contacts_data" => "inst"
            ],
            [
                "contacts_type" => "tlf",
                "contacts_data" => "+7 523 425 25 43"
            ],
            [
                "contacts_type" => "tlf",
                "contacts_data" => "0"
            ],
            [
                "contacts_type" => "adres",
                "contacts_data" => "Севастополь, ул. Такая-то, д.№00, офис №11"
            ]
        ]);

        $this->db->insert("users", [
            [
                "username" => "admin",
                "password" => password_hash("passw", PASSWORD_DEFAULT),
                "email" => "foo@bar.com",
                "email_status" => "0"
            ],
            [
                "username" => "moder",
                "password" => password_hash("moder", PASSWORD_DEFAULT),
                "email" => "foo@moder.com",
                "email_status" => "0"
            ],
            [
                "username" => "user",
                "password" => password_hash("user", PASSWORD_DEFAULT),
                "email" => "foo@user.com",
                "email_status" => "0"
            ]
        ]);

        $this->db->insert("pages", [
            ["page_alias" => "home",
            "page_templates" => "first",
            "page_title" => "Красота спасет мир",
            "page_meta_description" => "Маникюр, визаж, парикмахерские услуги.",
            "page_meta_keywords" => "маникюр, ногти, ногтевая пластина, кутикула, лак, гель, наращивание, покрытие, визаж, грим, тени, помада, парикмахер, салон, парикмахерская, прическа, стрижка",
            "page_h1" => "Маникюр"
            ]
        ]);
*/
    }
}