<?php
// +----------------------------------------------------------------------
// | LTHINK [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://LTHINK.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 涛哥 <liangtao.gz@foxmail.com>
// +----------------------------------------------------------------------
// | QqSDK.php  By Taoge 2017/9/28 11:34
// +----------------------------------------------------------------------
namespace agang235\ThinkSDK\sdk;

use agang235\ThinkSDK\ThinkOauth;
use agang235\curl\Url;

class QqSDK extends ThinkOauth
{

    /**
     * 获取用户信息的api接口
     * 参数说明: http://wiki.open.qq.com/wiki/【QQ登录】get_user_info
     * @var string
     */
    protected $GetUserInfoURL = 'https://graph.qq.com/user/get_user_info';

    /**
     * 获取requestCode的api接口
     * @var string
     */
    protected $GetRequestCodeURL = 'https://graph.qq.com/oauth2.0/authorize';

    /**
     * 获取access_token的api接口
     * @var string
     */
    protected $GetAccessTokenURL = 'https://graph.qq.com/oauth2.0/token';

    /**
     * 获取request_code的额外参数,可在配置中修改 URL查询字符串格式
     * @var string
     */
    protected $Authorize = 'scope=get_user_info,add_share';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://graph.qq.com/';

    /**
     * 组装接口调用参数 并调用接口
     * @param  string $api 微博API
     * @param  string $param 调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @return json
     */
    public function call($api, $param = '', $method = 'GET', $multi = false)
    {
        /* 腾讯QQ调用公共参数 */
        $params = [
            'oauth_consumer_key' => $this->AppKey,
            'access_token' => $this->Token['access_token'],
            'openid' => $this->openid(),
            'format' => 'json'
        ];
        $data = $this->http($this->url($api), $this->param($params, $param), $method);
        return json_decode($data, true);
    }

    /**
     * 解析access_token方法请求后的返回值
     * @param string $result 获取access_token的方法的返回值
     * @param $extend
     * @return mixed
     * @throws \think\Exception
     */
    protected function parseToken($result, $extend)
    {
        parse_str($result, $data);
        if ($data['access_token'] && $data['expires_in']) {
            $this->Token = $data;
            $data['openid'] = $this->openid();
            return $data;
        } else
            throw new \think\Exception("获取腾讯QQ ACCESS_TOKEN 出错：{$result}");
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
        elseif ($data['access_token']) {
            $data = $this->http($this->url('oauth2.0/me'), array('access_token' => $data['access_token']));
            $data = json_decode(trim(substr($data, 9), " );\n"), true);
            if (isset($data['openid']))
                return $data['openid'];
            else
                throw new \think\Exception("获取用户openid出错：{$data['error_description']}");
        } else {
            throw new \think\Exception('没有获取到openid！');
        }
    }

    /**
     * 获取用户资料 需要三个参数
     * 1.access_token 授权码
     * 2.openid 用户在本站的唯一标志码
     * 3.oauth_consumer_key 应用注册成功后分配的 APP ID
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
            'oauth_consumer_key' => $this->AppKey, //app id
            'access_token' => $token['access_token'],
            'openid' => $token['openid'],
            'format' => 'json'
        ];
        $data = $this->http($this->GetUserInfoURL, $params, 'GET');
        return json_decode($data, true);
    }
}
