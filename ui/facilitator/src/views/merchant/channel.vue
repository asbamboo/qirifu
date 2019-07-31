<template>
  <div class="app-container">
    <el-alert type="success" :closable="false">
      商户简称: {{ merchant.name }}
    </el-alert>
    <el-collapse v-model="active_collapse">
      <el-collapse-item name="collapse-item-alipay">
        <template slot="title">
          <div>
            <h4>支付宝支付</h4>
          </div>
        </template>
        <div v-if="show_alipay_dialog">
          <alipay-dialog></alipay-dialog>
          <el-divider></el-divider>
        </div>
        <el-timeline>
          <el-timeline-item
            v-for="(item, index) in alipay_timelines"
            :key="index"
            :timestamp="item.time"
            placement="top"
            type="primary"
          >
            <h4>{{item.status}}</h4>
            <p>{{item.desc}}</p>
          </el-timeline-item>
        </el-timeline>
      </el-collapse-item>

      <el-collapse-item name="collapse-item-wxpay">
        <template slot="title">
          <h4>微信支付</h4>
        </template>
        <div v-if="show_wxpay_dialog">
          <wxpay-dialog></wxpay-dialog>
          <el-divider></el-divider>
        </div>
        <el-timeline>
          <el-timeline-item
            v-for="(item, index) in wxpay_timelines"
            :key="index"
            :timestamp="item.time"
            placement="top"
            type="primary"
          >
            <h4>{{item.status}}</h4>
            <p>{{item.desc}}</p>
          </el-timeline-item>
        </el-timeline>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
import { fetchChannelInfo } from '@/api/merchant'
import AlipayDialog from './channel/alipay-dialog'
import WxpayDialog from './channel/wxpay-dialog'

const alipay_timelines = [{
  // time: new Date(),
  // status: '状态'
}]

const merchant = {
  name: ''
}
const wxpay_timelines = alipay_timelines

export default {
  name: 'MerchantChannel',
  components: { AlipayDialog, WxpayDialog },
  data() {
    return {
      show_alipay_dialog: false,
      alipay_timelines: Object.assign({}, alipay_timelines),
      show_wxpay_dialog: false,
      wxpay_timelines: Object.assign({}, wxpay_timelines),
      merchant: merchant,
      active_collapse: ['collapse-item-alipay', 'collapse-item-wxpay']
    }
  },
  created() {
    this.fetchData(this.$route.params.seq)
  },
  methods: {
    fetchData(seq) {
      fetchChannelInfo(seq).then(response => {

        this.alipay_timelines = response.data.channel.alipay.history
        let alipay_is_ok = response.data.channel.alipay.is_ok
        let alipay_is_apply = response.data.channel.alipay.is_apply
        this.show_alipay_dialog  = !alipay_is_ok && alipay_is_apply

        this.wxpay_timelines = response.data.channel.wxpay.history
        let wxpay_is_ok = response.data.channel.wxpay.is_ok
        let wxpay_is_apply = response.data.channel.wxpay.is_apply
        this.show_wxpay_dialog  = !wxpay_is_ok && wxpay_is_apply

        this.merchant = response.data.merchant

      }).catch(err => {
        console.log(err)
      })
    }
  }
}
</script>
