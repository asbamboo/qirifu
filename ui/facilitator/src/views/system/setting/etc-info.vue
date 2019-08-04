<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :md="12">
        <el-form
          ref="etcForm"
          :model="etc_info"
          class="form-container"
          label-width="135px"
        >
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>支付宝相关配置</span>
            </div>
            <el-form-item label="支付宝沙箱环境:">
              <el-switch v-model="etc_info.alipay_sandbox"></el-switch>
            </el-form-item>
            <el-form-item label="支付宝APPID:">
              <el-input
                v-model="etc_info.alipay_appid"
                type="text"
                name="alipay_app_id"
                placeholder="请输入支付宝APPID"
              />
            </el-form-item>
            <el-form-item label="支付宝RSR2私钥:">
              <el-input
                v-model="etc_info.alipay_rsa_private_key"
                type="textarea"
                rows="5"
                name="alipay_rsa_private_key"
                placeholder="请输入支付宝RSR2私钥"
              />
            </el-form-item>
            <el-form-item label="支付宝公钥:">
              <el-input
                v-model="etc_info.alipay_rsa_alipay_key"
                type="textarea"
                rows="5"
                name="alipay_rsa_alipay_key"
                placeholder="请输入支付宝公钥"
              />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" plain @click="submitForm('alipay')">
                提交修改
              </el-button>
            </el-form-item>
          </el-card>
        </el-form>
      </el-col>

      <el-col :md="12">
        <el-form
          ref="etcForm"
          :model="etc_info"
          class="form-container"
          label-width="135px"
        >
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>微信相关配置</span>
            </div>
            <el-form-item label="微信APPID:">
              <el-input
                v-model="etc_info.wxpay_appid"
                type="text"
                name="wxpay_appid"
                placeholder="请输入微信APPID"
              />
            </el-form-item>
            <el-form-item label="微信APPID:">
              <el-input
                v-model="etc_info.wxpay_appsecret"
                type="text"
                name="wxpay_appsecret"
                placeholder="请输入微信app secret"
              />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" plain @click="submitForm('wxpay')">
                提交修改
              </el-button>
            </el-form-item>
          </el-card>
        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { fetchEtcInfo, settingEtcInfo } from '@/api/system-setting'

const etc_info = {
  alipay_appid: '',
  alipay_sandbox: false,
  alipay_rsa_private_key: '',
  alipay_rsa_alipay_key: '',
  wxpay_appid: '',
  wxpay_appsecret: ''
};

export default {
  name: 'EtcInfoForm',
  data() {
    return {
      etc_info: Object.assign({}, etc_info),
      ajax: false
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      if(this.ajax == true){
        return
      }
      this.ajax = true
      fetchEtcInfo().then(response => {
        this.etc_info = response.data
        this.ajax = false
      }).catch(err => {
        console.log(err)
        this.ajax = false
      })
    },
    submitForm(type) {
      if(this.ajax == true){
        return
      }
      this.ajax = true
      let post_data = this.etc_info
      post_data.type = type
      settingEtcInfo(post_data).then(response => {
        this.ajax = false
        this.$message({
          message: response.message
        })
      }).catch(err => {
        this.ajax = false
        console.log(err)
      })
    }
  }
}
</script>
