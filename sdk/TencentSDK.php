<?php
// +----------------------------------------------------------------------
// | LTHINK [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://LTHINK.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 涛哥 <liangtao.gz@foxmail.com>
// +----------------------------------------------------------------------
// | TencentSDK.php  By Taoge 2017/9/28 11:36
// +----------------------------------------------------------------------
namespace agang235\ThinkSDK\sdk;

use agang235\ThinkSDK\ThinkOauth;

class TencentSDK extends ThinkOauth
{

    /**
     * 获取requestCode的api接口
     * @var string
     */
    protected $GetRequestCodeURL = 'https://open.t.qq.com/cgi-bin/oauth2/authorize';

    /**
     * 获取access_token的api接口
     * @var string
     */
    protected $GetAccessTokenURL = 'https://open.t.qq.com/cgi-bin/oauth2/access_token';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://open.t.qq.com/api/';

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
            'oauth_consumer_key' => $this->AppKey,
            'access_token' => $this->Token['access_token'],
            'openid' => $this->openid(),
            'clientip' => get_client_ip(),
            'oauth_version' => '2.a',
            'scope' => 'all',
            'format' => 'json'
        );

        $vars = $this->param($params, $param);
        $data = $this->http($this->url($api), $vars, $method, array(), $multi);
        return json_decode($data, true);
    }

    /**
     * 解析access_token方法请求后的返回值
     * @param string $result 获取access_token的方法的返回值
     * @param $extend
     * @return array
     * @throws \think\Exception
     */
    protected function parseToken($result, $extend)
    {
        parse_str($result, $data);
        $data = array_merge($data, $extend);
        if ($data['access_token'] && $data['expires_in'] && $data['openid'])
            return $data;
        else
            throw new \think\Exception("获取腾讯微博 ACCESS_TOKEN 出错：{$result}");
    }

    /**
     * 获取当前授权应用的openid
     * @return string
     * @throws \think\Exception
     */
    public function openid()
    {
        $data = $this->Token;
        if (isset($data['openid']))
            return $data['openid'];
        else
            throw new \think\Exception('没有获取到openid！');
    }

}
