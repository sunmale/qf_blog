<?php
namespace app\index\controller;

use app\common\model\MessageModel;
use app\common\model\SaidModel;
use app\common\model\ZanModel;

class Message extends Base
{

    /**
     * 显示留言
     * @return mixed
     */
    public function index()
    {
        $message = new MessageModel();
        if (request()->isAjax()) {
           $data =  request()->param();
            $map['pid'] = 0;
            if(!empty($data['article_id'])){
                $map['type'] = 2;
                $map['article_id'] = $data['article_id'];
            }else{
                $map['type'] = 1;
            }
            $page['pageIndex'] = request()->param('page');
            $page['pageSize'] = 5;
            $message_list = $message->selectPageByMap($map, $page);
            $message_count = $message->selectCountByMap($map);
            $page['pageCount'] = ceil($message_count / $page['pageSize']);
            foreach ($message_list as $k => $v) {
                //把转换成显示状态
                $v['tran_time'] = tran_time(strtotime($v['create_time']));

                //留言关联点赞数量
                $v['zan_count']  = $message->get($v['id'])->zan()->count();

                //查询每个父级留言或者评论的子集
                $child_map['pid'] = $v['id'];
                $v['children'] =   $message->selectInfoByMap($child_map);
                if(!empty($v['children'])){
                    foreach ($v['children'] as $p=>$q){
                        $q['tran_time'] = tran_time(strtotime($q['create_time']));
                        $q['zan_count']  = $message->get($q['id'])->zan()->count();

                      //  $v['children'][$p] = $q;
                    }
                }

                $message_list[$k] = $v;
            }
            $data['data'] = $message_list;
            $data['pageCount'] = $page['pageCount'];
            return json($data);
        }
        if (request()->isGet()) {
            return $this->fetch();
        }
    }


    /**
     * 添加留言
     */
    public function add()
    {
        $user = $this->base();
        $message = new MessageModel();
        if (request()->isAjax()) {
            $data = request()->post();
            if (empty($user)) {
                return json(['code' => -1, 'msg' => '请先登录']);
            }
            if (empty($data['param'])) {
                $data['pid'] = 0;
            }else{
              $params =   explode(',',$data['param']);
                $data['pid'] = $params[0];
                $data['reply_id'] = $params[1];
                $data['reply_nickname'] = $params[2];
            }
            if(!empty($data['article_id'])){
                $data['type'] = 2;
            }else{
                $data['type'] = 1;
            }
            $data['userid'] = $user['id'];
            $data['nickname'] = $user['nickname'];
            $data['email'] = $user['email'];
            $data['headurl'] = $user['headurl'];
            $data['os'] = get_os();
            $data['address'] = get_address_by_ip(request()->ip());
            //释放这个参数
            unset($data['param']);
            $res = $message->insertMessage($data);
            if ($res == -1) {
                return json(['code' => -1, 'msg' => '程序异常，留言失败，请稍后重试']);
            } else {
                if(!empty($data['article_id'])){
                    return json(['code' => 1, 'msg' => '评论成功']);
                }else{
                    return json(['code' => 1, 'msg' => '留言成功']);
                }

            }

        }
    }





    /**
     * 给评论或者留言点赞
     */
     public  function  addZan(){
         $user = $this->base();
         $message = new MessageModel();
         $zan = new ZanModel();
         if (request()->isAjax()) {
             $data = request()->post();
             if (empty($user)) {
                 return json(['code' => -1, 'msg' => '请先登录']);
             }
             //如果该用户已经点赞提示已经点赞过了
             $zan_map['userid'] = $user['id'];
             $zan_map['message_id'] = $data['message_id'];
            $zan_res =  $zan->selectInfoByMap($zan_map,1);
              if($zan_res && $zan_res->toArray()!=-1){
                  return json(['code' => -1, 'msg' => '你已经点赞了']);
              }

             $data['userid'] = $user['id'];
             $data['os'] = get_os();
             $data['address'] = get_address_by_ip(request()->ip());
             //释放这个参数
             $res = $zan->insertZan($data);
             if ($res == -1) {
                 return json(['code' => -1, 'msg' => '程序异常，点赞失败，请稍后重试']);
             } else {
                 return json(['code' => 1, 'msg' => '点赞成功']);
             }
         }
     }





}
