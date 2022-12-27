<?php
/*
* example of using a class:
* $session = new Session();
* $session->start();
* if( !$session->has('counter') ) { $session->set('counter', 0); }
* $counter = $session->get('counter');
* $session->set('counter', ++$counter);
* $response->getBody()->write("Вы посетили сайт $counter раз/a");
* // массовая установка значений
* $session->setArray(['one' => 1, 'two' => 2, 'three' => 3]);
*/
namespace App\Lib;

class Session
{
    private $cookieTime;
    // задаем время жизни сессионных кук
    public function __construct(string $cookieTime = '+30 days') {
        $this->cookieTime = strtotime($cookieTime);
        session_cache_limiter(false);
    }
    // стартуем сессию
    public function start()
    {
        session_start();
    }
    /**
     * Проверяем сессию на наличие в ней переменной c заданным именем
     */
    public function has($name)
    {
        return isset($_SESSION[$name]);
    }
    /**
     * Устанавливаем сессию с именем $name и значением $value
     * 
     * @param $name
     * @param $value
     */
    public function set($name, $value) {
        $_SESSION[$name] = $value;
    }
    /**
     * Когда мы хотим сохранить в сессии сразу много значений - используем массив
     *
     * @param $vars
     */
    public function setArray(array $vars)
    {
        foreach($vars as $name => $value) {
            $this->set($name, $value);
        }
    }
    /**
     * Получаем значение сессий
     *
     * @param $name
     * @return mixed
     */
    public function get($name) {
        return $_SESSION[$name];
    }
    /**
     * 
     * @param $name - Уничтожаем сессию с именем $name
     */
    public function destroy($name) {
        unset($_SESSION[$name]);
    }
    /**
     * Полностью очищаем все данные пользователя
     */
    public function destroyAll() {
        $_SESSION = array(); //Очищаем сессию
        session_destroy(); //Уничтожаем
    }
    /**
     * Устанавливаем куки  
     *
     * @param $name
     * @param $value
     */
    public function setCookie($name, $value) {
        setcookie($name, $value, $this->cookieTime);
    }
    /**
     * Получаем куки
     *
     * @param $name
     * @return mixed
     */
    public function getCookie($name) {
        return $_COOKIE[$name];
    }
    /**
     * @param $name Удалаяем
     */
    public function removeCookie($name) {
        setcookie($name, null);
    }
}
