<?php

namespace cn\atd3\db\article;

use Query;

class Attachment
{
    public static function create(int $article, int $data, int $type)
    {
        return Query::insert('article_attachment', ['article'=>$article, 'data'=>$data, 'type'=>$type]);
    }

    public static function delete(int $id)
    {
        return Query::delete('article_attachment', ['id'=>$id]);
    }
    
    public static function getByArticle(int $article)
    {
        return ($get=Query::where('article_attachment', ['id', 'article', 'data', 'type'], ['article'=>$article])->fetchAll()) ? $get  : false;
    }
    
    public static function get(int $id)
    {
        return ($get=Query::where('article_attachment', ['id', 'article', 'data', 'type'], ['id'=>$id])->fetch()) ? $get  : false;
    }
    
    public static function update(int $id, int $article, int $data, int $type)
    {
        return Query::update('article_attachment', ['id'=>$id, 'article'=>$article, 'data'=>$data, 'type'=>$type]);
    }
    public static function list(int $page=1, int $count=10)
    {
        return Query::where('article_attachment', ['id', 'article', 'data', 'type'], '1', [], [$page, $count])->fetchAll();
    }
}
