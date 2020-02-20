<?php

namespace app\index\model;

use think\Model;

class IndexModel extends Model
{
    //
    protected $name = '';

    public function  getByWhereList($where)
    {
        return $this->where($where)->select();
    }

    public function getUserInfo($where)
    {
        return $this->where($where)->find();
    }

}
