<?php
namespace cn\atd3\db;

use Query;

class Categroy
{
    public static function create(int $icon, string $name, string $slug, string $discription, int $sort=0, int $parent=0)
    {
        return Query::insert('categroy', ['icon'=>$icon, 'name'=>$name, 'slug'=>$slug, 'discription'=>$discription, 'sort'=>$sort, 'parent'=>$parent]);
    }

    public static function delete(int $id)
    {
        return Query::delete('categroy', ['id'=>$id]);
    }
    public static function getIdByName(string $name)
    {
        return  ($get=Query::where('categroy', ['id'], ['name'=>$name])->fetch())?$get['id']:0;
    }
    public static function getIdBySlug(string $slug)
    {
        return  ($get=Query::where('categroy', ['id'], ['slug'=>$slug])->fetch())?$get['id']:0;
    }
    public static function update(int $id, string $name, string $slug, string $discription, int $sort=0, int $parent=0)
    {
        return Query::update('categroy', ['icon'=>$icon, 'name'=>$name, 'slug'=>$slug, 'discription'=>$discription, 'sort'=>$sort, 'parent'=>$parent], ['id'=>$id]);
    }
    
    public static function list(int $page=1, int $count=10)
    {
        return Query::where('categroy', ['id', 'icon', 'name', 'slug', 'discription', 'sort', 'parent'], '1', [], [$page, $count])->fetchAll();
    }
}
