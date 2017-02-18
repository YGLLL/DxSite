<?php

namespace db;

use Query;

class Tag
{
    public static function create(string $name)
    {
        try {
            return Query::insert('tag', ['name'=>$name]);
        } catch (\Exception $e) {
            return self::getId($name);
        }
        return 0;
    }
    
    public static function delete(int $id)
    {
        return Query::delete('tag', ['id'=>$id]);
    }

    public static function update(int $id, string $name)
    {
        return Query::update('tag', ['id'=>$id, 'name'=>$name]);
    }

    public static function getId(string $name)
    {
        return ($fetch=Query::where('tag', ['id'], ['name'=>$name])->fetch())?intval($fetch['id']):0;
    }

    public static function countAdd(int $id)
    {
        return Query::update('tag', 'count=count+1', ['id'=>$id]);
    }

    public static function list(int $page=1, int $count=10)
    {
        return Query::where('tag', ['id', 'name', 'count'], '1', [], [$page, $count])->fetchAll();
    }
}
