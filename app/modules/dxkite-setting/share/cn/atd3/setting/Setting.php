<?php
namespace cn\atd3\setting;

use cn\atd3\dao\SettingDAO;
use suda\core\Cache;
use suda\template\Manager;

class Setting
{
    private static $setting;

    public static function load()
    {
        if (Cache::has('setting') && !conf('debug', true)) {
            self::$setting=Cache::get('setting');
        } else {
            $setting=(new SettingDAO)->list();
            if (is_array($setting)) {
                foreach ($setting as $value) {
                    self::$setting[$value['name']]=$value['value'];
                }
            }
            Cache::set('setting', self::$setting);
        }
        date_default_timezone_set(setting('timezone', 'PRC'));
        Manager::theme(setting('template',conf('app.template', 'default')));
    }

    public static function hookSetting($comp)
    {
        $comp->addCommand('setting', function ($args) {
            return '<?php echo setting'.$args.';?>';
        });
    }

    public static function get(string $name, $default=null)
    {
        return self::$setting[$name]??$default;
    }

    public static function set(string $name,$value){
        $return= (new SettingDao)->set($name, $value);
        Cache::delete('setting');
        static::load();
        return $return;
    }
}

require_once __DIR__.'/function.php';
