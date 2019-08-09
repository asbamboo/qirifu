{% extends 'qrcode/_trade.html.tpl' %}

{% block pay_script %}
	{% if onecd_pay_json and onecd_pay_json.trade_no %}
		<script>
		var doRequestAlipay = function(trade_no) {
		  AlipayJSBridge.call("tradePay", {
		       tradeNO: trade_no
		  }, function (data) {
		      console.log(data)
		      if ("9000" == data.resultCode) {
	              document.write('支付成功')
		      }
		  });
		}
		
		window.onload = function(){
			if (window.AlipayJSBridge) {
				doRequestAlipay('{{ onecd_pay_json.trade_no }}')
			} else {
				document.addEventListener('AlipayJSBridgeReady', function(){
					doRequestAlipay('{{ onecd_pay_json.trade_no }}')
				}, false);
			}
		}
		</script>
	{% endif %}
{% endblock %}
