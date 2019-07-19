<template>
  <div class="app-container">
    <el-form
      ref="etcForm"
      :model="etc_info"
      class="form-container"
      label-width="135px"
    >
      <el-row>
        <el-col :md="8">
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
                autosize
                name="alipay_rsa_private_key"
                placeholder="请输入支付宝RSR2私钥"
              />
            </el-form-item>
            <el-form-item label="支付宝公钥:">
              <el-input
                v-model="etc_info.alipay_rsa_alipay_key"
                type="textarea"
                autosize
                name="alipay_rsa_alipay_key"
                placeholder="请输入支付宝公钥"
              />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" plain @click="submitForm">
                提交修改
              </el-button>
            </el-form-item>
          </el-card>
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import { fetchEtcInfo, settingEtcInfo } from '@/api/system-setting'

const etc_info = {
  alipay_appid: '',
  alipay_sandbox: false,
  alipay_rsa_private_key: '',
  alipay_rsa_alipay_key: ''
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
    submitForm() {
      if(this.ajax == true){
        return
      }
      this.ajax = true
      settingEtcInfo(this.etc_info).then(response => {
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
