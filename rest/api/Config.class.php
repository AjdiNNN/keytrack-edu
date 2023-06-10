<?php

class Config
{
    public static function DB_HOST()
    {
        return Config::get_env("DB_HOST", "localhost");
    }
    public static function DB_USERNAME()
    {
        return Config::get_env("DB_USERNAME", "root");
    }
    public static function DB_PASSWORD()
    {
        return Config::get_env("DB_PASSWORD", "");
    }
    public static function DB_SCHEME()
    {
        return Config::get_env("DB_SCHEME", "keytrack");
    }
    public static function DB_PORT()
    {
        return Config::get_env("DB_PORT", "3306");
    }
    public static function JWT_SECRET()
    {
        return Config::get_env("JWTSECRET", "dc813309ba2546eff071cc496d0792aa01536c96cd1c0c7984b779bce030ea00");
    }
    public static function get_env($name, $default)
    {
        return isset($_ENV[$name]) && trim($_ENV[$name]) != '' ? $_ENV[$name] : $default;
    }
}