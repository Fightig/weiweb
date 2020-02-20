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
namespace app\admin\validate;

use think\Validate;

class MemberValidate extends Validate
{
    protected $rule = [
        ['nickname', 'require', '请输入会员名'],
        ['head_img', 'require', '请上传会员头像']
    ];

}