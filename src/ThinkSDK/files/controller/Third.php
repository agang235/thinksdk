<?php
// +----------------------------------------------------------------------
// | Created by PhpStorm. * User: Administrator * Date: 2018/1/15 * Time: 5:46
// +----------------------------------------------------------------------
// | 用途描述: 第三方登录
// +----------------------------------------------------------------------
// | 注意事项:
// +----------------------------------------------------------------------

namespace app\index\controller;
use think\Controller;
use agang235\ThinkSDK\ThinkOauth;
//use app\user\event\TypeEvent;


class Third extends Controller
{
    /**
     * 登录地址
     * 访问 http://www.xxx.com/index/third/index?type=qq
     */
    public function index()
    {
        //获取type值
        $type = input('param.type',null); //不存在则设为null
        if(!$type){
            $this->error('type值不存在');
        }else{
            //加载ThinkOauth类并实例化一个对象
            $sns = ThinkOauth::getInstance($type);
            //跳转到授权页面
            $this->redirect($sns->getRequestCodeURL());
        }
    }

    //授权回调地址
    public function callback($type = null, $code = null)
    {
        //支付宝code
        if ('alipay' == $type) {
            $code = $_GET['auth_code'];
        }
        (empty($type) || empty($code)) && $this->error('参数错误');

        //加载ThinkOauth类并实例化一个对象
        $sns = ThinkOauth::getInstance($type);

        //腾讯微博需传递的额外参数
        $extend = null;
        if ($type == 'tencent') {
            $extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
        }

        //请妥善保管这里获取到的Token信息，方便以后API调用
        //调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
        //如： $qq = ThinkOauth::getInstance('qq', $token);
        $token = $sns->getAccessToken($code, $extend);

//         array(4) {//$token
//         ["access_token"] => string(32) "A987D3A8400EA0FE5523797DD15D3277"
//         ["expires_in"] => string(7) "7776000"
//         ["refresh_token"] => string(32) "9F6E5D471FD744AFD4FA3A1EC117A583"
//         ["openid"] => string(32) "F8F13521AFC2443D03FD8CD01FB58C1F"
//          }
        //获取用户头像,昵称,性别等信息,
        //注意: 目前只支持qq和微信获取用户信息,其他的还没加,如果需要,用户可以自己在sdk中模仿微信和qq的方法来添加getUserInfo()方法
        $userInfo = $sns->getUserInfo($token);
        dump($userInfo);

    }

}