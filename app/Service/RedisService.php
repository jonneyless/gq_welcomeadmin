<?php

namespace App\Service;

class RedisService
{
    public static function saveInit($item)
    {
        $key = "initGroup";
        $redis = app('redis.connection');

        $redis->rpush($key, json_encode($item));
    }
    
    public static function saveUserIn($item)
    {
        $redis = app('redis.connection');
        $redis->select(10);
        
        $key = "qunguanUserIn";   
        
        $redis->rpush($key, json_encode($item));
    }
    
    public static function changeUserNew($item)
    {
        // _qq 无意义，防止与存在的key冲突
        
        $redis = app('redis.connection');
        $redis->select(10);
        
        $key = "changeUserNew_qq";
        
        $redis->rpush($key, json_encode($item));
    }
    
    public static function changeUserGroupNew($item)
    {
        $redis = app('redis.connection');
        $redis->select(10);
        
        $key = "changeUserGroupNew_qq";   
        
        $redis->rpush($key, json_encode($item));
    }
    
    public static function saveMsg48($item)
    {
        $redis = app('redis.connection');
        $redis->select(10);
        
        $key = "saveMsg48_qq";   
        
        $redis->rpush($key, json_encode($item));
    }
}