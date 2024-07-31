<?php


namespace App\DataModels;

class Word
{
    public static function index()
    {
        return view("word.index");
    }

    public static function in()
    {
        return view("word.in");
    }

    public static function username()
    {
        return view("word.username");
    }

    public static function intro()
    {
        return view("word.intro");
    }

    public static function search()
    {
        return view("word.search");
    }
}