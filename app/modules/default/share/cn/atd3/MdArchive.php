<?php
namespace cn\atd3;
class MdArchive
{
    protected $archive=null;
    protected $uploads=[];
    protected $index=null;
    protected $uid=0;
    protected $erron=0;
    protected $error=[
        1=>'Unreadable Zip Archive!'
    ];
    public function __construct(string $filename)
    {
        $this->archive=new ZipArchive;
        $this->uid=User::getSignInUserId();
        if ($this->archive->open($filename) && $index=self::getIndex()){
            $markdown=new MdArticle(self::get($index));
            if (!isset($markdown->config['index'])) {
                $markdown->config['index']=self::catIndex($index);
            }
            $this->index=$markdown;
            self::prepare();
            var_dump($this->index);
        }
        else{
            $erron=1;
        }
    }


    protected function get(string $filename){
        return $this->archive->getFromName($filename);
    }

    protected function catIndex(string $filename){
        $filename=preg_replace('/\.md$/i','',$filename);
        $filename=substr(preg_replace('/[^A-Za-z_-]/','-',$filename),-32,32);
        return preg_replace('/-+/','-',$filename);
    }


    protected function getIndex()
    {
        for ($i = 0; $i < $this->archive->numFiles; $i++) {
            $stat=$this->archive->statIndex($i);
            if (preg_match('/(readme|index)\.md/i', $stat['name']) === 1) {
                return  $stat['name'];
            }
        }
    }
    protected function prepare()
    {
        foreach( $this->index->images as $index => $image )
        {
               $this->index->images[$index]=self::uploadFile(self::path($image));
        }
    }
    protected function uploadFile(string $fname){
        $file=self::get($name);
        $md5=md5($file);
        $type=pathinfo($fname,PATHINFO_EXTENSION);
        $name=pathinfo($fname,PATHINFO_FILENAME);
        $size=strlen($file);
        $path=APP_RESOURCE.'/upload/'.$md5;
        Storage::put($path,$file);
        if ($id=db\Upload::create($this->uid,$name,$size,$type,$md5)){
            return $id;
        }
    }

    public function uploadZipMarkdown(string $filename, string $name='')
    {
        $zip=new ZipArchive;
        $res = $zip->open($filename);
        $ret=[];
        if ($res === true) {
            $this->archive=$zip;
            $file=self::getMarkdownFiles();
            //var_dump($file);
            $ret=['file'=>$file,'return'=>self::uploadMarkdown($file)];
            $zip->close();
        } else {
            $this->error='read zip failed!';
            return -1;
        }
        return $ret;
    }
    protected function path(string $path)
    {
        // 根目录去除
        $path=preg_replace('/^(\.{1,2}(\/|\\\\))*/', '', $path);
        $preg='/(\/|\\\\)(.+?)(\/|\\\\)\.\./';
        while (preg_match($preg, $path)) {
            $path=preg_replace($preg, '', $path);
        }
        $path=preg_replace('/^(.+?)(\/|\\\\)\.\.(\/|\\\\)/', '', $path);
        return $path;
    }
}
