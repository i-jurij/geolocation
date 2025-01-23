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

namespace Ijurij\Geolocation\Lib;

class Session
{
    public static function start()
    {
        // Если сессия еще не запущена
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function has($name)
    {
        self::start();

        return isset($_SESSION[$name]);
    }

    public static function set($name, $value)
    {
        self::start();
        if ($name && $value) {
            $_SESSION[$name] = $value;
        }
        session_write_close();
    }

    public static function setArray(array $vars)
    {
        self::start();
        foreach ($vars as $name => $value) {
            self::set($name, $value);
            session_write_close();
        }
    }

    public static function get($name)
    {
        self::start();

        return (!empty($_SESSION[$name])) ? $_SESSION[$name] : false;
    }

    /**
     * Если вызвать $this->flash со строковым параметром, то она сохранит эту строку в сессии,
     * а если вызвать без параметров, то возвратит сохранённое сообщение.
     *
     * @param $message - string or null
     */
    public static function flash(?string $message = null)
    {
        self::start();
        if ($message) {
            $_SESSION['flash'] = $message;
        } else {
            if (!empty($_SESSION['flash'])) {
                return $_SESSION['flash'];
            }
        }
    }

    /**
     * @param $name - Уничтожаем $name
     */
    public static function destroy($name)
    {
        self::start();
        unset($_SESSION[$name]);
        session_write_close();
    }
}
