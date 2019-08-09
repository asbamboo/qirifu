{% apply spaceless %}
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
{% block pay_script %}
{% endblock %}
<form role="form" method="post">
{% block authinfo %}
{% endblock %}
	<div class="title">
		<h3>
			<img src="../../images/cashier_icon.jpg"/>
			{{ merchant_name }}
		</h3>
	</div>
	<div class="trade-price">
		<input type="text" value="{{ app.request.getRequestParam('trade_price') }}" autocomplete="off" placeholder="请输入支付金额" name="trade_price" id="tradePrice" required pattern="[\d\.]+" readonly="readonly" />	
	</div>
	<div class="pay-button">
		<button>立即付款</button>
	</div>
</form>
<script>
	;(function(exports){
	  var KeyBoard = function(input, options){
	    var body = document.getElementsByTagName('body')[0];
	    var DIV_ID = options && options.divId || '__w_l_h_v_c_z_e_r_o_divid';
	     
	    if(document.getElementById(DIV_ID)){
	      body.removeChild(document.getElementById(DIV_ID));
	    }
	     
	    this.input = input;
	    this.el = document.createElement('div');
	     
	    var self = this;
	    var zIndex = options && options.zIndex || 1000;
	    var width = options && options.width || '100%';
	    var height = options && options.height || '250px';
	    var fontSize = options && options.fontSize || '15px';
	    var backgroundColor = options && options.backgroundColor || '#fff';
	    var TABLE_ID = options && options.table_id || 'table_0909099';
	    var mobile = typeof orientation !== 'undefined';
	     
	    this.el.id = DIV_ID;
	    this.el.style.position = 'absolute';
	    this.el.style.left = 0;
	    this.el.style.right = 0;
	    this.el.style.bottom = 0;
	    this.el.style.zIndex = zIndex;
	    this.el.style.width = width;
	    this.el.style.height = height;
	    this.el.style.backgroundColor = backgroundColor;
	     
	    //样式
	    var cssStr = '<style type="text/css">';
	    cssStr += '#' + TABLE_ID + '{text-align:center;width:100%;height:230px;border-top:1px solid #CECDCE;background-color:#FFF;}';
	    cssStr += '#' + TABLE_ID + ' td{width:33%;border:1px solid #ddd;border-right:0;border-top:0;}';
	    if(!mobile){
	      cssStr += '#' + TABLE_ID + ' td:hover{background-color:#1FB9FF;color:#FFF;}';
	    }
	    cssStr += '</style>';
	     
	    //Button
	    var btnStr = '';
	    //btnStr += '<div style="width:60px;height:28px;background-color:#1FB9FF;';
	    //btnStr += 'float:right;margin-right:5px;text-align:center;color:#fff;';
	    //btnStr += 'line-height:28px;border-radius:3px;margin-bottom:5px;cursor:pointer;">完成</div>';
	     
	    //table
	    var tableStr = '<table id="' + TABLE_ID + '" border="0" cellspacing="0" cellpadding="0">';
	      tableStr += '<tr><td>1</td><td>2</td><td>3</td></tr>';
	      tableStr += '<tr><td>4</td><td>5</td><td>6</td></tr>';
	      tableStr += '<tr><td>7</td><td>8</td><td>9</td></tr>';
	      tableStr += '<tr><td style="background-color:#D3D9DF;">.</td><td>0</td>';
	      tableStr += '<td style="background-color:#D3D9DF;">删除</td></tr>';
	      tableStr += '</table>';
	    this.el.innerHTML = cssStr + btnStr + tableStr;
	     
	    function addEvent(e){
	      var ev = e || window.event;
	      var clickEl = ev.element || ev.target;
	      var value = clickEl.textContent || clickEl.innerText;
	      if(clickEl.tagName.toLocaleLowerCase() === 'td' && value !== "删除"){
	        if(self.input){
	          self.input.value += value;
	        }
	      }else if(clickEl.tagName.toLocaleLowerCase() === 'div' && value === "完成"){
	        body.removeChild(self.el);
	      }else if(clickEl.tagName.toLocaleLowerCase() === 'td' && value === "删除"){
	        var num = self.input.value;
	        if(num){
	          var newNum = num.substr(0, num.length - 1);
	          self.input.value = newNum;
	        }
	      }
	    }
	     
	    if(mobile){
	      this.el.ontouchstart = addEvent;
	    }else{
	      this.el.onclick = addEvent;
	    }
	    body.appendChild(this.el);
	  }
	   
	  exports.KeyBoard = KeyBoard;
	 
	})(window);
	new KeyBoard(document.getElementById('tradePrice'));
	document.getElementById('tradePrice').onclick= false;
</script>
</body>
</html>
{% endapply %}