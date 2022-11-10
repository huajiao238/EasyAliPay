## 支付宝证书模式PHP类库

PHP版本 >= 8.0

支持APP支付、电脑网页、手机网页支付、单笔转账到支付宝、单笔转账到银行卡、当面付（扫码模式）、当面付（付款码模式）、异步通知验签、支持链式调用。

### 调用示例：

##### 一、电脑网页支付：

```php
<?php
    
    require_once "EasyAliPay.php";
	
	try {
        $pay = new EasyAliPay();
        $pay->setAppId("应用APPID");    //请确保在该APPID下已开通了电脑网页支付
        $pay->setPrivate_key("应用私钥");
        //$pay->setPrivateKeyPath("私钥路径")   //与setPrivateKey二选一即可
        $pay->setBizContent([
            "total_amount" => 300,
            "out_trade_no" => "你的订单号",
            "subject" => "会员充值",
        ]);
        $pay->setReturnUrl("支付完返回地址");
        $pay->setNotifyUrl("异步通知地址");
        $pay->setRootSecretPath("./root_key.crt");  //设置支付宝根证书路径
        $pay->setAppSecretPath("./app_key.crt");	//设置应用证书路径
        $result =  $pay->doPaymentRequest();
        echo $result;
    }catch (ErrorException $e) {
        echo $e->getMessage();
    }

```

或：

```PHP
<?php
    
    require_once "EasyAliPay.php";
	
	try {
        $pay = new EasyAliPay();
        $result = $pay->setAppId("应用APPID")
            ->setPrivate_key("应用私钥")
            ->setBizContent([
                "total_amount" => 300,
                "out_trade_no" => "你的订单号",
                "subject" => "会员充值",
            ])
            ->setReturnUrl("支付完返回地址")
            ->setNotifyUrl("异步通知地址")
            ->setRootSecretPath("./root_key.crt")
            ->setAppSecretPath("./app_key.crt")
            ->doPaymentRequest();
        echo $result;
    }catch (ErrorException $e) {
        echo $e->getMessage();
    }
```

##### 二、手机网页支付

```php
<?php
    
    require_once "EasyAliPay.php";
	
	try {
        $pay = new EasyAliPay();
        $pay->setAppId("应用APPID");   //请确保在该APPID下已开通了H5支付
        $pay->setPrivate_key("应用私钥");
        //$pay->setPrivateKeyPath("私钥路径")   //与setPrivateKey二选一即可
        $pay->setBizContent([
            "total_amount" => 300,
            "out_trade_no" => "你的订单号",
            "subject" => "会员充值",
        ]);
        $pay->setReturnUrl("支付完返回地址");
        $pay->setNotifyUrl("异步通知地址");
        $pay->setRootSecretPath("./root_key.crt");  //设置支付宝根证书路径
        $pay->setAppSecretPath("./app_key.crt");	//设置应用证书路径
        $pay->setType("wap");
        $result =  $pay->doPaymentRequest();
        echo $result;
    }catch (ErrorException $e) {
        echo $e->getMessage();
    }

```

##### 三、单笔转账到支付宝账户

```php
<?php
require "EasyAliPay.php";

try {
    $pay = new EasyAliPay();
    $pay->setAppId("你的APPID");  //请确保在该APPID下已开通了单笔转账功能
    $pay->setBizContent([
        "out_biz_no" => "2022101902126",
        "trans_amount" => 1,
        "product_code"  => "TRANS_ACCOUNT_NO_PWD",
        "biz_scene"     =>  "DIRECT_TRANSFER",
        "order_title"   =>  "转账测试",
        "payee_info"    =>  [
            "identity"  =>  "xxxxxx@qq.com",  //对方支付宝登录手机号或邮箱
            "identity_type" =>  "ALIPAY_LOGON_ID",
            "name"  =>  "对方真实姓名"
        ]
    ]);
    $pay->setPrivateKey("你的私钥");
    $pay->setRootSecretPath("./root_key.crt");
    $pay->setAppSecretPath("./app_key.crt");
    $pay->setType("transfer");
    $new = $pay->doPaymentRequest();
    print_r($new);
}catch (ErrorException $e) {
    echo $e->getMessage();
}
```

##### 四、单笔转账到银行卡

```php
<?php
require "EasyAliPay.php";

try {
    $pay = new EasyAliPay();
    $pay->setAppId("你的APPID");    //请确保在该APPID下已开通了电脑网页支付
    $pay->setBizContent([
        "out_biz_no" => "2022101902126",  //订单号
        "trans_amount" => 1,  //金额
        "product_code"  => "TRANS_ACCOUNT_NO_PWD",
        "biz_scene"     =>  "DIRECT_TRANSFER",
        "order_title"   =>  "转账测试",
        "payee_info"    =>  [
            "identity"  =>  "对方银行卡号",
            "identity_type" =>  "BANKCARD_ACCOUNT",
            "name"  =>  "银行卡预留真实姓名",
            "bankcard_ext_info"	=> [
                "inst_name"	=>	"中国银行" 
                "account_type"	=>	"2" //1.对公，2：对私
            ]
        ]
    ]);
    $pay->setPrivateKey("你的私钥");
    $pay->setRootSecretPath("./root_key.crt");
    $pay->setAppSecretPath("./app_key.crt");
    $pay->setType("transfer");
    $new = $pay->doPaymentRequest();
    print_r($new);
}catch (ErrorException $e) {
    echo $e->getMessage();
}
```

##### 五、当面付（扫码模式）

```php
<?php
    
    require_once "EasyAliPay.php";
	
	try {
        $pay = new EasyAliPay();
        $pay->setAppId("应用APPID");  //请确保在该APPID下已开通了当面付
        $pay->setPrivate_key("应用私钥");
        //$pay->setPrivateKeyPath("私钥路径")   //与setPrivateKey二选一即可
        $pay->setBizContent([
            "total_amount" => 1,
            "out_trade_no" => "你的订单号",
            "subject" => "会员充值",
        ]);
        $pay->setNotifyUrl("异步通知地址");
        $pay->setRootSecretPath("./root_key.crt");  //设置支付宝根证书路径
        $pay->setAppSecretPath("./app_key.crt");	//设置应用证书路径
        $pay->setType("face_scan");
        $result =  $pay->doPaymentRequest();
        print_r($result);
    }catch (ErrorException $e) {
        echo $e->getMessage();
    }
```

##### 六、当面付（付款码模式）

```php
<?php
    
    require_once "EasyAliPay.php";
	
	try {
        $pay = new EasyAliPay();
        $pay->setAppId("应用APPID");  //请确保在该APPID下已开通了当面付
        $pay->setPrivate_key("应用私钥");
        //$pay->setPrivateKeyPath("私钥路径")   //与setPrivateKey二选一即可
        $pay->setBizContent([
            "total_amount" => 300,
            "out_trade_no" => "你的订单号",
            "subject" => "会员充值",
            "scene"		=>	"bar_code",
            "auth_code"	=>	"付款码内容，扫码枪扫出来的那一串字符串，25~30开头"
        ]);
        $pay->setNotifyUrl("异步通知地址");
        $pay->setRootSecretPath("./root_key.crt");  //设置支付宝根证书路径
        $pay->setAppSecretPath("./app_key.crt");	//设置应用证书路径
        $pay->setType("face_code");
        $result =  $pay->doPaymentRequest();
        print_r($result);
    }catch (ErrorException $e) {
        echo $e->getMessage();
    }
```

##### 七、APP支付

```php
<?php
    
    require_once "EasyAliPay.php";
	
	try {
        $pay = new EasyAliPay();
        $pay->setAppId("应用APPID");   //请确保在该APPID下已开通了APP支付
        $pay->setPrivate_key("应用私钥");
        //$pay->setPrivateKeyPath("私钥路径")   //与setPrivateKey二选一即可
        $pay->setBizContent([
            "total_amount" => 1,
            "out_trade_no" => "你的订单号",
            "subject" => "会员充值",
        ]);
        $pay->setNotifyUrl("异步通知地址");
        $pay->setRootSecretPath("./root_key.crt");  //设置支付宝根证书路径
        $pay->setAppSecretPath("./app_key.crt");	//设置应用证书路径
        $pay->setType("app");
        $result =  $pay->doPaymentRequest();
        echo $result;    //将$result发送给客户端，由客户端调用SDK发起支付即可
    }catch (ErrorException $e) {
        echo $e->getMessage();
    }
```



##### 八、异步通知验签

```php
require "EasyAliPay.php";

try {
    $pay = new EasyAliPay();
    $pay->setPublicKeyPath("你的支付宝公钥证书路径");
    if($pay->checkSign()) {
        //验签就是如此的简单
        //你自己的业务逻辑
        
        $pay->suceess();
    }
}catch (ErrorException $e) {
    echo $e->getMessage();
}
```


