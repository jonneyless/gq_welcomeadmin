<?php

namespace App\Models;

class Reply extends BaseModel
{
    protected $table = "group_reply";
    
    public static function boot()
    { 
        parent::boot();
        static::deleting(function ($model){
        });
        static::deleted(function ($model){
            if (strpos($model["val"], "http") !== false) {
                $obj = new ReplyDel();
                $obj->key = $model["key"];
                $obj->val = $model["val"];
                $obj->created_at = $model["created_at"];
                $obj->save();   
            }
        });
    }
}