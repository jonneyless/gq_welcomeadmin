<?php

namespace App\Service;

use App\Models\Config;

class ConfigService
{
    public static function val($key)
    {
        $query = Config::query();

        $query->where("key", $key);

        return $query->value("val");
    }

    public static function valLimit()
    {
        $query = Config::query();

        $query->where("description", "limit");

        return $query->get();
    }

    public static function get($key)
    {
        return Config::query()->where("key", $key)->first();
    }

    public static function set($config, $val)
    {
        $config->val = $val;
        $config->save();
    }
}