<?php

namespace App\Service;

use App\Models\ConfigText;

class ConfigTextService
{
    public static function one($name)
    {
        return ConfigText::query()->where("name", $name)->first();
    }

    public static function setKey($config, $key)
    {
        $config->keyy = $key;
        $config->save();
    }
    
    public static function setVal($config, $val)
    {
        $config->val = $val;
        $config->save();
    }
}