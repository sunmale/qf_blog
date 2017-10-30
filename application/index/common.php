<?php
/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/9/14
 * Time: 10:48
 */


/**
 * 时间转换
 * @param $init_time
 * @return false|string
 */
function tran_time($init_time) {
    $y_time = date("y-m-d H:i",$init_time);
    $m_time =  date('m-d H:i',$init_time);
    $time = time() - $init_time;
    if ($time < 60) {
        $str = '刚刚';
    }
    elseif ($time < 60 * 60) {
        $min = floor($time/60);
        $str = $min.'分钟前';
    }
    elseif ($time < 60 * 60 * 24) {
        $h = floor($time/(60*60));
        $str = $h.'小时前 ';
    }
    elseif ($time < 60 * 60 * 24 * 3) {
        $d = floor($time/(60*60*24));
        $str = $d . '天前 ';
    } else if ( date('y',time()) == date('y',$init_time) ){
        $str =$m_time;
    }
    else {
        $str = $y_time;
    }
    return $str;
}









/**
 * 显示所有的菜单权限
 * @param $data
 * @param int $pid
 * @param int $level
 * @param int $index
 * @return array
 */
function article_type_to_tree($data, $pid = 0,$level=0,$index=0)
{
    $arr = array();
    foreach ($data as $k => $v) {
        if ($pid == $v['pid']) {
            $v['level'] = $level + 1;
            if($index!=0){
                if($index!=$v['level']){
                    $v['children'] = article_type_to_tree($data, $v['id'], $v['level'],$index);
                    if (empty($v['children'])) {
                        unset($v['children']);
                    }
                }
            }else{
                $v['children'] = article_type_to_tree($data, $v['id'], $v['level'],$index);
                if (empty($v['children'])) {
                    unset($v['children']);
                }
            }
            array_push($arr, $v);
        }
    }
    return $arr;
}







/**
 * 显示文章评论跟留言的父子级全部信息
 * @param $data
 * @param int $pid
 * @param int $level
 * @param int $index
 * @return array
 */
function message_to_tree($data, $pid = 0,$level=0,$index=0)
{
    $arr = array();
    foreach ($data as $k => $v) {
        if ($pid == $v['pid']) {
            $v['level'] = $level + 1;
            if($index!=0){
                if($index!=$v['level']){
                    $v['children'] = message_to_tree($data, $v['id'], $v['level'],$index);
                    if (empty($v['children'])) {
                        unset($v['children']);
                    }
                }
            }else{
                $v['children'] = message_to_tree($data, $v['id'], $v['level'],$index);
                if (empty($v['children'])) {
                    unset($v['children']);
                }
            }
            array_push($arr, $v);
        }
    }
    return $arr;
}