<?php

namespace cn\atd3;
class Api
{

    /* 接口权限检查 */

    public static function permission(array $permissions)
    {
        $id=self::getSignInUserId();
        if ($id) {
            if (!db\User::hasPermission($id, $permissions)) {
                return new api\Error('hasNoUserToken', 'need user_token to check permission');
            }
        } else {
            return new api\Error('hasNoPermission', 'no permission');
        }
        return true;
    }

    /*API 接口参数检查 */
    public static function checkValues($value_input, array $checks)
    {
        if (!$value_input) {
            return new api\Error('nullInput', 'please check the json!');
        }
        $param=[];
        foreach ($checks as $key=>$value) {
            // ['name']
            // ['name'=>'type']
            // ['name'=>['type','default']]

            if (is_numeric($key)) {
                $name=$value;
                $type='string';
                $has_default=false;
            } else {
                $name=$key;
                if (is_array($value)) {
                    $type= array_shift($value);
                    if (count($value)) {
                        $default=array_shift($value);
                        $has_default=true;
                    } else {
                        $has_default=false;
                    }
                } else {
                    $type=$value;
                    $has_default=false;
                }
            }
            
            if (is_array($value_input)) {
                if (!isset($value_input[$name]) &&  !$has_default) {
                    return new api\Error('paramError', 'need '.$name);
                } else {
                    $val=isset($value_input[$name])?$value_input[$name]:$default;
                    if (@settype($val, $type)) {
                        $param[$name]=$val;
                    } else {
                        return new api\Error('paramTypeCastError', $name .' cannot be '.$type);
                    }
                }
            } elseif (is_object($value_input)) {
                if (!isset($value_input->$name) &&   !$has_default) {
                    return new api\Error('paramError', 'need '.$name);
                } else {
                    $val=isset($value_input->$name)?$value_input->$name:$default;
                    if (@settype($val, $type)) {
                        $param[$name]=$val;
                    } else {
                        return new api\Error('paramTypeCastError', $name .' cannot be '.$type);
                    }
                }
            }
        }

        return $param;
    }


    public static function checkCallback($value_input, array $checks, $callback)
    {
        $param=self::checkValues($value_input, $checks);
        if ($param instanceof api\Error) {
            return $param;
        }
        $return = (new base\Command($callback))->exec($param);
        if ($return instanceof api\Error) {
            return $return;
        } else {
            return ['return'=> $return];
        }
    }
}
