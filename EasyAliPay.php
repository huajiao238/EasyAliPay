<?php

/**
 * @author huajiao238
 * @url https://github.com/huajiao238
 * @date 2021-10-19
 * @email 1032601165@qq.com
 */
class EasyAliPay
{
    /*appid*/
    private string $app_id = "";
    /*字符集*/
    private string $charset = "UTF-8";
    /*返回地址*/
    private string $return_url = "";
    /*异步回调地址*/
    private string $notify_url = "";
    /*版本号*/
    private string $version = "1.0";
    /*加签方式*/
    private string $sign_type = "RSA2";
    /*请求时间*/
    private string $time_stamp = "";
    /*私钥路径*/
    private string $private_key_path = "";
    /*请求体*/
    private array $biz_content = [];
    /*私钥文本内容*/
    private string $private_key = "";
    /*支付宝根证书路径*/
    private string $root_secret_path = "";
    /*支付宝应用证书路径*/
    private string $app_secret_path = "";
    /*支付宝网关*/
    private string $url = "https://openapi.alipay.com/gateway.do";
    /*支付方式*/
    private string $type = "pc";
    /*接口名称*/
    private array $pay_method = [
        "pc" => "alipay.trade.page.pay",
        "wap" => "alipay.trade.wap.pay",
        "transfer" => "alipay.fund.trans.uni.transfer",
        "face_scan"  =>  "alipay.trade.precreate",
        "face_code" => "alipay.trade.pay",
        "app"       =>  "alipay.trade.app.pay"
    ];
    /*支付宝公钥证书路径*/
    private string $public_key_path = "";


    /**
     * @throws ErrorException
     */
    public function __construct()
    {
        if (version_compare(PHP_VERSION, "8.0", "<")) {
            throw new ErrorException("需要PHP版本在8以上");
        }
        $this->time_stamp = date("Y-m-d H:i:s");
    }

    /**
     * @param string $app_id APPID
     * @return EasyAliPay
     */
    public function setAppId(string $app_id): EasyAliPay
    {
        $this->app_id = $app_id;
        return $this;
    }

    /**
     * @param string $charset 字符集
     * @return EasyAliPay
     */
    public function setCharset(string $charset): EasyAliPay
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * @param string $return_url 返回地址
     * @return EasyAliPay
     */
    public function setReturnUrl(string $return_url): EasyAliPay
    {
        $this->return_url = $return_url;
        return $this;
    }

    /**
     * @param string $sign_type 签名方式
     * @return EasyAliPay
     */
    public function setSignType(string $sign_type): EasyAliPay
    {
        $this->sign_type = $sign_type;
        return $this;
    }

    /**
     * @param string $private_key_path 私钥路径
     * @return EasyAliPay
     * @throws ErrorException
     */
    public function setPrivateKeyPath(string $private_key_path): EasyAliPay
    {
        if (!file_exists($private_key_path)) {
            throw new ErrorException("私钥文件不存在");
        }
        $this->private_key_path = $private_key_path;
        return $this;
    }

    /**
     * @param array $biz_content 请求参数
     * @return EasyAliPay
     */
    public function setBizContent(array $biz_content): EasyAliPay
    {
        $this->biz_content = $biz_content;
        return $this;
    }

    /**
     * @param string $private_key 私钥
     * @return EasyAliPay
     */
    public function setPrivateKey(string $private_key): EasyAliPay
    {
        $this->private_key = $private_key;
        return $this;
    }

    /**
     * @param string $notify_url 异步通知地址
     * @return EasyAliPay
     */
    public function setNotifyUrl(string $notify_url): EasyAliPay
    {
        $this->notify_url = $notify_url;
        return $this;
    }

    /**
     * @param string $path 支付宝根证书路径
     * @return EasyAliPay
     * @throws ErrorException
     */
    public function setRootSecretPath(string $path): EasyAliPay
    {
        if (!file_exists($path)) {
            throw new ErrorException("支付宝根证书文件不存在");
        }
        $this->root_secret_path = $path;
        return $this;
    }

    /**
     * @param string $path 应用根证书路径
     * @return EasyAliPay
     * @throws ErrorException
     */
    public function setAppSecretPath(string $path): EasyAliPay
    {
        if (!file_exists($path)) {
            throw new ErrorException("支付宝应用证书文件不存在");
        }
        $this->app_secret_path = $path;
        return $this;
    }

    /**
     * @param string $type 支付类型  pc:电脑网页支付(默认)，wap:手机网页支付
     * @return EasyAliPay
     * @throws ErrorException
     */
    public function setType(string $type): EasyAliPay
    {
        if (!array_key_exists($type, $this->pay_method)) {
            throw new ErrorException("不支持的支付类型");
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $path 支付宝公钥路径
     * @return EasyAliPay
     * @throws ErrorException
     */
    public function setPublicKeyPath(string $path): EasyAliPay
    {
        if (!file_exists($path)) {
            throw new ErrorException("未找到支付宝公钥文件");
        }
        $this->public_key_path = $path;
        return $this;
    }

    /**
     * 发起支付
     * @throws ErrorException
     * @return string|array
     */
    public function doPaymentRequest(): string|array
    {
        if (!$this->private_key_path && !$this->private_key) {
            throw new ErrorException("请设置私钥");
        }
        if (count($this->biz_content) < 3) {
            throw new ErrorException("请设置请求参数");
        }
        if (!$this->root_secret_path) {
            throw new ErrorException("请设置支付根据证书路径");
        }
        if (!$this->app_secret_path) {
            throw new ErrorException("请设置应用证书路径");
        }
        if ($this->type == "pc" && !array_key_exists("product_code", $this->biz_content)) {
            $this->biz_content["product_code"] = "FAST_INSTANT_TRADE_PAY";
        }
        $result = $this->buildOriginSignContent();
        $sign = $this->sign($this->signArrayToString($result));
        $result["sign"] = $sign;
        return match ($this->type) {
            "wap", "pc" => $this->buildRequestForm($result),
            "face_scan","face_code", "transfer" => $this->request($result),
            "app"   => http_build_query($result),
            default => throw new ErrorException("不支持的支付类型"),
        };
    }

    /**
     * 验签
     * @return bool
     */
    public function checkSign(): bool
    {
        $data = $_POST;
        $sign = $data["sign"];
        $sign_type = $data["sign_type"];
        unset($data["sign"]);
        unset($data["sign_type"]);
        ksort($data);
        foreach ($data as &$value) {
            $value = $this->changeCharset($value, $data["charset"]);
        }
        if ("RSA2" == $sign_type) {
            $result = (openssl_verify($this->signArrayToString($data), base64_decode($sign), $this->getPublicKey(), OPENSSL_ALGO_SHA256) === 1);
        } else {
            $result = (openssl_verify($this->signArrayToString($data), base64_decode($sign), $this->getPublicKey()) === 1);
        }
        return $result;
    }

    /**
     * 构造支付请求表单
     * @param array $form 待构造数组
     * @return string
     */
    private function buildRequestForm(array $form): string
    {
        foreach ($form as &$value) {
            $value = $this->changeCharset($value, $this->charset);
        }
        $html = "<form name='alipay' action='$this->url' method='post' charset='$this->charset'>";
        foreach ($form as $k => $v) {
            $html .= "<input name='$k' value='$v' type='hidden'/>";
        }
        $html .= "<input type='submit' style='display: none'/>";
        $html .= "</form>";
        $html .= "<script>document.forms['alipay'].submit()</script>";
        return $html;
    }

    /**
     * 构造签名内容
     * @return array
     */
    private function buildOriginSignContent(): array
    {
        $origin_sign = [
            "app_id" => $this->app_id,
            "method" => $this->pay_method[$this->type],
            "charset" => $this->charset,
            "sign_type" => $this->sign_type,
            "timestamp" => $this->time_stamp,
            "version" => $this->version,
            "biz_content" => json_encode($this->biz_content),
            "alipay_root_cert_sn" => $this->getRootSecretSN(),
            "app_cert_sn" => $this->getAppSecretSN(),
        ];
        switch ($this->type) {
            case "wap":
            case "pc":
                $origin_sign["return_url"] = $this->return_url;
                $origin_sign["notify_url"] = $this->notify_url;
                break;
            case "face_scan":
            case "face_code":
            case "app":
                $origin_sign["notify_url"] = $this->notify_url;
                break;
            default:
                break;
        }
        ksort($origin_sign);
        return $origin_sign;
    }

    /**
     * 待签名数组转字符串
     * @param array $content 待签名数组
     * @return string
     */
    private function signArrayToString(array $content): string
    {
        $origin_sign = "";
        foreach ($content as $item => $value) {
            if ("" != trim($value)) {
                $origin_sign .= $item . "=" . $value . "&";
            }
        }
        return rtrim($origin_sign, "&");
    }

    /**
     * 签名
     * @param string $content 签名内容
     * @return string
     * @throws ErrorException
     */
    private function sign(string $content): string
    {
        if ($this->private_key_path) {
            $private_key = file_get_contents($this->private_key_path);
        } else {
            $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($this->private_key, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
            ($private_key) or throw new ErrorException("私钥格式错误，请检查");
        }
        if ($this->sign_type == "RSA2") {
            openssl_sign($content, $sign, $private_key, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($content, $sign, $private_key);
        }
        return base64_encode($sign);
    }

    /**
     * 获取支付宝根证书序列号
     * @return string
     */
    private function getRootSecretSN(): string
    {
        $cert = file_get_contents($this->root_secret_path);
        $array = explode("-----END CERTIFICATE-----", $cert);
        $SN = null;
        for ($i = 0; $i < count($array) - 1; $i++) {
            $ssl[$i] = openssl_x509_parse($array[$i] . "-----END CERTIFICATE-----");
            if (str_starts_with($ssl[$i]['serialNumber'], '0x')) {
                $ssl[$i]['serialNumber'] = $this->hexToDec($ssl[$i]['serialNumberHex']);
            }
            if ($ssl[$i]['signatureTypeLN'] == "sha1WithRSAEncryption" || $ssl[$i]['signatureTypeLN'] == "sha256WithRSAEncryption") {
                if ($SN == null) {
                    $SN = md5($this->arrayToString(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                } else {

                    $SN = $SN . "_" . md5($this->arrayToString(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                }
            }
        }
        return $SN;
    }

    /**
     * 获取证书序列号
     * @return string
     */
    private function getAppSecretSN(): string
    {
        $certContent = file_get_contents($this->app_secret_path);
        $ssl = openssl_x509_parse($certContent);
        return md5($this->arrayToString(array_reverse($ssl['issuer'])) . $ssl['serialNumber']);
    }

    private function arrayToString(array $array): string
    {
        $string = [];
        foreach ($array as $key => $value) {
            $string[] = $key . '=' . $value;
        }
        return implode(',', $string);
    }

    private function hexToDec($hex): int|string
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }

    /**
     * 转换字符集
     * @param string $data
     * @return bool|array|string
     */
    private function changeCharset(string $data, $charset): bool|array|string
    {
        if (!empty($data)) {
            if (strcasecmp($charset, "UTF-8") != 0) {
                $data = mb_convert_encoding($data, $charset, "UTF-8");
            }
        }
        return $data;
    }

    /**
     * 发情请求
     * @param array $body 请求体
     * @return bool|string|array
     * @throws ErrorException
     */
    private function request(array $body): bool|string|array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        if(curl_errno($ch)) {
            throw new ErrorException("请求出错");
        }
        curl_close($ch);
        return json_decode($data, true);
    }

    /**
     * 公钥证书中提取公钥
     * @return string
     */
    private function getPublicKey(): string
    {
        $cert = file_get_contents($this->public_key_path);
        $pkey = openssl_pkey_get_public($cert);
        $keyData = openssl_pkey_get_details($pkey);
        $public_key = str_replace('-----BEGIN PUBLIC KEY-----', '', $keyData['key']);
        return trim(str_replace('-----END PUBLIC KEY-----', '', $public_key));
    }

    public function success(): void
    {
        echo "success";
    }

}