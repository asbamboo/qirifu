/* eslint-disable */

<template>
  <div class="app-container">
    <el-form ref="system-info-form"
      :model="system_info"
      class="form-container"
    >
      <el-row>
        <el-col :md="8">
          <el-form-item label-width="100px" label="系统名称:">
            <el-input
              v-model="system_info.name"
              type="text"
              name="system_name"
              placeholder="请输入系统名称"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-tooltip
            content="服务商名称会显示在商户二维码的底部"
            placement="top"
            effect="dark"
          >
            <el-form-item label-width="100px" label="服务商名称:">
              <el-input
                v-model="system_info.faciltator"
                type="text"
                name="system_faciltator"
                placeholder="请输入服务商名称"
              />
            </el-form-item>
          </el-tooltip>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-tooltip
            content="绑定手机号需要发送短信验证码，可能会产生费用。（该功能暂时不开放）"
            placement="top-start"
            effect="dark"
          >
            <el-form-item label="允许手机登录">
              <el-switch disabled></el-switch>
            </el-form-item>
          </el-tooltip>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label-width="100px" label="管理员账号:">
            <el-input
              v-model="system_info.user"
              type="text"
              name="system_user"
              placeholder="请输入管理员账号"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label-width="100px" label="管理员密码:">
            <el-input
              v-model="system_info.password"
              type="password"
              name="system_password"
              placeholder="请输入管理员密码"
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
import { fetchSystemInfo, settingSystemInfo } from '@/api/system-setting'

const system_info = {
  name: '',
  faciltator: '',
  user: '',
  password: ''
}

export default {
  name: 'SystemInfoForm',
  data() {
    return {
      system_info: Object.assign({}, system_info),
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
      fetchSystemInfo().then(response => {
        this.ajax = false
        this.system_info = response.data
      }).catch(err => {
        this.ajax = false
        console.log(err)
      })
    },
    submitForm() {
      if(this.ajax == true){
        return
      }
      this.ajax = true
      settingSystemInfo(this.system_info).then(response => {
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
