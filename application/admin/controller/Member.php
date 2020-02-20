<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;


use app\admin\model\MemberModel;

class Member extends Base
{
    // 文章列表
    public function index()
    {
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['nickname'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $m = new MemberModel();
            $selectResult = $m->getMemberByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){
                $selectResult[$key]['head_img'] = '<img src="' . $vo['head_img'] . '" width="40px" height="40px">';
                $selectResult[$key]['qrcode'] = empty($vo['qrcode']) ? '' : '<img src="' . $vo['qrcode'] . '" width="40px" height="40px">';
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $m->getAllMember($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    // 添加
    public function memberAdd()
    {
        if(request()->isPost()){
            $param = input('post.');

            unset($param['file']);
            $param['add_time'] = date('Y-m-d H:i:s');

            $m = new MemberModel();
            $flag = $m->addMember($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        return $this->fetch();
    }

    public function memberEdit()
    {
        $m = new MemberModel();
        if(request()->isPost()){

            $param = input('post.');
            unset($param['file']);
            $flag = $m->editMember($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $this->assign([
            'info' => $m->getOneMember($id)
        ]);
        return $this->fetch();
    }

    // 上传缩略图
    public function uploadHeadImg()
    {
        if(request()->isAjax()){

            $file = request()->file('file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload' . DS . 'head');
            if($info){
                $src =  '/upload/head/' . $info->getFilename();
                return json(msg(0, ['src' => $src], ''));
            }else{
                // 上传失败获取错误信息
                return json(msg(-1, '', $file->getError()));
            }
        }
    }

    public function uploadQrCodeImg()
    {
        if(request()->isAjax()){

            $file = request()->file('file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload' . DS . 'qrcode');
            if($info){
                $src =  '/upload/qrcode/' . $info->getFilename();
                return json(msg(0, ['src' => $src], ''));
            }else{
                // 上传失败获取错误信息
                return json(msg(-1, '', $file->getError()));
            }
        }
    }

    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return [
            '编辑' => [
                'auth' => 'member/memberedit',
                'href' => url('member/memberedit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ]
        ];
    }
}
