<?php
namespace app\index\controller;

use app\index\model\IndexModel;
use think\Controller;

class Index extends Controller
{
    protected $appid = '';// 微信APPID
    protected $secret = '';// 微信秘钥
    protected $redirect_url = 'http://example.com/info';// 回调地址
    protected $scope = 'snsapi_userinfo';// 授权作用域 snsapi_userinfo/snsapi_base

    public function index()
    {
        $this->redirect_url = urlencode($this->redirect_url);

        $url = 'https://open.weixin.qq.com/connect/qrconnect?appid='. $this->appid .'&redirect_uri='. $this->redirect_url .'&response_type=code&scope='. $this->scope .'&state=WEI#wechat_redirect';
        header("Location:" . $url);
    }

    public function getWxUserInfo()
    {
        $param = input('param.');

        $code = $param['code'];
        $state = $param['state'];
        if ($state != 'WEI'){
            return json(['code'=>304,'data'=>'','msg'=>'非法请求']);
        }
        if (empty($code)){
            return json(['code'=>403,'data'=>'','msg'=>'授权失败,请重试']);
        }

        $url = ' https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $this->appid .'&secret='. $this->secret .'&code='. $code .'&grant_type=authorization_code';
        // 根据code获取openid和access_token
        $res = file_get_contents($url);

        $res = json_decode($res);

        $url_end = 'https://api.weixin.qq.com/sns/userinfo?access_token='. $res['access_token'] .'&openid='. $res['openid'] .'&lang=zh_CN';
        // 根据openid和access_token获取用户信息
        $info = file_get_contents($url_end);

        $info = json_decode($info);

        // 判断用户是否已存在 存在->更新 不存在->新增
        $home = new IndexModel();
        $res = $home->getUserInfo(['openid'=>$info['openid']]);
        if (!empty($res)){
            // 更新
            $home->updateUserInfo(['nickname'=>$info[''],'head_img'=>$info['']],['openid'=>$info['openid']]);
        }else{
            // 新增
            $home->addUserInfo();
        }

        dump($info);
    }

    /**
     * 获取用户列表
     * @param pageNumber 第几页
     */
    public function getUserList()
    {
        $num = input('post.pageNumber');

        $home = new IndexModel();
    }

    /**
     * 获取用户详情
     * @param id 用户ID
     * @return string
     */
    public function getDetail()
    {
        $id = input('get.id');
        return '';
    }

}
