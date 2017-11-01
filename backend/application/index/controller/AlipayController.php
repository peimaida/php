<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Loader;


class AlipayController extends Controller{
    private $config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016081600255645",
		
		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEogIBAAKCAQEA0LOtz7mwwKxrGVIx/y7PYMIs2h+f4E5BaYshogMw+TzSjY8KybxfL9/3SrjmkAUVSblaQK/g6hhu8PHYObsZqZ7ohH2g53fiIN0UAUrd55cwgZgMXHlwFtbGplhr6q6z6Y6XiSOnkGATRsNbt+ZaYmmyRapUP+mOkFSmy/Re2DpOUOW0j4xcCgGhg9pifVM1gaGrqX9lp8v2GFTH2/MHbPW7KZ/aQlFOJM7DsOjECDAqouYtbrch7dePsxsLSWgg4bDBanIbJV70ZX2Jl0hJbBeHNF63U0rTYzt/kx4883paE0GjsV1feQbr05QmkdWxOzWKHamFDTk+adAypcaD4QIDAQABAoIBAF7G2k4VY+F7638qRq4+Ucr9I2VDK1Wv9CC8IF/01w64wl2q8hk/RHL3YNQ8N+h7hnlehVAPDzMAOZGOIsXE5BiVo75XVvUHClgmTelwWGnNzSdtJ1/vfinBC6GLUibXg7izGroayQPVvatLGKHhKHa8zUq52VzU8fs1ljHVHiVPMrZy6nxvXE301NDtNJzhXL9KffmsxO6Ss/SfbyhFFrHW9uw+d+bSXxZqUr+10scyId8VrJ1Z0F2figOwFlKz7QFZEOZZ+/IVpaiHP+uauo5tyiSbQqXwC6MVvkLBssZrF0Wcn41/bApSy3lhinM/zNan+FXU4QMLwPSBFNXs1CkCgYEA+Jpm6nYdN2RlN6reU32wfEF8D4bZG1tZ+w3jRZ4HXqVvuzVuOXga3nHNCZ1XHvt16mmxwillf8ZBi93ygyOQh+Th+l+NYIGKE4Q4PKn9iI3Ldxck7slnVaT3qd+8DvRO4x8Y6L5+A08T5e9hH98ysB+UhjQ2z97wPne49SqKeesCgYEA1ulZ2KQ/ZeeBp+srC1nMK1Gqj+cIxeREITQC7cgCERL2UAYtJ22FDjPHAKS5YTnlKd2465SJIhlpUwZ+/qdLMCP+8bYb2GsDWKn41CLkND1pC5qBD/kVc+kWxKXp7whBExD1RAFQk/BLXOdFG63IhgzGlBNynU2xBvZbWmQmmmMCgYAWI0m+3z3CzQHmbyTVMoAg0IQHre5vbTcaECaI8IWffAPzG9Lw8y0RWfj0Pjqf26yobzkRHTaYpkL3/Y+29dfNAijNbuzcBy0Qh9mqLUkSe+3+cOUtDmpRShtz2SSaAE92EjLZpvz5tnUDKMnxWKLe5DEJsmSfJi8moOqrilpCeQKBgA12FluchiAS7SsgbtTKLiC0f9N9rC4BC0dtI5XTRlXdczI2ANQMZx4pnhhrOPVfE/yYV8Hhzukk4FUD/iZjQjFkv85SEtJueYovM1fOS679/bttVRI0DuTwv61XmxyOzsyr7kDxOCPcFExgbBK8wueqmzGhOjeiKyl9euFhjo1zAoGALM0Ntv4AutxjuEwF+v1/qTBUpxdSfB5+1c6eeb/8FwsiJ/HwZoUlf+8Q4Rk4AtM9IG+HufAnqLT+Ee/w0hSq3OihP5GzjLC0V+lDBtkqjHc1mXl0jHLTY5x/aOMhQ5yCASiWqPvAZRNg4TN4hXp5uvaMY7UwaBVikWPIHj43V8Q=",
		
		//异步通知地址，对应后台里的应用网关
		//'notify_url' => "http://工程公网访问地址/alipay.trade.wap.pay-PHP-UTF-8/notify_url.php",
		'notify_url' => "",

		//同步跳转，对应后台里的授权回调地址
		'return_url' => "http://mitsein.com/alipay.trade.wap.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		//'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2oy3TjbeNtxcjaw7J9aAwDK2EJ7rlQ0mDij85DcoTjVbGn9j3AH5DKulVbF2NhoFGa7iUYXbTtPm5u4OYlkK83l8CYwFIQp8Yh2ySQvEBrES0MQkbE46ktZx8AR/oQrOy2oc1fct0VXW1RSkAJS2qMTJN94mAqeQb7mtzyx1eejgwjtfCX4C3pE2FOttEKWE8vu0imckp7sromPzKHKw3AZHV4DKV2MOWKKKloCmpRihhHblZVkCYC7bAFta2MdTY0db/wm2JQOJE6Zp7yYVVqqv2P1p6D0SdJtHtL7lhk38fkRpOA7G2S+HXvUTEDmpNljDKcw4ECBMZzVEIWEk+QIDAQAB",
    );


	public function index(){
		return $this->fetch();
	}

	public function pay(){
		return $this->fetch();
	}

	public function dopay(){
		header("Content-type: text/html; charset=utf-8");
		Loader::import('alipay.wappay.service.AlipayTradeService',EXTEND_PATH);
		Loader::import('alipay.wappay.buildermodel.AlipayTradeWapPayContentBuilder',EXTEND_PATH);
	
		if (!empty($_POST['WIDout_trade_no'])&& trim($_POST['WIDout_trade_no'])!=""){
		    //商户订单号，商户网站订单系统中唯一订单号，必填
		    $out_trade_no = $_POST['WIDout_trade_no'];

		    //订单名称，必填
		    $subject = $_POST['WIDsubject'];

		    //付款金额，必填
		    $total_amount = $_POST['WIDtotal_amount'];

		    //商品描述，可空
		    $body = $_POST['WIDbody'];

		    //超时时间
		    $timeout_express="1m";

		    $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
		    $payRequestBuilder->setBody($body);
		    $payRequestBuilder->setSubject($subject);
		    $payRequestBuilder->setOutTradeNo($out_trade_no);
		    $payRequestBuilder->setTotalAmount($total_amount);
		    $payRequestBuilder->setTimeExpress($timeout_express);

		    $payResponse = new \AlipayTradeService($this->config);
		    //echo '<pre>';print_r($payResponse);die;
		    $result=$payResponse->wapPay($payRequestBuilder,$this->config['return_url'],$this->config['notify_url']);
		    return ;
		}

	}

	public function query(){
		return $this->fetch();
	}

	public function doquery(){
		header("Content-type: text/html; charset=utf-8");
		Loader::import('alipay.wappay.service.AlipayTradeService',EXTEND_PATH);
		Loader::import('alipay.wappay.buildermodel.AlipayTradeQueryContentBuilder',EXTEND_PATH);
		if (!empty($_POST['WIDout_trade_no']) || !empty($_POST['WIDtrade_no'])){
		    //商户订单号和支付宝交易号不能同时为空。 trade_no、  out_trade_no如果同时存在优先取trade_no
		    //商户订单号，和支付宝交易号二选一
		    $out_trade_no = trim($_POST['WIDout_trade_no']);

		    //支付宝交易号，和商户订单号二选一
		    $trade_no = trim($_POST['WIDtrade_no']);

		    $RequestBuilder = new \AlipayTradeQueryContentBuilder();
		    $RequestBuilder->setTradeNo($trade_no);
		    $RequestBuilder->setOutTradeNo($out_trade_no);

		    $Response = new \AlipayTradeService($this->config);
		    $result=$Response->Query($RequestBuilder);
		    return ;
		}
	}
}