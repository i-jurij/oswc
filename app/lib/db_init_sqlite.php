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

        $this->db->create("adm_pages", [
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
            "page_robots" => [
                "VARCHAR(100)",
                "DEFAULT NOINDEX, NOFOLLOW"
            ],
            "page_content" => [
                "TEXT"
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
/*
        $this->db->insert("adm_pages", [
            ["page_alias" => "adm",
            "page_title" => "Admin",
            "page_meta_description" => "Управление сайтом"
            ],
            ["page_alias" => "master_app",
            "page_title" => "Записи к мастерам",
            "page_meta_description" => "Таблица записей к мастеру"
            ],
            ["page_alias" => "today_app",
            "page_title" => "Записи на сегодня",
                "page_meta_description" => "Таблица записей к мастерам на сегодня"
            ],
            ["page_alias" => "recall",
            "page_title" => "Перезвоните клиенту",
            "page_meta_description" => "Список клиентов, которым нужно перезвонить"
            ],
            ["page_alias" => "no_recall",
            "page_title" => "Обратный звонок выполнен",
            "page_meta_description" => "Список клиентов, которым уже перезвонили"
            ],
            ["page_alias" => "contacts",
            "page_title" => "Редактор контактов",
            "page_meta_description" => "Страница изменения контактов"
            ],
            ["page_alias" => "grafik",
            "page_title" => "Графики работы мастеров",
            "page_meta_description" => "Редактирование графиков работы мастеров"
            ],
            ["page_alias" => "price",
            "page_title" => "Редактирование прайсов услуг",
            "page_meta_description" => "Редактирование прайсов услуг"
            ],
            ["page_alias" => "masters",
            "page_title" => "Мастера",
            "page_meta_description" => "Редактирование информации о мастерах"
            ],
            ["page_alias" => "pages_services",
            "page_title" => "Редактор страниц и услуг",
            "page_meta_description" => "Редактирование страниц и услуг"
            ],
            ["page_alias" => "map",
            "page_title" => "Изменение карты проезда",
            "page_meta_description" => "Изменение карты проезда"
            ],
            ["page_alias" => "about",
            "page_title" => "Редактор страницы \"Обо мне\"",
            "page_meta_description" => "Редактор страницы \"Обо мне\""
            ],
            ["page_alias" => "gallery",
            "page_title" => "Редактор галереи",
            "page_meta_description" => "Редактор галереи"
            ],
        ]);
*/
    }
}