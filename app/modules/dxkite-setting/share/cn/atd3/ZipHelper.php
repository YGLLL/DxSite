<?php
namespace cn\atd3;

use ZipArchive;

class ZipHelper
{

    /**
     * 解压文件到目录
     *
     * @param string $inputFile
     * @param string $output
     * @return void
     */
    public static function unzip(string $inputFile, string $output, bool $cutself=false)
    {
        $zip=new ZipArchive;
        if ($zip->open($inputFile, ZipArchive::CHECKCONS)) {
            $name=basename($output);
            if (preg_match('/^'. preg_quote($name, '/').'/', $zip->getNameIndex(0))) {
                if ($cutself) {
                    $output=dirname($output);
                }
            }
            $zip->extractTo($output);
            $zip->close();
            return true;
        }
        return false;
    }
 

    /**
     * 压缩目录到文件
     *
     * @param string $path
     * @param string $output
     * @return void
     */
    public static function zip(string $path, string $output)
    {
        $zip=new ZipArchive;
        if ($zip->open($output, ZipArchive::CREATE|ZipArchive::OVERWRITE)) {
            self::zipFolder($zip, $path);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    protected static function zipFolder(ZipArchive & $zip, string $folder)
    {
        if (is_dir($folder)) {
            if ($dh = opendir($folder)) {
                while (($file = readdir($folder)) !== false) {
                    $path=$dir.'/'.$file;
                    if (is_dir($path)) {
                        self::zipFolder($zip, $path);
                    } else {
                        $zip->addFile($path);
                    }
                }
                closedir($dh);
            }
        }
    }
}
