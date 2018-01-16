<?php
// +----------------------------------------------------------------------
// | LTHINK [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://LTHINK.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 涛哥 <liangtao.gz@foxmail.com>
// +----------------------------------------------------------------------
// | sdk_config.php 2015-12-01
// +----------------------------------------------------------------------
// | 获取变量: config('sdk')["THINK_SDK_QQ"]
// | 或动态获取 config('sdk')["THINK_SDK_{$this->Type}"];
// +----------------------------------------------------------------------

//定义回调URL通用的URL,建议在每个配置中写死,不要用这种连接的方式,可能会出现意想不到的错误
//如: 'CALLBACK' => 'http://xxx.com/index/third/callback/type/qq',
define('URL_CALLBACK', 'http://' . $_SERVER['SERVER_NAME'] . '/user/oauth/callback/type/');

return [
    //支付宝登录mapi
    'THINK_SDK_ALIPAY' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'alipay',
    ],
    //微信登录
    'THINK_SDK_WEIXIN' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'weixin',
    ],
    //腾讯QQ登录配置
    'THINK_SDK_QQ' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'qq',

    ],
    //腾讯微博配置
    'THINK_SDK_TENCENT' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'tencent',
    ],
    //新浪微博配置
    'THINK_SDK_SINA' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'sina',
    ],
    //网易微博配置
    'THINK_SDK_T163' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 't163',
    ],
    //人人网配置
    'THINK_SDK_RENREN' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'renren',
    ],
    //360配置
    'THINK_SDK_X360' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'x360',
    ],
    //豆瓣配置
    'THINK_SDK_DOUBAN' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'douban',
    ],
    //Github配置
    'THINK_SDK_GITHUB' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'github',
    ],
    //Google配置
    'THINK_SDK_GOOGLE' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'google',
    ],
    //MSN配置
    'THINK_SDK_MSN' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'msn',
    ],
    //点点配置
    'THINK_SDK_DIANDIAN' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'diandian',
    ],
    //淘宝网配置
    'THINK_SDK_TAOBAO' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'taobao',
    ],
    //百度配置
    'THINK_SDK_BAIDU' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'baidu',
    ],
    //开心网配置
    'THINK_SDK_KAIXIN' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'kaixin',
    ],
    //搜狐微博配置
    'THINK_SDK_SOHU' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'sohu',
    ],

];