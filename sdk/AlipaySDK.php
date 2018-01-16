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

class AlipaySDK extends ThinkOauth
{
    /**
     * 获取requestCode的api接口
     * @var string
     */
    protected $GetRequestCodeURL = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm';

    /**
     * API根路径
     * @var string
     */
    protected $ApiBase = 'https://openapi.alipay.com/gateway.do';

    public function getRequestCodeURL()
    {
        $this->Callback = config('sdk')['THINK_SDK_ALIPAY']['CALLBACK'];
        $params = [
            'app_id' => $this->AppKey,// 商户的APPID
            'scope' => 'auth_user',
            'redirect_uri' => $this->Callback,
            'state' => 'init'
        ];
        return $this->GetRequestCodeURL . '?' . http_build_query($params);
    }

    public function getAccessToken($code = '', $extend = '')
    {
        $this->Callback = config('sdk')['THINK_SDK_ALIPAY']['CALLBACK'];
        $params = array(
            'app_id' => $this->AppKey,// 支付宝分配给开发者的应用ID
            'method' => 'alipay.system.oauth.token', // 接口名称
            'format' => 'JSON',
            'charset' => 'utf-8',
            'timestamp' => date('Y-m-d H:i:s'),// 发送请求的时间，格式"yyyy-MM-dd HH:mm:ss"
            'version' => '1.0',// 调用的接口版本，固定为：1.0
            'grant_type' => 'authorization_code',// 值为authorization_code时，代表用code换取；值为refresh_token时，代表用refresh_token换取
            'code' => $code,
            'sign_type' => 'RSA2' // 商户生成签名字符串所使用的签名算法类型，目前支持RSA2和RSA，推荐使用RSA2
        );
        $params['sign'] = $this->createSign($params); // 签名
        $result = $this->http($this->ApiBase, $params, 'get');
        $json = json_decode($result, true);
        $data = $json['alipay_system_oauth_token_response'];
        if ($data['access_token'] && $data['expires_in'] && $data['re_expires_in'] && $data['user_id']) {
            $this->Token = $data['access_token'];
            return $data;
        } else {
            throw new \think\Exception("获取支付宝ACCESS_TOKEN出错：{$data['error']}");
        }
    }

    protected function createSign($params)
    {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                $v = $this->characet($v, "UTF-8");

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $this->sign($stringToBeSigned);
    }

    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset)
    {

        if (!empty($data)) {
            $fileType = "UTF-8";
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //				$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }


        return $data;
    }

    protected function sign($data, $signType = "RSA2")
    {
        $priKey = config('sdk')['THINK_SDK_ALIPAY']['APP_SECRET'];
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 组装接口调用参数 并调用接口
     */
    public function call($api, $param = array(), $method = 'POST', $multi = false)
    {
        $param['app_id'] = $this->AppKey; // 支付宝分配给开发者的应用ID
        $param['method'] = $api;// 接口名称
        $param['format'] = 'JSON';
        $param['charset'] = 'utf-8';
        $param['sign_type'] = 'RSA2';
        $param['timestamp'] = date('Y-m-d H:i:s');// 发送请求的时间，格式"yyyy-MM-dd HH:mm:ss";
        $param['version'] = '1.0';
        $param['auth_token'] = $this->Token['access_token'];
        $param['sign'] = $this->createSign($param); // 签名
        $data = $this->http($this->ApiBase, $param, $method);
        $json = json_decode(mb_convert_encoding($data,'utf-8','gbk'),true);
        return $json['alipay_user_info_share_response'];
    }

    /**
     * 获取当前授权应用的openid
     */
    public function openid()
    {
        $data = $this->Token;
        if (!empty($data['user_id']))
            return $data['user_id'];
        else
            exit('没有获取到支付宝用户ID！');
    }

    /**
     * 解析access_token方法请求后的返回值
     */
    protected function parseToken($result, $extend)
    {
    }

}

?>

