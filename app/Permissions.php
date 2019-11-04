<?php

namespace App;


class Permissions
{
    public const CREATE_POST = 1;
    public const EDIT_MY_POST = 2;
    public const DELETE_MY_POST = 3;
    public const EDIT_ANY_POST = 4;
    public const DELETE_ANY_POST = 5;

    public static function all()
    {
        return [
            [
                'id' => self::CREATE_POST,
                'name' => 'Create Post'
            ],
            [
                'id' =>  self::EDIT_ANY_POST,
                'name' => 'Edit My Post'
            ],
            [
                'id' => self::DELETE_MY_POST,
                'name' => 'Delete My Post'
            ],
            [
                'id' => self::EDIT_ANY_POST,
                'name' => 'Delete Any Post'
            ],
            [
                'id' => self::DELETE_ANY_POST,
                'name' => 'Delete Any Post'
            ],
        ];
    }
}
