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
namespace app\admin\model;

use think\Model;

class MemberModel extends Model
{
    // 确定链接表名
    protected $name = 'members';

    public function getMemberByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    public function getAllMember($where)
    {
        return $this->where($where)->count();
    }

    public function addMember($param)
    {
        try{
            $result = $this->validate('MemberValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('member/index'), '添加成功');
            }
        }catch (\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    public function editMember($param)
    {
        try{

            $result = $this->validate('MemberValidate')->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('member/index'), '编辑成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    public function getOneMember($id)
    {
        return $this->where('id', $id)->find();
    }

}
