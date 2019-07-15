<template>
  <div class="app-container">
    <el-form
      ref="emailInfoForm"
      :model="email_info"
      class="form-container"
      label-width="135px"
    >
      <el-row>
        <el-col :md="8">
          <el-form-item label="Email Host:">
            <el-input
              v-model="email_info.host"
              type="text"
              name="email_info_host"
              placeholder="请输入email host"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label="Email Port:">
            <el-input
              v-model="email_info.port"
              type="text"
              name="email_info_port"
              placeholder="请输入email port"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label="Email Encryption:">
            <el-input
              v-model="email_info.encryption"
              type="text"
              name="email_info_encryption"
              placeholder="请输入email encryption"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label="Email 账号:">
            <el-input
              v-model="email_info.user"
              type="text"
              name="email_info_user"
              placeholder="请输入email账号"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label="Email 密码:">
            <el-input
              v-model="email_info.password"
              type="text"
              name="email_info_password"
              placeholder="请输入email密码"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :offset="3">
          <el-button type="primary" plain @click="submitForm">
            提交修改
          </el-button>
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import { fetchEmailInfo, settingEmailInfo } from '@/api/system-setting'

const email_info = {
  "host":"",
  "port":"",
  "encryption":"",
  "user":"",
  "password":""
};

export default {
  name: 'EmailInfoForm',
  data() {
    return {
      email_info: Object.assign({}, email_info),
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
      fetchEmailInfo().then(response => {
        this.email_info = response.data
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
      settingEmailInfo(this.email_info).then(response => {
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
