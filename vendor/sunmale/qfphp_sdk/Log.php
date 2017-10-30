<?php
/**
 * Log类说明
 *通过 file_put_contents（）方法简单的将一些重要信息输出，便于程序调试或者记录一些不需要存放到数据库的信息
 * 输出的类型有 info,warn,debug,error四种形式。
 * 输出的路径在public/static/logs下，可以根据实际情况扩展相应 $type 的输出日志的目录，本例的 1,2分别代表普通信息输出目录跟支付信息输出目录
 */

namespace Qf;

class   Log
{

    public static function info($msg)
    {
        self::msg($msg, 'Info');
    }

    public static function warn($msg)
    {
        self::msg($msg, 'Warn');
    }

    public static function debug($msg)
    {
        self::msg($msg, 'Debug');
    }

    public static function error($msg)
    {
        self::msg($msg, 'Error');

    }

    /**
     *  记录信息
     * @param $msg
     * @param $log_type
     * @param null $path
     */
    public static function msg($msg, $log_type, $path = null)
    {
        if (empty($path)) {
            $path = __DIR__ . DIRECTORY_SEPARATOR . 'assert' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        }
        $year_month = date('Y-m', time());
        $day = date('d', time());
        $path = $path . $year_month . DIRECTORY_SEPARATOR;
        if (!is_dir($path)) {
            mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
        }
        $log_file = $path . $day . '.log';
        $msg = date("Y-m-d H:i:s") . "\t[$log_type]\t" . $msg . "\n";
        file_put_contents($log_file, $msg, FILE_APPEND);
    }

}
