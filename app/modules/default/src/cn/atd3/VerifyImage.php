<?php
namespace cn\atd3;

use suda\core\Storage;
use cn\atd3\Session;

class VerifyImage
{
    public static $verifyschars='mnbvcxzasdfghjklpoiuytrewq1234567890QWERTYUIOPLKJHGFDSAZXCVBNM';
    public $foint;

    public function __construct()
    {
        $foints=Storage::readDirFiles(MODULE_RESOURCE.'/ttf/');
        $this->foint=$foints[mt_rand(0, count($foints)-1)];
    }

    public function create()
    {
        $size=4;
        $randCode = '';
        for ($i = 0; $i < $size; $i++) {
            $randCode .= substr(self::$verifyschars, mt_rand(0, strlen(self::$verifyschars) - 1), 1);
        }
        Session::set('human_varify', strtoupper($randCode));
        $img = imagecreate(80, 25);
        $bgColor =  imagecolorallocate($img, mt_rand(245, 255), mt_rand(245, 255), mt_rand(245, 255)) ;
        
        for ($i = 0; $i < $size; $i++) {
            $x = $i * 14 + mt_rand(4, 8) +10;
            $y = mt_rand(18, 22);
            $text_color = imagecolorallocate($img, mt_rand(30, 180), mt_rand(10, 100), mt_rand(40, 250));
            imagettftext($img, mt_rand(12, 14), mt_rand(0, 30), $x, $y, $text_color, $this->foint, $randCode[$i]);
        }

        for ($j = 0; $j < 60; $j++) {
            $pixColor = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 200), mt_rand(40, 250));
            $x = mt_rand(0, 80);
            $y = mt_rand(0, 25);
            imagesetpixel($img, $x, $y, $pixColor);
        }
        imagepng($img);
        imagedestroy($img);
    }


    public static function checkCode(string $code):bool
    {
       $result=Session::get('human_varify')===strtoupper($code);
       self::refresh();
       return $result;
    }

    public static function refresh()
    {
        Session::set('human_varify',null);
    }

    public function version()
    {
        return gd_info()['GD Version'];
    }
}
