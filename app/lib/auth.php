<?php
/* class dependencies: Session class in app/lib/session.php
/* example of using a class
*
* if (isset($_POST["login"]) && isset($_POST["password"])) 
* { //Если логин и пароль были отправлены
*   $this->inp_login = $_POST["login"];
*   $this->inp_password = $_POST["password"];
*
*    $dbi = new Db_init_sqlite; $dbt = $dbi->db;
*    $res = $dbt->select("users", "password", [
*        "username" => $this->inp_login
*    ]);
*    $this->password = (isset($res[0])) ? $res[0] : '';
*
*    if (!$this->auth()) { //Если логин и пароль введен не правильно
*        echo '<h2 style="color:red;">Неправильный логин или пароль.</h2>';
*        include 'app/view/login.php';
*    }
*             
*    if (isset($_GET["is_exit"])) 
*    { //Если нажата кнопка выхода
*        if ($_GET["is_exit"] == 1) {
*            $this->out(); //Выходим
*            header("Location: ?is_exit=0"); //Редирект после выхода
*        }
*    }
*
*   if ($this->isAuth()) 
*    { // Если пользователь авторизован, приветствуем:  
*        echo "Здравствуйте, " . $this->getLogin() ;
*        echo "<br/><br/><a href='?is_exit=1'>Выйти</a>"; //Показываем кнопку выхода
*    } 
* }
* else 
* { //Если не авторизован, показываем форму ввода логина и пароля
*    include 'app/view/login.php';
* }
*
*/

namespace App\Lib;

class Auth
{
    private string $inp_login; //from login form
    private string $inp_password; //from login form
    private string $login; //researched user, eg admin
    private string $password; //password from db

    function __construct()
    {
        $this->session = new \App\Lib\Session();
		$this->session->start();
    }

    public function isAuth() 
    {
        //if (isset($_SESSION["is_auth"])) 
        if ($this->session->has("is_auth"))
        { //Если сессия существует
            return $this->session->get("is_auth"); //Возвращаем значение переменной сессии is_auth (хранит true если авторизован, false если не авторизован)
        }
        else return false; //Пользователь не авторизован, т.к. переменная is_auth не создана
    }
        
    /**
    * Авторизация пользователя
    * @param string $login
    * @param string $password
    */
    public function auth($inp_password, $inp_login, $password, $login) 
    {
        if ( $inp_login === $login && password_verify($inp_password, $password) )
        { //Если логин и пароль введены правильно
            $this->session->set("is_auth", true); //Делаем пользователя авторизованным
            $this->session->set("login", $inp_login); //Записываем в сессию логин пользователя
            return true;
        }
        else 
        { //Логин и пароль не подошел
            $this->session->set("is_auth", false);;
            return false; 
        }
    }
        
    /**
    * Метод возвращает логин авторизованного пользователя 
    */
    public function getLogin() 
    {
        if ($this->isAuth()) 
        { //Если пользователь авторизован
            return $this->session->get("login"); //Возвращаем логин, который записан в сессию
        }
    }
    
    public function out() 
    {
        $this->session->destroyAll(); //Уничтожаем
    }
}
