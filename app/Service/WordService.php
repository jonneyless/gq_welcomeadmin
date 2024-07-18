<?php


namespace App\Service;

use App\Models\Word;

class WordService
{
    public static function get($condition = [])
    {
        $query = Word::query();

        if (is_right_data($condition, "id")) {
            $query->where("id", $condition["id"]);
        }
        if (is_right_data($condition, "name")) {
            $query->where("name", $condition["name"]);
        }
        if (is_right_data($condition, "type")) {
            $query->where("type", $condition["type"]);
        }

        if (is_right_data($condition, "is_one_obj")) {
            return $query->first();
        }
        if (is_right_data($condition, "is_one_arr")) {
            return obj_to_array($query->first());
        }

        $query->orderBy("id", "desc");

        if (is_right_data($condition, "is_arr")) {
            return obj_to_array($query->get());
        }

        return $query->get();
    }

    public static function create($data = [])
    {
        $word = new Word();
        $word->name = $data["name"];
        $word->level = $data["level"];
        $word->type = $data["type"];
        $word->save();
    }

    public static function delete($word)
    {
        $word->delete();
    }

    public static function changeLevel($word, $level)
    {
        $word->level = $level;
        $word->save();
    }
}