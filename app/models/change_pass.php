<?php
namespace App\Models;

class Change_pass extends Adm
{
    private array $users;
	protected function db_query() 
	{
		$this->data['page_db_data'] = array(array("page_alias" => "change_pass", 
                                                "page_title" => "Пользователи",
                                                "page_meta_description" => "Изменить явки и пароли",
                                                "page_robots" => "NOINDEX, NOFOLLOW",
                                                "page_h1" => "Пользователи",
                                                "page_access" => "admin"));
        $this->users = $this->db->db->select("users", ["username", "status"]);  
	}

    public function add($path)
	{	
        $this->data['name'] = 'Добавить';
        if (strlen(filter_has_var( INPUT_POST, "reg_name" )) < 26 
            && strlen(filter_has_var( INPUT_POST, "reg_password")) < 121
            && filter_has_var( INPUT_POST, "reg_status" ) ) 
        {
            
            //$names = $this->db->db->select("users", "username");   
            $names = $this->users;
            $name = htmlentities($_POST["reg_name"]);
            if (preg_match("/^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$/", $name)) {
                if (in_array($name, $names)) {
                    $this->data['res'] = 'Username exists.<br /> Такое имя уже существует.';
                } else {
                    $pass = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);
                    $status = htmlentities($_POST['reg_status']);
                    $sql_res = $this->db->db->insert("users", [
                                    [
                                        "username" => $name,
                                        "password" => $pass,
                                        "email" => "foo@bar.com",
                                        "email_status" => "0",
                                        "status" => $status
                                    ]
                                ]);
                    if ($sql_res->rowCount() > 0) {
                        $this->data['res'] = 'User added.<br />
                                                Пользователь добавлен.<br /> 
                                                Username is: "'.$name.'"<br />
                                                Status is: "'.$status.'"';
                    } else {
                        $this->data['res'] = 'ERROR!<br /> Data NOT INSERT to database.';
                    }
                }
            } else {
                $this->data['res'] = 'ERROR!<br /> Username is not valid.';
            }
        } else {
            $file = APPROOT.DS.'view'.DS.'registration.php';
            if (is_readable($file)) {
                $this->data['res'] = 'Save the username and password.<br />'.file_get_contents($file);
            }else {
                $this->data['res'] = 'Check for the file '.$file.' and access rights to it.';
            }             
        }
        return $this->data;
	} 

    public function delete($path)
	{	
        $this->data['name'] = 'Удалить';
        if ( filter_has_var( INPUT_POST, "delete" ) ) 
        {
            $this->data['res'] = '';
            if (is_array($_POST['delete'])) {
            /*
                foreach($_POST['delete'] as $name) {         // walk wthrough array items

                $database->delete("users", [      // run medoo query 
                "AND" => [
                    "username" => string($name)          // if whitespaces, make an string
                    ]
                ]);
                }
            */
                
                foreach ($this->users as $user) {
                    if (in_array('admin', $user)) {
                        $admins[] = $user['username'];
                    }
                }

                foreach ($_POST['delete'] as $name) {
                   $postdel[] = htmlentities($name);
                }

                if ( !empty(array_diff($admins, $postdel)) ) {
                    $sql_res = $this->db->db->delete("users", array(
                        "username" => $postdel)
                    );
    
                    $del_user = '"'.implode('", "', $_POST['delete']).'"';
                    if ($sql_res->rowCount() > 0) {
                        $this->data['res'] = 'Users '.$del_user.'<br /> has been deleted from database.';
                    } else {
                        $this->data['res'] = 'ERROR!<br /> The data has NOT been DELETED from database.';
                    }
                }
                else {
                    $this->data['res'] = "ERROR! You can't delete all users with administrator rights.";
                }

            } else {
                $this->data['res'] = 'ERROR! Data from $_POST is not array.';
            }
        } else {
            //$this->data['users_del'] = $this->db->db->select("users", "username");
            $this->data['users_del'] = $this->users;
        }        
        return $this->data;
	} 

    public function change($path)
	{	
        $this->data['name'] = 'Изменить';
        if ( filter_has_var( INPUT_POST, "change" ) ) 
        {

            $res = implode(', ', $_POST['change']);
            $this->data['res'] = 'Form output'.$res;
        } elseif ( filter_has_var( INPUT_POST, "change_name" ) 
                    || filter_has_var( INPUT_POST, "change_password")
                    || filter_has_var( INPUT_POST, "change_status" ) ) 
        {
            //sql update
            $this->data['res'] = 'DATA is UPDATE in TABLE';
        } else {
            //$this->data['users_change'] = $this->db->db->select("users", "username");
            $this->data['users_change'] = $this->users;
        }        
        return $this->data;
	}
}