<?php

namespace Ijurij\Geolocation\Lib;

/**
 * Cross Site Request Forgery (CSRF) Class.
 * session_start(); must be called before this is utilised.
 *
 * @Author Matt Kent (Matt_Kent9)
 *
 * @License MIT
 */
class Csrf
{
    // Empty constructor to avoid "Constructor cannot be static" error.
    public function __construct()
    {
    }

    /**
     * Used for is_recent() method.
     */
    public static int $max_elapsed = 60 * 60; // 1 hour
    /**
     * name of token in session variable.
     */
    public static string $token_name = 'csrf_token';
    /**
     * name of token in session variable.
     */
    public static string $token_time_name = 'csrf_token_time';

    /**
     * Generates token for use but doesn't store it.
     */
    public static function token(int $length): string
    {
        $randomString = \bin2hex(random_bytes($length));

        return \substr($randomString, 0, $length);
    }

    /**
     * Generate and store CSRF token in user session.
     * Requires session to have been started already.
     */
    private static function createToken(): string
    {
        $token = self::token(64);

        $data = [
            self::$token_name => $token,
            self::$token_time_name => \time(),
        ];
        Session::setArray($data);

        return $token;
    }

    /**
     * Return CSRF token in user session.
     */
    public static function getToken(): string
    {
        if (Session::has(self::$token_name)) {
            return Session::get(self::$token_name);
        } else {
            return self::createToken();
        }
    }

    /**
     * Destroys a token by removing it from the session.
     */
    private static function destroyToken(): bool
    {
        Session::destroy(self::$token_name);
        Session::destroy(self::$token_time_name);

        return true;
    }

    /**
     * Return HTML tag for use in a form.
     */
    public static function display(): string
    {
        return '<input type="hidden" name="'.self::$token_name.'" value="'.self::createToken().'" />';
    }

    /**
     * Returns true if user-submitted POST token is
     * identical to the previously stored SESSION token.
     * Returns false otherwise.
     */
    public static function isValid()
    {
        if (isset($_POST[self::$token_name])) {
            $user_token = $_POST[self::$token_name];
        }
        if (isset($_GET[self::$token_name])) {
            $user_token = $_GET[self::$token_name];
        }
        if (!empty($user_token)) {
            if (Session::has(self::$token_name)) {
                $stored_token = Session::get(self::$token_name);

                return \hash_equals($stored_token, $user_token);
            } else {
                return false;
            }
        }

        return false;
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
        if (Session::has(self::$token_time_name)) {
            $stored_time = Session::get(self::$token_time_name);

            return ($stored_time + self::$max_elapsed) >= \time();
        } else {
            self::destroyToken();

            return false;
        }
    }
}
