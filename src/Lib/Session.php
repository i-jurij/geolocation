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

    public function has($name)
    {
        static::start();

        return isset($_SESSION[$name]);
    }

    public function set($name, $value)
    {
        static::start();
        if ($name && $value) {
            $_SESSION[$name] = $value;
        }
        session_write_close();
    }

    public function setArray(array $vars)
    {
        static::start();
        foreach ($vars as $name => $value) {
            $this->set($name, $value);
            session_write_close();
        }
    }

    public function get($name)
    {
        static::start();

        return (!empty($_SESSION[$name])) ? $_SESSION[$name] : false;
    }

    /**
     * Если вызвать $this->flash со строковым параметром, то она сохранит эту строку в сессии,
     * а если вызвать без параметров, то возвратит сохранённое сообщение.
     *
     * @param $message - string or null
     */
    public function flash(?string $message = null)
    {
        static::start();
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
    public function destroy($name)
    {
        static::start();
        unset($_SESSION[$name]);
        session_write_close();
    }
}
