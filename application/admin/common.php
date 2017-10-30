<?php
/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/9/14
 * Time: 10:48
 */



/**
 * 显示所有的菜单权限
 * @param $data
 * @param int $pid
 * @param int $level
 * @param int $index
 * @return array
 */
function rule_to_tree($data, $pid = 0,$level=0,$index=0)
{
    $arr = array();
    foreach ($data as $k => $v) {
        if ($pid == $v['pid']) {
            $v['href_name'] = $v['name'];
            $v['name'] = $v['title'];
            $v['level'] = $level + 1;
            $v['spread'] = false;
            if($index!=0){
                if($index!=$v['level']){
                    $v['children'] = rule_to_tree($data, $v['id'], $v['level'],$index);
                    if (empty($v['children'])) {
                        unset($v['children']);
                    }
                }
            }else{
                $v['children'] = rule_to_tree($data, $v['id'], $v['level'],$index);
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
 * 左侧菜单显示
 * @param $data
 * @param int $pid
 * @param int $level
 * @param int $index
 * @return array
 */
function nav_left_tree($data, $pid = 0,$level=0,$index=0)
{
    $arr = array();
    foreach ($data as $k => $v) {
        if ($pid == $v['pid']) {
            $v['level'] = $level + 1;
            $v['icon'] = $v['css'];
            $v['href'] = $v['name'];
            $v['spread'] = false;
            if($index!=0){
                if($index!=$v['level']){
                    $v['children'] = nav_left_tree($data, $v['id'], $v['level'],$index);
                    if (empty($v['children'])) {
                        unset($v['children']);
                    }
                }
            }else{
                $v['children'] = nav_left_tree($data, $v['id'], $v['level'],$index);
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
            $v['name'] = $v['title'];
            $v['level'] = $level + 1;
            $v['spread'] = true;
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