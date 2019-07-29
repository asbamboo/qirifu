<template>
  <div>
    <el-link type="primary" @click="doSendToWxpay()">将资料提交到微信支付</el-link>
    <el-button
      type="primary"
      size="small"
      @click="openDialog()"
    >提交新的操作记录</el-button>
    <el-dialog title="提交新的操作记录" :visible.sync="open_dialog">
      <el-form :model="post_form">
        <el-form-item label="状态">
          <el-select v-model="post_form.status" @change="doChangeStatus">
            <el-option
              v-for="item in available_status"
              v-if="item.status != 'no-apply' && item.status != 'apply-ing'"
              :label="item.label"
              :value="item.status"
            ></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="微信支付商户号" v-if="post_form.status=='ok'">
          <el-input
            v-model="post_form.sub_mch_id"
            autocomplete="off"
          ></el-input>
        </el-form-item>
        <el-form-item label="说明">
          <Tinymce ref="editor" v-model="post_form.desc" :height="150" />
        </el-form-item>
        <el-form-item label="是否通知商户联系人">
          <el-checkbox-group v-model="post_form.notifys">
            <el-checkbox label="email">邮件</el-checkbox>
            <el-checkbox label="sms">短信</el-checkbox>
          </el-checkbox-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="open_dialog = false">取 消</el-button>
        <el-button type="primary" @click="doSubmit()" :disabled="ajax">
          确 定
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {
  fetchWxpayAvailableStatus,
  createWxpayHistory
} from '@/api/merchant-channel'

import Tinymce from '@/components/Tinymce'

const default_post_form = {
  status: null,
  sub_mch_id: undefined,
  desc: undefined,
  notifys: ['email']
}

export default {
  name: 'MerchantChannelWxpayDialog',
  components: { Tinymce },
  data() {
    return {
      open_dialog: false,
      post_form: Object.assign({}, default_post_form),
      ajax: false,
      available_status: []
    }
  },
  created() {
    this.getAvailableStatus()
  },
  methods: {
    getAvailableStatus() {
      fetchWxpayAvailableStatus().then( response => {
        this.available_status = response.data.items
      }).catch(err => {
        console.log(err)
      })
    },
    doSendToWxpay() {
      window.open('https://pay.weixin.qq.com', '_blank')
      this.open_dialog = true
      this.post_form = Object.assign({}, default_post_form)
      this.post_form.status = 'send-wxpay'
    },
    openDialog() {
      this.post_form = Object.assign({}, default_post_form)
      this.open_dialog = true
    },
    doChangeStatus(selected) {
      for( let i in this.available_status ){
        if(this.available_status[i].status == selected) {
          this.post_form.desc = this.available_status[i].desc
          this.$refs.editor.setContent(this.post_form.desc)
        }
      }
    },
    doSubmit() {
      if(this.ajax == true) {
        return
      }
      this.ajax = true
      let post_data = this.post_form
      post_data.seq = this.$route.params.seq
      createWxpayHistory(post_data).then(response => {
        this.open_dialog = false
        this.$message({
          message: response.message,
          showClose: true
        })

        let $MerchantChannel  = this.$parent.$parent.$parent
        let is_ok = response.data.is_ok
        let is_apply = response.data.is_apply
        $MerchantChannel.wxpay_timelines = response.data.history
        $MerchantChannel.show_wxpay_dialog  = !is_ok && is_apply

        this.ajax = false
      }).catch(err => {
        console.log(err)
        this.ajax = false
      })
    }
  }
}
</script>
