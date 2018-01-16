<?php
// +----------------------------------------------------------------------
// | LTHINK [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://LTHINK.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 涛哥 <liangtao.gz@foxmail.com>
// +----------------------------------------------------------------------
// | WeixinSDK.php  By Taoge 2017/9/28 11:30
// +----------------------------------------------------------------------

namespace lt\ThinkSDK\sdk;

use lt\ThinkSDK\ThinkOauth;

class BaiduSDK extends ThinkOauth
{
    /**
     * 获取requestCode的api接口
     * @var string
     */
    protected $GetRequestCodeURL = 'https://openapi.baidu.com/oauth/2.0/authorize';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://openapi.baidu.com/oauth/2.0/token';

    public function getRequestCodeURL()
    {
        $this->Callback = config('sdk')['THINK_SDK_BAIDU']['CALLBACK'];
        $params = [
            'client_id' => $this->AppKey,// 应用的唯一标示，既注册应用时获得的API Key。
            'response_type' => 'code',
            'redirect_uri' => $this->Callback,
        ];
        return $this->GetRequestCodeURL . '?' . http_build_query($params);
    }

    public function getAccessToken($code = '', $extend = '')
    {
        $this->Callback = config('sdk')['THINK_SDK_BAIDU']['CALLBACK'];
        $params = array(
            'client_id' => $this->AppKey,// 应用的唯一标示，即注册应用时获得的API Key；
            'client_secret' => $this->AppSecret, // 应用的私钥，即注册应用时获得的Secret Key；
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->Callback,
        );
        $result = $this->http($this->ApiBase, $params, 'get');
        $data = json_decode($result, true);
        if ($data['access_token'] && $data['expires_in'] && $data['refresh_token'] && $data['session_secret']) {
            return $data;
        } else {
            throw new \think\Exception("获取百度ACCESS_TOKEN出错：{$data['error']}");
        }
    }

    /**
     * 组装接口调用参数 并调用接口
     */
    public function call($api, $param = array(), $method = 'POST', $multi = false)
    {
        $param['client_id'] = $this->AppKey;
        $param['access_token'] = $this->Token['access_token'];
        $param['domain'] = $_SERVER['HTTP_HOST'];
        $data = $this->http('https://openapi.baidu.com/rest/2.0/' . $api, $param, $method);
        return json_decode($data, true);
    }

    /**
     * 获取当前授权应用的openid
     */
    public function openid()
    {
        $data = $this->Token;
        if (!empty($data['uid']))
            return $data['uid'];
        else
            exit('没有获取到百度用户ID！');
    }

    /**
     * 解析access_token方法请求后的返回值
     */
    protected function parseToken($result, $extend)
    {
    }

}

?>

