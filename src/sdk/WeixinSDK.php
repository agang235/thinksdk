<?php
// +----------------------------------------------------------------------
// | LTHINK [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://LTHINK.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 涛哥 <liangtao.gz@foxmail.com>
// +----------------------------------------------------------------------
// | WeixinSDK.php  By Taoge 2017/9/28 11:37
// +----------------------------------------------------------------------
namespace agang235\ThinkSDK\sdk;

use agang235\ThinkSDK\ThinkOauth;

class WeixinSDK extends ThinkOauth
{
    /**
     * 获取用户信息的api接口
     * 参数说明: http://wiki.open.qq.com/wiki/【QQ登录】get_user_info
     * @var string
     */
    protected $GetUserInfoURL = 'https://api.weixin.qq.com/sns/userinfo';

    /**
     * 获取requestCode的api接口
     * @var string
     */
    protected $GetRequestCodeURL = 'https://open.weixin.qq.com/connect/qrconnect';

    /**
     * 获取access_token的api接口
     * @var string
     */
    protected $GetAccessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://api.weixin.qq.com/';

    /**
     * 初始化配置
     */
    private function config()
    {
        $config = config('sdk')["THINK_SDK_WEIXIN"];
        if (!empty($config['AUTHORIZE']))
            $this->Authorize = $config['AUTHORIZE'];
        if (!empty($config['CALLBACK']))
            $this->Callback = $config['CALLBACK'];
        else
            throw new \think\Exception('请配置回调页面地址',100004);
    }

    public function getRequestCodeURL()
    {
        $this->config();
        $params = array(
            'appid' => $this->AppKey,
            'redirect_uri' => $this->Callback,
            'response_type' => 'code',
            'scope' => 'snsapi_login'
        );
        return $this->GetRequestCodeURL . '?' . http_build_query($params);
    }

    /**
     * 获取access_token
     * @param string $code 上一步请求到的code
     * @return array|mixed|null|\stdClass
     */
    public function getAccessToken($code, $extend = null)
    {
        $params = array(
            'appid' => $this->AppKey,
            'secret' => $this->AppSecret,
            'grant_type' => $this->GrantType,
            'code' => $code,
        );
        $data = $this->http($this->GetAccessTokenURL, $params, 'POST');
        $this->Token = $this->parseToken($data, $extend);
        return $this->Token;
    }

    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api 微博API
     * @param  string $param 调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @return json
     */
    public function call($api, $param = '', $method = 'GET', $multi = false)
    {
        /* 腾讯微博调用公共参数 */
        $params = array(
            'access_token' => $this->Token['access_token'],
            'openid' => $this->openid(),
        );
        $vars = $this->param($params, $param);
        $data = $this->http($this->url($api), $vars, $method, array(), $multi);
        return json_decode($data, true);
    }


    /**
     * 解析access_token方法请求后的返回值
     */
    protected function parseToken($result, $extend)
    {
        $data = json_decode($result, true);
        //parse_str($result, $data);
        if ($data['access_token'] && $data['expires_in']) {
            $this->Token = $data;
            $data['openid'] = $this->openid();
            return $data;
        } else
            throw new \think\Exception("获取微信 ACCESS_TOKEN 出错：{$result}");
    }

    /**
     * 获取当前授权应用的openid
     */
    public function openid()
    {
        $data = $this->Token;
        if (!empty($data['openid']))
            return $data['openid'];
        else
            exit('没有获取到微信用户ID！');
    }

    /**
     * 获取用户资料 需要2个参数
     * 1.access_token 授权码
     * 2.openid 用户在本站的唯一标志码
     * @param $token
     * @return array
     * @throws \think\Exception
     */
    public function getUserInfo($token)
    {
        // 如果键值不存在,抛出错误
        if(!isset($token['access_token']) || !isset($token['openid'])){
            throw new \think\Exception("缺少必要参数");
        }
        $params = [
            'access_token' => $token['access_token'],
            'openid' => $token['openid'],
        ];
        $data = $this->http($this->GetUserInfoURL, $params, 'GET');
        return json_decode($data, true);
    }
}



