<?php
/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/8/17
 * Time: 10:56
 */
namespace  qf;
class  Auto_load{
    /**
     * 通过命名空间自动加载类
     * @param $class
     * @return bool
     */
    public static function loadByNamespace($class)
    {
        $logicalPathPsr4 = strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
        $class_file = __DIR__  . substr($logicalPathPsr4, strlen('qf'));
        if (is_file($class_file))
        {
             require_once  $class_file;
            if (class_exists($class, false))
            {
                return true;
            }
        }
        return false;
    }
}
spl_autoload_register('\qf\Auto_load::loadByNamespace');