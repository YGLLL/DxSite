<?php

class MdArticle
{
    // 待上传的图片
    public $images=[];
    public $url=[];
    // 文档的属性设置
    public $config=[];
    // 文档内容
    public $markdown;
    
    public function __construct(string $markdown)
    {
        $md=self::parserHeader($markdown);
        $this->config=$md['config'];
        $this->markdown=$md['markdown'];
        $urls=self::parserUrl($markdown);
        $this->images=$urls['images'];
        $this->urls=$urls['urls'];
    }

    protected static function parserUrl(string $markdown){
        $images=[];
        $url=[];
        if(preg_match_all('/(!)?\[.+?\]\((.+?)\)/',$markdown,$matchs))
        {
            for($i=0;$i<count($matchs[0]);$i++){
                if ($matchs[1][$i]){
                    $images[$matchs[2][$i]]=$matchs[2][$i];
                }else{
                    $url[$matchs[2][$i]]=$matchs[2][$i];
                }
            }
        }
        return ['images'=>$images,'urls'=>$url];
    }
    protected static function parserCategory(string $categorystr){
        if (preg_match('/>?\s*([^|]+)\s*(?:\|\s*(.+)\s*)?$/',$categorystr,$matchs))
        {
            $name=$matchs[1];
            if (isset($matchs[2])){
                $slug=$matchs[2];
                 return ['name'=>trim($name),'slug'=>trim($slug)];
            }
             return ['name'=>trim($name)];
        }
       return false;
    }

    protected static function parserHeader(string $markdown)
    {
        // 不合格式的上传Md不将支持
        $config=null;
        $markdown=preg_replace_callback('/^\s*(#(?:.+?))-{3,}\s*\r?\n/ism', function ($matchs) use (&$config) {
            $config['time']=time();
            $config['public']=1;
            $config['top']=0;
            $config['reply']=1;
            $config['finish']=0;
            $header=$matchs[1];
            // 我是不是该用下循环？？
            if (preg_match('/^\s*#{1,6}\s(.+)$/im', $header, $tagmatch)) {
                $config['title']=trim($tagmatch[1]);
            }
            if (preg_match('/^\s*(tags?|标签)([^:]*?)\s*:\s*(.+)$/im', $header, $tagmatch)) {
                $config['tags']=trim($tagmatch[3]);
            }
            if (preg_match('/^\s*(a?id|编号)([^:]*?)\s*:\s*(.+)$/im', $header, $tagmatch)) {
                $config['aid']=intval($tagmatch[3]);
            }
            if (preg_match('/^\s*(index|索引)([^:]*?)\s*:\s*(.+)$/im', $header, $tagmatch)) {
                $config['index']=intval($tagmatch[3]);
            }
            if (preg_match('/^\s*(categorys?|分类)([^:]*?)\s*:\s*(.+)$/im', $header, $tagmatch)) {
                $config['category']=self::parserCategory($tagmatch[3]);
            }
            if (preg_match('/^\s*(author|作者)([^:]*?)\s*:\s*(.+)$/im', $header, $tagmatch)) {
                $config['author']=trim($tagmatch[3]);
            }
            if (preg_match('/^\s*(times?|时间)([^:]*?)\s*:\s*(.+)$/im', $header, $tagmatch)) {
                if (($unix=strtotime(trim($tagmatch[3]))) > 0) {
                    $config['time']=$unix;
                }
            }
            if (preg_match('/^\s*(remark|摘要)([^:]*?)\s*:\s*(.+)\r?\n\r?\n/ims', $header, $tagmatch)) {
                $config['remark']=$tagmatch[3];
            }
            if (preg_match('/^\s*(status?|状态)([^:]*?)\s*:\s*(.+)\r?\n\r?\n/ims', $header, $tagmatch)) {
                if (preg_match('/(save|草稿)/i', $tagmatch[3])) {
                    $config['public']=0;
                }
                if (preg_match('/((keep-)?top|置顶)/i', $tagmatch[3])) {
                    $config['top']=1;
                }
                if (preg_match('/(finish|完成)/i', $tagmatch[3])) {
                    $config['finish']=1;
                }
            }
            if (preg_match('/^\s*(disallow(ed)?|不允许)([^:]*?)\s*:\s*(.+)\r?\n\r?\n/ims', $header, $tagmatch)) {
                if (preg_match('/(noreply|(不允许)?回复)/i', $tagmatch[3])) {
                    $config['reply']=0;
                }
            }
        }, $markdown, 1);
        return  ['config'=>$config,'markdown'=>$markdown];
    }
}
