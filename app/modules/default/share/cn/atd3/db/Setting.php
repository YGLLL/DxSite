<?php

namespace cn\atd3\db;

use Query;

class Setting
{
    public static function set(string $name, string $value)
    {
        try {
            if (!self::update($name, $value)) {
                return Query::insert('site_setting', ['name'=>$name, 'value'=>$value]);
            }
        } catch (\Exception $e) {
            return true;
        }
        return true;
    }

    public static function delete(int $id)
    {
        return Query::delete('site_setting', ['id'=>$id]);
    }

    protected static function update(string $name, string $value)
    {
        return Query::update('site_setting', ['value'=>$value], ['name'=>$name]);
    }

    public static function get(string $name)
    {
        return Query::where('site_setting', ['id', 'name', 'value'], ['name'=>$name])->fetch();
    }

    public static function getAll()
    {
        return Query::where('site_setting', ['id', 'name', 'value'])->fetchAll();
    }
}
