<?php

namespace App\Models;

class KeywordReply extends BaseModel
{
    protected $table = "keyword_reply";

    public function getTypeAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = json_encode(array_map('intval', array_values($value)));
    }

    public function getSenderTypeAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setSenderTypeAttribute($value)
    {
        $this->attributes['sender_type'] = json_encode(array_map('intval', array_values($value)));
    }

    public function getRepliesAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setRepliesAttribute($value)
    {
        if ($value) {
            $value = array_values($value);
        } else {
            $value = [];
        }

        $this->attributes['replies'] = json_encode($value);
    }

    public function getButtonsAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setButtonsAttribute($value)
    {

        if ($value) {
            $value = array_values($value);
        } else {
            $value = [];
        }

        $this->attributes['buttons'] = json_encode($value);
    }
}