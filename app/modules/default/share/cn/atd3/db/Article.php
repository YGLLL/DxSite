<?php
namespace db;

use Query;

class Article
{
    // 储存类型
    const TYPE_HTML=1; // HTML
    const TYPE_MD=0;   // Markdown

    const STATE_PUBLISH=1;// 发布
    const STATE_DRAFT=2;  // 草稿
    const STATE_DELETE=3; // 删除
    const STATE_VERIFY=4; // 待审核
    // 创建文章
    public static function create(int $author, int $categroy, string $index, string $title, string $abstract, string $content, int $type, int $allow_reply=1, int $state=self::STATE_VERIFY)
    {
        return Query::insert('article', ['author'=>$author,
        'categroy'=>$categroy,
        'index'=>$index,
        'title'=>$title,
        'abstract'=>$abstract,
        'content'=>$content,
        'type'=>$type,
        'create'=>time(),
        'update'=>time(),
        'allow_reply'=>$allow_reply,
        'state'=>$state]);
    }

    public static function update(int $id,int $author,int $categroy,string $index,string $title,string $abstract,string $content,int $type,int $view,int $create,int $update,int $reply,int $allow_reply,int $state){
       return Query::update('article',['id'=>$id,
       'author'=>$author,
       'categroy'=>$categroy,
       'index'=>$index,
       'title'=>$title,
       'abstract'=>$abstract,
       'content'=>$content,
       'type'=>$type,
       'view'=>$view,
       'create'=>$create,
       'update'=>$update,
       'reply'=>$reply,
       'allow_reply'=>$allow_reply,
       'state'=>$state]); 
    }

	public static function get(int $id)
    {
        return ($get=Query::where('article', ['id','author','categroy','index','title','abstract','content','type','view','create','update','reply','allow_reply','state'],['id'=>$id])->fetch() && self::viewCount($id)) ? $get  : false;
    }

    public static function search(string $search,int $page=1, int $count=10){
        return Query::where('article', ['id','author','categroy','index','title','abstract','type','view','create','update','reply','allow_reply'],' `title` LIKE CONCAT("%",:like,"%") OR `index` LIKE CONCAT("%",:like,"%") OR `content` LIKE CONCAT("%",:like,"%")', ['like'=>$search], [$page, $count])->fetchAll();
    }

    public static function list(int $page=1, int $count=10)
    {
        return Query::where('article', ['id','author','categroy','index','title','abstract','type','view','create','update','reply','allow_reply'],['state'=>self::STATE_PUBLISH], [], [$page, $count])->fetchAll();
    }

    public static function listSort(string $field, int $type=SORT_ASC, int $page=1, int $count=10)
    {
        if (!in_array($field, ['update', 'create', 'view'])) {
            $field='update';
        }
        $order=$type===SORT_ASC?'ASC':'DESC';
        return Query::where('article',  ['id','author','categroy','index','title','abstract','type','view','create','update','reply','allow_reply'], 'state =:state ORDER BY `'.$field.'` '.$order, ['state'=>self::STATE_PUBLISH], [$page, $count])->fetchAll();
    }
    public static function setState(int $id, int $state)
    {
        return Query::update('article', ['state'=>$state], ['id'=>$id]);
    }

    public static function count()
    {
        return Query::count('article');
    }

    public static function setCategory(int $id, int $categroy)
    {
        try {
            Query::begin();
            Query::update('article', ['categroy'=>$categroy], ['id'=>$id]);
            Query::update('categroy', 'count = count +1', ['id'=>$categroy]);
            Query::commit();
        } catch (\Exception $e) {
            Query::rollBack();
            return false;
        }
        return true;
    }

    public static function viewCount(int $id)
    {
        return Query::update('article', 'view = view +1', ['id'=>$id]);
    }

    public static function replyCount(int $id)
    {
        return Query::update('article', 'reply = reply +1', ['id'=>$id]);
    }

    public static function addTag(int $article, string $name)
    {
        if (($tag=Tag::getId($name)) || ($tag=Tag::create($name))) {
            if (Query::insert('article_tag', ' (`article`,`tag`) SELECT :article,:tag FROM DUAL WHERE NOT EXISTS (SELECT `article`,`tag` FROM `#{article_tag}` WHERE article=:article AND tag=:tag ) ', ['article'=>$article, 'tag'=>$tag])) {
                return Tag::countAdd($tag);
            }
            return true;
        }
        return false;
    }
}
