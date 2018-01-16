<?php
// +----------------------------------------------------------------------
// | LTHINK [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://LTHINK.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 涛哥 <liangtao.gz@foxmail.com>
// +----------------------------------------------------------------------
// | SinaSDK.php  By Taoge 2017/9/28 11:30
// +----------------------------------------------------------------------
namespace agang235\ThinkSDK\sdk;

use agang235\ThinkSDK\ThinkOauth;

class DiandianSDK extends ThinkOauth
{

    /**
     * 获取requestCode的api接口
     * @var string
     */
    protected $GetRequestCodeURL = 'https://api.diandian.com/oauth/authorize';

    /**
     * 获取access_token的api接口
     * @var string
     */
    protected $GetAccessTokenURL = 'https://api.diandian.com/oauth/token';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://api.diandian.com/v1/';

    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api 点点网API
     * @param  string $param 调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @return json
     */
    public function call($api, $param = '', $method = 'GET', $multi = false)
    {
        /* 点点网调用公共参数 */
        $params = array(
            'access_token' => $this->Token['access_token'],
        );

        $data = $this->http($this->url($api, '.json'), $this->param($params, $param), $method);
        return json_decode($data, true);
    }

    /**
     * 解析access_token方法请求后的返回值
     * @param string $result 获取access_token的方法的返回值
     * @param $extend
     * @return array|mixed|\stdClass
     * @throws \think\Exception
     */
    protected function parseToken($result, $extend)
    {
        $data = json_decode($result, true);
        if ($data['access_token'] && $data['expires_in'] && $data['token_type'] && $data['uid']) {
            $data['openid'] = $data['uid'];
            unset($data['uid']);
            return $data;
        } else
            throw new \think\Exception("获取点点网ACCESS_TOKEN出错：{$data['error']}");
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
            throw new \think\Exception('没有获取到点点网用户ID！');
    }

}
