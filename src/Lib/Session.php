<?php
/*
* example of using a class:
* Session::start();
* if( !Session::has('counter') ) { Session::set('counter', 0); }
* $counter = Session::get('counter');
* Session::set('counter', ++$counter);
* $response->getBody()->write("Вы посетили сайт $counter раз/a");
* // массовая установка значений
* Session::setArray(['one' => 1, 'two' => 2, 'three' => 3]);
*/

namespace Ijurij\Geolocation\Lib;

/**
 * class include methods start, has(name), set(name), setArray(array of name-value),
 * get(name), flash(string), destroy(name).
 */
class Session
{
    public static function start()
    {
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
