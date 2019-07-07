<template>
  <div class="app-container">
    <el-collapse v-model="active_collapse">
      <el-collapse-item name="collapse-item-alipay">
        <template slot="title">
          <div>
            <h4>支付宝支付</h4>
          </div>
        </template>
        <el-timeline>
          <el-timeline-item
            v-for="(item, index) in alipay_timelines"
            :key="index"
            :timestamp="item.time"
            type="primary"
          >{{item.status}}</el-timeline-item>
        </el-timeline>
      </el-collapse-item>

      <el-collapse-item name="collapse-item-wxpay">
        <template slot="title">
          <h4>微信支付</h4>
        </template>
        <el-timeline>
          <el-timeline-item
            v-for="(item, index) in wxpay_timelines"
            :key="index"
            :timestamp="item.time"
            type="primary"
          >{{item.status}}</el-timeline-item>
        </el-timeline>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
import { getChannelInfo } from '@/api/information-channel'

const alipay_timelines = [{
  // time: new Date(),
  // status: '状态'
}]

const wxpay_timelines = alipay_timelines

export default {
  name: 'InformationChannel',
  data() {
    return {
      alipay_timelines: Object.assign({}, alipay_timelines),
      wxpay_timelines: Object.assign({}, wxpay_timelines),
      active_collapse: ['collapse-item-alipay', 'collapse-item-wxpay']
    }
  },
  created() {
    this.fetchData(this.$route.params.seq)
  },
  methods: {
    fetchData() {
      getChannelInfo().then(response => {

        this.alipay_timelines = response.data.channel.alipay.history

        this.wxpay_timelines = response.data.channel.wxpay.history

      }).catch(err => {
        console.log(err)
      })
    }
  }
}
</script>
