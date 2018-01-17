# 项目简介：
> 1. 此项目并非原创,而是前端时间在github上查找第三方相关登录扩展时,没有找到合适的就在tp官网上找到一个比较全面和简洁的 
> 2. 参考项目 https://github.com/Aoiujz/ThinkSDK
> 3. 项目原地址 http://www.thinkphp.cn/extend/1050.html  

1. 使用命名空间
```
use agang235\ThinkSDK\ThinkOauth;
```
    
2. 设置三方登录的类别并赋予一个变量
```
$type = ThinkOauth::getInstance('qq');
```
    
3. 设置配置文件
```
请查看 files/extra/sdk.php文件

```
    
4. 实例化一个登录页面
```
redirect($type->getRequestCodeURL());
        这里的$type是第二部获取的结果
```
    
5. 回调页面
```
请参照 files/extra/controller/Third.php文件

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

//         array(4) {//$token QQ登录返回数据样例
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
```
    