<template>
  <div class="app-container">
    <el-collapse v-model="active_collapse">
      <el-collapse-item name="collapse-item-alipay">
        <template slot="title">
          <div>
            <h4>支付宝支付</h4>
          </div>
        </template>
        <template v-if="show_alipay_apply_button">
          <el-button
            type="primary"
            size="small"
            @click="doApplyAlipay"
            @disabled="!ajax"
          >申请开通</el-button>
          <el-divider></el-divider>
        </template>
        <template v-if="show_alipay_reapply_button">
          <el-button
            type="primary"
            size="small"
            @click="doReApplyAlipay"
            @disabled="!ajax"
          >已补充或修改资料，再次申请</el-button>
          <el-divider></el-divider>
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
        <template v-if="show_wxpay_apply_button">
          <el-button
            type="primary"
            size="small"
            @click="doApplyWxpay"
            @disabled="!ajax"
          >申请开通</el-button>
          <el-divider></el-divider>
        </template>
        <template v-if="show_wxpay_reapply_button">
          <el-button
            type="primary"
            size="small"
            @click="doReApplyWxpay"
            @disabled="!ajax"
          >已补充或修改资料，再次申请</el-button>
          <el-divider></el-divider>
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
import {
  getChannelInfo,
  createChannel,
  updateChannel
} from '@/api/information-channel'

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
      show_alipay_apply_button: false,
      show_alipay_reapply_button: false,
      show_wxpay_apply_button: false,
      show_wxpay_reapply_button: false,
      ajax: false,
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
        let alipay_is_apply = response.data.channel.alipay.is_apply
        let alipay_status = response.data.channel.alipay.status
        this.show_alipay_apply_button = !alipay_is_apply
        if(alipay_status == 'third-refuse'){
          this.show_alipay_reapply_button  = true
        }else if(alipay_status == 'refuse'){
          this.show_alipay_reapply_button  = true
        }else{
          this.show_alipay_reapply_button  = false
        }

        this.wxpay_timelines = response.data.channel.wxpay.history
        let wxpay_is_apply = response.data.channel.wxpay.is_apply
        let wxpay_status = response.data.channel.wxpay.status
        this.show_wxpay_apply_button = !wxpay_is_apply
        if(wxpay_status == 'third-refuse'){
          this.show_wxpay_reapply_button  = true
        }else if(wxpay_status == 'refuse'){
          this.show_wxpay_reapply_button  = true
        }else{
          this.show_wxpay_reapply_button  = false
        }
      }).catch(err => {
        console.log(err)
      })
    },
    doApplyAlipay() {
      this.$confirm(
        "申请开通前，请确认您已填写完商户资料信息。",
        "你确定要开通支付宝支付吗？",
        {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'info'
        }
      ).then(()=>{
        this.ajax = true
        createChannel({channel: 'alipay'}).then(response => {
          this.$message({
            message: response.message,
            showClose: true
          })
          this.ajax = false
        }).catch(err => {
          this.ajax = false
        })
      }).catch(()=>{

      });
    },
    doReApplyAlipay() {
      this.$confirm(
        "再次申请开通前，请确认您已经按照要求补全或修改商户资料信息。",
        "你确定要重新申请开通支付宝支付吗？",
        {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'info'
        }
      ).then(()=>{
        this.ajax = true
        updateChannel({channel: 'alipay'}).then(response => {
          this.$message({
            message: response.message,
            showClose: true
          })
          this.ajax = false
        }).catch(err => {
          this.ajax = false
        })
      }).catch(()=>{

      });
    },
    doApplyWxpay() {
      this.$confirm(
        "申请开通前，请确认您已填写完商户资料信息。",
        "你确定要开通微信支付吗？",
        {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'info'
        }
      ).then(()=>{
        this.ajax = true
        createChannel({channel: 'wxpay'}).then(response => {
          this.$message({
            message: response.message,
            showClose: true
          })
          this.ajax = false
        }).catch(err => {
          this.ajax = false
        })
      }).catch(()=>{});
    },
    doReApplyWxpay() {
      this.$confirm(
        "再次申请开通前，请确认您已经按照要求补全或修改商户资料信息。",
        "你确定要重新申请开通微信支付吗？",
        {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'info'
        }
      ).then(()=>{
        this.ajax = true
        updateChannel({channel: 'wxpay'}).then(response => {
          this.$message({
            message: response.message,
            showClose: true
          })
          this.ajax = false
        }).catch(err => {
          this.ajax = false
        })
      }).catch(()=>{});
    }
  }
}
</script>
