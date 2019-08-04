<template>
  <div class="app-container">
    <el-form label-width="100px;" v-if="is_supported">
      <div class="title-container">
        <h3 class="title">自助付款</h3>
      </div>

      <el-form-item>
        <el-input
          placeholder="请输入支付金额"
          v-model="form.trade_price"
          name="trade_price"
          type="number"
        />
      </el-form-item>
      <el-form-item>
        <el-button
          type="primary"
          style="width:100%;"
          @click.native.prevent="doPay"
        >付款</el-button>
      </el-form-item>
    </el-form>
    <p v-else>
      对不起，系统暂不支持您选择的支付方式
    </p>
  </div>
</template>

<script>
import { order, getAuthUrl, getAuthInfo } from '@/api/trade'
import qs from 'qs'

const from = {
  trade_price: '',
  pay_type: undefined,
  user_id: undefined,
  auth_info: undefined
}

export default {
  name: 'TradeOrder',
  data() {
    let is_supported = false
    let ua = window.navigator.userAgent.toLowerCase()
    if ( ua.match(/MicroMessenger/i) == 'micromessenger' ) {
      from.pay_type = 'wxpay'
      is_supported = true
    }else if( ua.match(/AlipayClient/i) == 'alipayclient' ) {
      from.pay_type = 'alipay'
      is_supported = true
    }

    from.user_id  = this.$route.params.user_id

    return {
      form: Object.assign({}, from),
      is_supported: is_supported
    }
  },
  created() {
    if(this.is_supported) {
      this.doAuth()
    }
  },
  methods: {
    doAuth() {
      console.log(this.$route.query)

      let has_auth_code = false

      if(this.form.pay_type == 'alipay' && location.href.search('auth_code') > 0) {
        has_auth_code = true
      }

      if(this.form.pay_type == 'wxpay' && location.href.search('code') > 0) {
        has_auth_code = true
      }

      console.log(has_auth_code, this.form.pay_type, location.href.search('auth_code'))

      if(!has_auth_code){
        getAuthUrl({
          pay_type: this.form.pay_type,
          redirect_url: location.href
        }).then(response => {
          location.href = response.data.auth_url
        }).catch(err => {
          console.log(err)
        })
      }else{
        let post_data = this.$route.query
        if(this.form.pay_type == 'wxpay'){
          post_data = qs.parse(location.search.slice(1))
        }
        post_data.type = this.form.pay_type
        console.log(post_data)
        getAuthInfo(post_data).then(response => {
          this.form.auth_info  =  Object.assign({}, response.data)
        }).catch(err => {
          console.log(err)
        })
      }
    },
    doRequestAlipay(trade_no) {
      AlipayJSBridge.call("tradePay", {
           tradeNO: trade_no
      }, function (data) {
          console.log(data)
          if ("9000" == data.resultCode) {
              this.$message({
                message: 支付成功,
                showClose: true
              })
          }
      });
    },
    doPay() {
      order(this.form).then(response => {
        // if(this.form.pay_type == 'alipay'){
          if (window.AlipayJSBridge) {
            this.doRequestAlipay(response.data.onecd_pay_json.trade_no)
          } else {
            document.addEventListener('AlipayJSBridgeReady', function(){
              this.doRequestAlipay(response.data.onecd_pay_json.trade_no)
            }, false);
          }
        // }
      }).catch(err => {
        console.log(err)
      })
    }
  }
}
</script>
