<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
<meta name=renderer content="webkit">
<meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<title>{{ merchant_name }} - {{ system_name }}</title>
<style>
html, body {
	line-height: 1.5em;
	-webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
    font-family: Helvetica Neue, Helvetica, PingFang SC, Hiragino Sans GB, Microsoft YaHei, Arial, sans-serif;
}


.title {
	height: 2em;
}

.title h3 {
	height: 100%;
	display: block;
	font-size: 24px;
	-webkit-margin-before: 1em;
	-webkit-margin-after: 1em;
	-webkit-margin-start: 0px;
	-webkit-margin-end: 0px;
	font-weight: bold;
}

.title img {
	height: 100%;
}

.trade-price input {
	margin-top: 1em;
    -webkit-appearance: none;
    background-color: #FFFFFF;
    background-image: none;
    border-radius: 4px;
    border: 1px solid #DCDFE6;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    color: #606266;
    display: inline-block;
    font-size: inherit;
    height: 40px;
    line-height: 40px;
    outline: none;
    padding: 0 15px;
    -webkit-transition: border-color 0.2s cubic-bezier(0.645, 0.045, 0.355, 1);
    transition: border-color 0.2s cubic-bezier(0.645, 0.045, 0.355, 1);
    width: 100%;
}

.trade-price input:focus {
	border: 1px solid #1890ff;
}

.pay-button {
	margin-top: 1em;
	text-align:center;
}

.pay-button button {
    display: inline-block;
    line-height: 1;
    white-space: nowrap;
    cursor: pointer;
    border: 1px solid #DCDFE6;
    color: #606266;
    -webkit-appearance: none;
    text-align: center;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    outline: none;
    margin: 0;
    -webkit-transition: .1s;
    transition: .1s;
    font-weight: 400;
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
    padding: 12px 20px;
    font-size: 14px;
    border-radius: 4px;
	color: #FFFFFF;
    background-color: #1890ff;
    border-color: #1890ff;
	width: 100%;
}
.pay-button button:active {
	background: #1870ff;
	position:relative;
	left: 0.1em;
	top: 0.1em;
}
</style>
</head>
<body>
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
{% else %}
	<form role="form" method="post">
		<div class="title">
			<h3>
				<img src="../../images/cashier_icon.jpg"/>
				{{ merchant_name }}
			</h3>
		</div>
		<div class="trade-price">
			<input type="number" autocomplete="off" placeholder="请输入支付金额" name="trade_price" pattern="\d*" autofocus="autofocus" />	
		</div>
		<div class="pay-button">
			<button>立即付款</button>
		</div>
	</form>
{% endif %}
</body>
</html>