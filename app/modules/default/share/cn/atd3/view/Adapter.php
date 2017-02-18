<?php
namespace view;
/**
* 视图适配器
* 
*/
class Adapter
{
    /**
    * 转换导航栏格式
    */
    public static function navigation(array $nav)
    {
        $navs=[];
        foreach ($nav as $nav_set) {
            $navs[$nav_set['id']]=$nav_set;
        }
        foreach ($navs as $id=>$nav_item) {
            if ($nav_item['parent'] && isset($navs[$nav_item['parent']]) ) {
                $navs[$nav_item['parent']]['child'][]=$nav_item;
                unset($navs[$id]);
            }
        }
        return $navs;
    }
}
