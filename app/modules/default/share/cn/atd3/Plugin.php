<?php
namespace cn\atd3;

use cn\atd3\db\Setting;
use suda\core\Storage;
use suda\core\Request;
use suda\core\Hook;

interface PlugInterface
{
    public static function mount();       // 装载插件
    public static function umount();      // 卸载插件
    public static function boot();        // 启动插件
    public static function shutdown();    // 关闭插件
}


class Plugin
{
    protected static $mounted;
    protected static $plugin;

    public static function boot()
    {
        spl_autoload_register('Plugin::plugautoload');
        self::$mounted=Application::getSetting('mounted_plugin', []);
        foreach (self::$mounted as $plugin) {
            $name='Plugin\\'.$plugin;
            // 命名空间
            $name::boot();
        }
        Hook::listen('system:shutdown', 'Plugin::shutdown');
    }

    public static function hook(string $name, $callback, bool $namespace=true)
    {
        $name=$namespace?'plugin::'.$name:$name;
        return Hook::listen($name, $callback);
    }

    public static function shutdown()
    {
        foreach (self::$mounted as $plugin) {
            $name='Plugin\\'.$plugin;
            $name::shutdown();
        }
    }

    public static function plugautoload(string $name)
    {
        if (preg_match('/^Plugin\\\\(.+)$/',$name,$match))
        {
            // plugin/Name/Name.plug.php 
            $plugin=preg_replace('/^Plugin\\\\/','',$name);
            $root=APP_PLUGIN.DIRECTORY_SEPARATOR.preg_replace('/[\\\\_\/.]/', DIRECTORY_SEPARATOR,$plugin);
            preg_match('/(\w+)$/',$name,$match);
            $name=$match[1];
            $path=$root.'/'.$name.'.plug.php';
            self::$plugin[$name]=$path;
            if (file_exists($path)){
                require_once $path;
            }
        }
    }

    public static function publicDir(string $plugin,string $dir){

    }
}
