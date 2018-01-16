# 项目简介：
> 1. 此项目并非原创,而是前端时间在github上查找第三方相关登录扩展时,没有找到合适的就在tp官网上找到一个比较全面和简洁的 
> 2. 参考项目 https://github.com/Aoiujz/ThinkSDK
> 3. 项目原地址 http://www.thinkphp.cn/extend/1050.html

1. 使用命名空间
```
use lt\ThinkSDK\ThinkOauth;
```
    
2. 设置三方登录的类别并赋予一个变量
```
$type = ThinkOauth::getInstance('qq');
```
    
3. 设置配置文件
```
'THINK_SDK_(TYPE)' => array(
        'APP_KEY'    => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK'   => '', //注册应用填写的callback
     ),
     上文中的(TYPE)为设置的类别，其值目前有以下几个：
        //腾讯QQ登录配置 THINK_SDK_QQ
        // 用户基本信息API接口 user/get_user_info
        //腾讯微博配置 THINK_SDK_TENCENT
        // 用户基本信息API接口 user/info
        //新浪微博配 THINK_SDK_SINA
        // 用户基本信息API接口 users/show。附加参数：'uid='.$obj->openid()
        //网易微博配置 THINK_SDK_T163
        // 用户基本信息API接口 users/show
        //人人网配置 THINK_SDK_RENREN
        // 用户基本信息API接口 users.getInfo
        //360配置 THINK_SDK_X360
        // 用户基本信息API接口 user/me
        //豆瓣配置 THINK_SDK_DOUBAN
        // 用户基本信息API接口 user/~me
        //Github配置 THINK_SDK_GITHUB
        // 用户基本信息API接口 user
        //Google配置 THINK_SDK_GOOGLE
        // 用户基本信息API接口 userinfo
        //MSN配置 THINK_SDK_MSN
        // 用户基本信息API接口 msn。附加参数：token
        //点点配置 THINK_SDK_DIANDIAN
        // 用户基本信息API接口 user/info
        //淘宝网配置 THINK_SDK_TAOBAO
        // 用户基本信息API接口 taobao.user.buyer.get。附加参数：'fields=user_id,nick,sex,buyer_credit,avatar,has_shop,vip_info'
        //百度配置 THINK_SDK_BAIDU
        // 用户基本信息API接口 passport/users/getLoggedInUser
        // 注意，百度的头像位置是http://tb.himg.baidu.com/sys/portrait/item/{$data['portrait']}
        //开心网配置 THINK_SDK_KAIXIN
        // 用户基本信息API接口 users/me
        //搜狐微博配置 THINK_SDK_SOHU
        // 用户基本信息API接口 i/prv/1/user/get-basic-info
```
    
4. 实例化一个登录页面
```
redirect($type->getRequestCodeURL());
        这里的$type是第二部获取的结果
```
    
5. 回调页面
```
$code = $this->get('code');
    $type = 'QQ';
    $sns  = ThinkOauth::getInstance($type);
    //腾讯微博需传递的额外参数
    $extend = null;
    if($type == 'tencent'){
            $extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
    }
    //请妥善保管这里获取到的Token信息，方便以后API调用
    //调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
    //如： $qq = ThinkOauth::getInstance('qq', $token);
    $token = $sns->getAccessToken($code , $extend);
    //获取当前登录用户信息
    if(is_array($token)){
        $data = $sns->call('user/get_user_info');
        if($data['ret'] == 0){
            $userInfo['type'] = 'QQ';
            $userInfo['name'] = $data['nickname'];
            $userInfo['nick'] = $data['nickname'];
            $userInfo['head'] = $data['figureurl_2'];
            // 此处的$userInfo就是需要的用户信息
        } else {
            throw new \think\Exception('获取腾讯QQ用户信息失败 : '.$data['msg']);
        }
    }
```
    
