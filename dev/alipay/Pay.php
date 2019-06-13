<?php
/**
 * 2018-03-05
* 一码多付方案
* by 我是个导演
* 欢迎访问支付宝开发者社区：https://openclub.alipay.com/index.php
*/
//第一步拼接授权链接
//沙箱授权链接拼接：https://openauth.alipaydev.com/oauth2/publicAppAuthorize.htm?app_id=填写您的appid&scope=auth_base&redirect_uri=填写您的授权回调链接
//正式环境授权链接拼接：https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=填写您的appid&scope=auth_base&redirect_uri=填写您的授权回调链接
require_once 'AopSdk.php';
$aop = new AopClient();
//沙箱网关
$aop->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';
//正式环境网关
// $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '2016090900468991';
$aop->rsaPrivateKey = 'MIIEogIBAAKCAQEArOjvYgZqOLJa6uTl2qg5v27ejiT+L3gvsGJkfxvKOkHlTR58FG6ptWZSKJ3HQT035mf2NH70IeRgKcHZU83PTV59wbvkmZXlRGcEQNvAD41ipa24V5CBQIb9rvbFpljE/3XiUYBPdU6GFLYJ9ILauhp1JcMsSj2ZiC3Rnf7MFtBBaAK/0ZXQ3uMPFQBI5TgUUSH4BmR7DWPVkkMUw48Rd5P8Hf4Cgkboi7K3MJCXmK7eNLW8KPkIPwCJ9iBZepwclJpL8l/O/+vsvcQb6Ak+PlCbhf2s5GWnAbFXwG/pNkBcXmTVMRtRix4qP07vHF597NEC3EKAUNPO/07jVQtuPwIDAQABAoIBAFM0sGU6cwkfgrLAPX/QLiHZx00drhfHvSMi5ftosxL/vMk0nz7x1cbOG0EiU80oGWtNoeO3J+HbVQ8jEmLKijRQATImKaZixHX7IMfZN34EanERvMCecCROEWuNoqUe2IWaA7N2jEYF6G20/+tiwMCHlNH8Lqb3J5epfNKL10guWnczcsU0VTEerJuCjOfvn9wcswLV1L/Q5KZsUFeBco9ZsxwUMoZDJTjzPtr9Bh3b8POUftLh7trRaqs6Hl9ODEQqDmj9sY0nkUKE/IOLq6W3sKsxopdgG0h6fPSFO3g5Q9TpW9M/LjmMBXrWttetiiow3h1EmqSvoXHgKVKrugECgYEA2rNhzBYdxPZh/jUERoJlC+ZJwcjtPFYJQa2MEDDG7pOQh9x6XkLRHC54g76o4/71FDvBSYyXJ5ntZu9kcJJ4ob9iXPS3j9Wy7BRYJ1V82VN3vBy5gy34dMmOvV6KI89nmBs9fS7QFvlHQ+sWLKUc5ukj2sJ7YLa+nwtdWrCYoT8CgYEAymZLyLZvbkFsfYqvWlWrdkPLjDMVua6ljgexe9H2K3jzSd5O8kLOpeDptbx5E3wYuVwlf4fhL4QXYuvwpvDiw0uuktMvlaZDsrFoQvuSCtnjFuUYcbCgAYzoVR272kjEAHOnZhA/agCNJPTMrapvAiBUqnUsqlOlOvr8CT+c8wECgYBfS9zofhaYtbd7eoSTaMw5BC7Ndw0QxnigGsw2XdrBKKHI4aScoEbYHdO7rH+RYY5PM76EmvNeeS8+NEAy4VAZQvjMgoHd9gNR5YeIFDMjy8AXwvh/Fa/2y8Eb+S7+bai5Wd1QR+66pAKOsMhnycavQ5WxBB+Yw+iKCvyxwNOxJwKBgBvNFVfKh1/TqL1N0WxNHDIwecMWOUO6E4+AywZA/tlV0cUxvhJUzuHGFRQbgV4Fy7eaSqRL28iVEklKM1IbAGrm+GG+lGiv39ipubKJr7PB3gmFCmiDZFcSLc+TN/xxxkpxmGrSjINY9ApBfePU506Zdke7tJjoyGdjlj8G994BAoGAWtSPVjZhuncUOQhFtDE3nA7N9UTGv71hwn2R2JNeJ3ZQMajoRZztFmW9Z7DdaLHcytUvhlxjfShr06l4jAYww7W4U9OcRu+f548i38Quy9RXB/2NbH40mJiaFzHEm/b0x+unXbtUb6hq2JFwqVYnTR5JNsPx0paQ5EnJK8soeLc=';
$aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyhs2HUdxQJlzhGcxkM6hHsI5ej1Jbls/NFZshtU9ZhaArNzx/YFpIcmkfOwSmBtjMblKC9sUtWbw63BiX024csr78vSjjJZ4DyDVEiT6q/0By+PoHmnxnEj4uDa4B4u1S5XcA76UyjUeXgv/3lws1Pri78minn8PA0tun6ZAJF3nx4yc2fj1QjCvQ9rHp/GVbfrw7UBoFgfY4odnyUse+Q0iOdfL4AyNj5lCWmGC0Zl2tKwezE20yWpdrhEcpoAMLgwEm7+xwXE1QTzaytmrK0Hk1DhNTAwSU3ejgANcVM9coArpsOi+XasXCrMgymxSUfWLrXmwHPWFdun/18mrjQIDAQAB';
$aop->apiVersion = '1.0';
$aop->postCharset='utf-8';
$aop->format='json';
$aop->signType = 'RSA2';
//生成随机订单号
$date=date("YmdHis");
$arr=range(1000,9999);
shuffle($arr);
//请求的必传信息
$auth_code = $_GET['auth_code'];
$request = new AlipaySystemOauthTokenRequest();
$request->setGrantType("authorization_code");
$request->setCode($auth_code);
$result = $aop->execute($request);
$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
//输出用户pid
$user_id = $result->$responseNode->user_id;

//调用创建接口
$request1 = new AlipayTradeCreateRequest ();
$request1->setBizContent("{\"out_trade_no\":\"".$date.$arr[0]."\",\"subject\":\"一码多付\",\"buyer_id\":\"".$user_id."\",\"total_amount\":\"0.01\"}");
$CreateResult = $aop->execute ($request1);
$responseNode1 = str_replace(".", "_", $request1->getApiMethodName()) . "_response";
//输出交易号
$trade_no = $CreateResult->$responseNode1->trade_no;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>js调起收银台</title>
</head>
<body>
<button id="payButton" onclick="tradePay('<?=$trade_no?>')">去支付</button>
<p id="result">result: </p>

<script src="https://a.alipayobjects.com/g/h5-lib/alipayjsapi/3.0.6/alipayjsapi.min.js"></script>
<script src="https://static.alipay.com/aliBridge/1.0.0/aliBridge.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="https://as.alipayobjects.com/g/component/antbridge/1.1.1/antbridge.min.js"></script>

<script type="application/javascript">
    // 调试时可以通过在页面定义一个元素，打印信息，使用alert方法不够优雅
    function log(obj) {
        $("#result").append(obj).append(" ").append("<br />");
    }

    $(document).ready(function(){
        // 页面载入完成后即唤起收银台
        // 此处${tradeNO}为模板语言语法，实际调用样例类似为tradePpay("2016072621001004200000000752")
       /*  tradePay("${tradeNO}");

        // 点击payButton按钮后唤起收银台
        $("#payButton").click(function() {
           tradePay("${tradeNO}");
        });
 */
        // 通过jsapi关闭当前窗口，仅供参考，更多jsapi请访问
        // /aod/54/104510
        $("#closeButton").click(function() {
           AlipayJSBridge.call('closeWebview');
        });
     });

    // 由于js的载入是异步的，所以可以通过该方法，当AlipayJSBridgeReady事件发生后，再执行callback方法
    function ready(callback) {
         if (window.AlipayJSBridge) {
             callback && callback();
         } else {
             document.addEventListener('AlipayJSBridgeReady', callback, false);
         }
    }

    function tradePay(tradeNO) {
        ready(function(){
             // 通过传入交易号唤起快捷调用方式(注意tradeNO大小写严格)
             AlipayJSBridge.call("tradePay", {
                  tradeNO: tradeNO
             }, function (data) {
                 log(JSON.stringify(data));
                 if ("9000" == data.resultCode) {
                     log("支付成功");
                 }
             });
        });
    }
</script>
</body>
</html>