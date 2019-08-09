{% extends 'qrcode/_trade.html.tpl' %}

{% block pay_script %}
	{% if onecd_pay_json %}
		<script type="text/javascript">
		function onBridgeReady(){

			WeixinJSBridge.invoke(
				'getBrandWCPayRequest', 
				{
					"appId": "{{ onecd_pay_json.appId }}",     //公众号名称，由商户传入     
					"timeStamp": "{{ onecd_pay_json.timeStamp }}",         //时间戳，自1970年以来的秒数     
					"nonceStr": "{{ onecd_pay_json.nonceStr }}", //随机串     
					"package": "{{ onecd_pay_json.package }}",     
					"signType": "{{ onecd_pay_json.signType }}",         //微信签名方式：     
					"paySign": "{{ onecd_pay_json.paySign }}" //微信签名 
				},
				function(res){
					if(res.err_msg == "get_brand_wcpay_request:ok" ){
						// 使用以上方式判断前端返回,微信团队郑重提示：
						//res.err_msg将在用户支付成功后返回ok，但并不保证它绝对可靠。
						document.write('支付成功');
					}
				}
			);
		}
		
		if (typeof WeixinJSBridge == "undefined"){
		   if( document.addEventListener ){
		       document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
		   }else if (document.attachEvent){
		       document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
		       document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
		   }
		}else{
		   onBridgeReady();
		}
		</script>
	{% endif %}
{% endblock %}

{% block authinfo %}
	<input type="hidden" name="auth_info[openid]" value="{{ auth_info.openid|default('') }}" />
{% endblock %}

