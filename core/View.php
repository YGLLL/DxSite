<?php
class View
{
    private static $compiler=null;
    private static $values=[];
    private static $use=null;
    public static function loadCompile()
    {
        if (is_null(self::$compiler)) {
            $compiler='View_Compiler_'. conf('Driver.View', 'Pomelo');
            self::$compiler=new $compiler;
        }
    }
    
    public static function set(string $name, $value)
    {
        self::$values[$name]=$value;
    }

    public static function theme(string $theme=null)
    {
        if (is_null($theme)) {
            return self::$compiler->getTheme();
        }
        self::$compiler->setTheme($theme);
    }

    public static function use(string $page)
    {
        self::$use=$page;
    }

    public static function assign(array $values)
    {
        self::$values=array_merge(self::$values, $values);
    }
    public static function resource($path)
    {
        $extension=pathinfo($path, PATHINFO_EXTENSION);
        // Resource
        if (array_key_exists($extension, mime()) && Storage::exist($path=APP_VIEW.'/'.$path)) {
            return $path;
        }
        return false;
    }
    public static function type(string $type)
    {
        header('Content-type: '.mime($type, 'text/plain;charset=UTF-8'));
    }
    public static function render(string $page, array $values=[])
    {
        // 合并数据
        self::assign($values);
        // 内部可设置界面
        $page=is_null(self::$use)?$page:self::$use;
        // 获取界面路径
        $file=self::$compiler->viewPath($page);
        // var_dump($file);
        if (Storage::exist($file)) {
            // 分解变量
            extract(self::$values, EXTR_OVERWRITE);
            require_once $file;
        } else {
            trigger_error($page.' TPL no Find!');
        }
    }
    public static function tplRoot()
    {
        return self::$compiler->tplRoot();
    }
    public static function compile($input)
    {
        return self::$compiler->compileFile($input);
    }
    public static function compileAll(string $theme='default')
    {
        if (self::$compiler)
        {
            $theme=self::$compiler->getTheme();
        }
        
        $files=Storage::readDirFiles(APP_TPL.'/'.$theme, '/\.pml\.html$/', true);
        Storage::rmdirs(APP_VIEW,true);
        foreach ($files as $file) {
            View::compile($file);
        }
        $extensions='';
        foreach (array_keys (mime()) as $ext)
        {
            $extensions.='|'.$ext;
        }
        $extensions=trim($extensions,'|');
        $resources=Storage::readDirFiles(APP_TPL.'/'.$theme,'/(?<!\.pml)\.('.$extensions.')$/',true,false);
        foreach ($resources as $resource)
        {
            $path=APP_VIEW.'/'.$resource;
            Storage::mkdirs(dirname($path));
            Storage::copy(APP_TPL.'/'.$theme.'/'.$resource,$path);
        }
    }
}
