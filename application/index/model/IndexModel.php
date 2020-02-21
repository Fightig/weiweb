<?php

namespace app\index\model;

use think\Model;

class IndexModel extends Model
{
    //
    protected $name = 'members';

    public function  getListByWhere($where,$offset,$limit)
    {
        return $this->where($where)->limit($offset,$limit)->order('CONVERT(nickname USING gbk) desc')->select();
    }

    public function getCount($where)
    {
        return $this->where($where)->count();
    }

    public function getOneUserInfo($where)
    {
        return $this->where($where)->find();
    }

}
