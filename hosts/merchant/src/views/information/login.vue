<template>
  <div class="app-container">
    <el-row>
      <el-col :md="8">
        <el-form
          class="form-container"
          label-width="100px"
          label-position="right"
        >
          <el-form-item label="账号">
            <el-input
              v-model="account"
              type="text"
              placeholder="请输入账号"
            />
            <el-button
              v-if="!account_isset"
              :disabled="ajax"
              @click="doSetAccount"
              type="primary"
              class="form-item-button"
            >
              设置账号
            </el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>

    <el-row>
      <el-col :md="8">
        <el-form
          class="form-container"
          label-width="100px"
          label-position="right"
        >
          <el-form-item label="Email">
            <el-input
              v-model="email"
              type="text"
              placeholder="请输入email"
            />
            <el-button
              v-if="!email_isset"
              :disabled="ajax"
              @click="doSettingEmail"
              type="primary"
              class="form-item-button"
            >
              立即绑定
            </el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>

    <el-row>
      <el-col :md="8">
        <el-form
          class="form-container"
          label-width="100px"
          label-position="right"
        >
          <el-form-item label="手机号码">
            <el-input
              v-model="phone"
              type="text"
              placeholder="请输入手机号码"
            />
            <el-button
              v-if="!phone_isset"
              :disabled="ajax"
              @click="doSettingPhone"
              type="primary"
              class="form-item-button"
            >
              立即绑定
            </el-button>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>

    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>重置密码</span>

      </div>
      <div>
        <el-form
            class="form-container"
            label-width="100px"
            label-position="right"
          >
          <el-row>
            <el-col :md="8">
              <el-form-item label="原密码">
                <el-input
                  v-model="org_password"
                  type="password"
                  placeholder="请输入原始密码"
                />
              </el-form-item>
              <el-form-item label="新密码">
                <el-input
                  v-model="new_password"
                  type="password"
                  placeholder="请输入新密码"
                />
              </el-form-item>
              <el-form-item label="确认新密码">
                <el-input
                  v-model="confirm_password"
                  type="password"
                  placeholder="请再次输入新密码"
                />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :md="2" :offset="4">
              <el-button
                :disabled="ajax"
                @click="doResetPassword"
                type="primary"
              >提交</el-button>
            </el-col>
          </el-row>
        </el-form>
      </div>
    </el-card>
  </div>
</template>

<script>
import {
  getData,
  settingAccount,
  settingEmail,
  settingPhone,
  resetPassword,
  sendCaptcha
} from '@/api/information-login'
import { validEmail } from '@/utils/validate'

export default {
  name: 'InformationLogin',
  data() {
    return {
      account: '',
      account_isset: false,
      email: '',
      email_isset: false,
      phone: '',
      phone_isset: false,
      org_password: '',
      new_password: '',
      confirm_password: '',
      ajax: false
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData(){
      this.ajax = true
      getData().then(response => {
        this.account = response.data.account
        this.email = response.data.email
        this.phone = response.data.phone
        this.account_isset = this.account.length > 0
        this.email_isset = this.email.length > 0
        this.phone_isset = this.phone.length > 0
        this.ajax = false
      }).catch(err => {
        this.ajax = false
      })
    },
    doSetAccount(){
      this.ajax = true
      settingAccount({account: this.account}).then(response => {
        this.ajax = false
        this.account_isset = true
        this.$message({
          message: response.message,
          showClose: true
        })
      }).catch(err => {
        this.ajax = false
        console.log(err)
      })
    },
    doSettingEmail(){
      if(!validEmail(this.email)){
        return this.$message.error('请输入有效的email')
      }

      this.ajax = true
      sendCaptcha({email: this.email}).then(response => {
        this.ajax = false
        this.$prompt('请输入email验证码(请注意查收email)', '确认email绑定', {
          inputPattern: /./,
          inputErrorMessage: '请输入验证码'
        }).then(({ value }) => {
          this.ajax = true
          settingEmail({
            email: this.email,
            captcha: value
          }).then( response => {
            this.ajax = false
            this.email_isset = true
            this.$message({
              message: response.message,
              showClose: true
            })
          }).catch( err => {
            console.log(err)
            this.ajax = false
          })
        })
      }).catch(err => {
        console.log(err)
        this.ajax = false
      })
    },
    doSettingPhone(){
      if(!/\d+/.test(this.phone.trim())){
        return this.$message.error('请输入有效的手机号')
      }
      this.ajax = true
      sendCaptcha({phone: this.phone}).then(response => {
        this.ajax = false
        this.$prompt('请输入验证码(请注意查收手机短信)', '确认绑定手机号', {
          inputPattern: /./,
          inputErrorMessage: '请输入验证码'
        }).then(({ value }) => {
          this.ajax = true
          settingPhone({
            phone: this.phone,
            captcha: value
          }).then( response => {
            this.ajax = false
            this.phone_isset = true
            this.$message({
              message: response.message,
              showClose: true
            })
          }).catch( err => {
            console.log(err)
            this.ajax = false
          })
        })
      }).catch(err => {
        console.log(err)
        this.ajax = false
      })
    },
    doResetPassword(){
      this.ajax = true
      resetPassword({
        org_password: this.org_password,
        new_password: this.new_password,
        confirm_password: this.confirm_password
      }).then(response => {
        this.ajax = false
        this.$message({
          message: response.message,
          showClose: true
        })
      }).catch(err => {
        console.log(err)
        this.ajax = false
      })
    }
  }
}
</script>

<style>
.form-item-button {
  position: absolute;
  right:0;
  top:0;
}
</style>
