<?php

namespace Ijurij\Geolocation\Lib;

/**
 * Cross Site Request Forgery (CSRF) Class.
 *
 * @Author Matt Kent (Matt_Kent9)
 *
 * @License MIT
 *
 * session_start(); must be called before this is utilised.
 */
class Csrf
{
    // Empty constructor to avoid "Constructor cannot be static" error.
    public function __construct()
    {
    }

    // Used for is_recent() method.
    private static $max_elapsed = 60 * 60 * 24; // 1 day

    /**
     * Generates token for use but doesn't store it.
     */
    private static function token(int $length): string
    {
        $randomString = bin2hex(random_bytes($length));

        return substr($randomString, 0, $length);
    }

    /**
     * Generate and store CSRF token in user session.
     * Requires session to have been started already.
     */
    private static function createToken(): string
    {
        $token = self::token(64);

        $data = [
            'token' => $token,
            'token_time' => time(),
        ];
        Session::setArray($data);

        return $token;
    }

    /**
     * Destroys a token by removing it from the session.
     */
    private static function destroyToken(): bool
    {
        Session::destroy('token');
        Session::destroy('token_time');

        return true;
    }

    /**
     * Return HTML tag for use in a form.
     */
    public static function display(): string
    {
        return '<input type="hidden" name="token" value="'.self::createToken().'" />';
    }

    /**
     * Returns true if user-submitted POST token is
     * identical to the previously stored SESSION token.
     * Returns false otherwise.
     */
    public static function isValid()
    {
        if (isset($_POST['token'])) {
            $user_token = $_POST['token'];
        } else {
            return false;
        }
        if (Session::has('token')) {
            $stored_token = Session::get('token');

            return hash_equals($stored_token, $user_token);
        } else {
            return false;
        }
    }

    /**
     * You can simply check the token validity and
     * handle the failure yourself, or you can use
     * this "stop-everything-on-failure" method.
     */
    public static function exitOnFailure()
    {
        if (!self::isValid()) {
            exit('Invalid Security Token.');
        }
    }

    /**
     * This doesn't have to be used but it
     * checks to see if the token is recent.
     */
    public static function isRecent()
    {
        if (isset($_SESSION['token_time'])) {
            $stored_time = $_SESSION['token_time'];

            return ($stored_time + self::$max_elapsed) >= time();
        } else {
            self::destroyToken();

            return false;
        }
    }
}
