<template>
  <div class="register-container">
    <el-form
      ref="registerForm"
      :model="register_form"
      class="register-form"
      autocomplete="on"
      label-position="left"
    >
      <div class="title-container">
        <h3 class="title">商户账号注册</h3>
      </div>

      <el-form-item prop="email">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input
          ref="email"
          v-model="register_form.email"
          placeholder="请输入Email"
          name="email"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
      </el-form-item>

      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          ref="password"
          v-model="register_form.password"
          type="password"
          placeholder="请输入密码"
          name="password"
          tabindex="2"
          autocomplete="on"
        />
      </el-form-item>

      <el-form-item prop="confirm_password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          ref="confirm-password"
          v-model="register_form.confirm_password"
          type="password"
          placeholder="请再次输入密码"
          name="password"
          tabindex="3"
          autocomplete="on"
        />
      </el-form-item>

      <el-form-item prop="captcha">
        <span class="svg-container">
          <svg-icon icon-class="people" />
        </span>
        <el-input
          v-model="register_form.captcha"
          type="text"
          placeholder="请输入验证码"
          name="password"
          tabindex="4"
          autocomplete="off"
        />
        <el-tooltip
          class="item"
          effect="light"
          content="验证码将发送到您输入的email，请注意查收。"
          placement="top-end"
        >
          <span
            class="captcha-send"
            v-if="captcha_sended == 'no'"
            @click="doSendCaptcha()"
          >
            <svg-icon icon-class="guide" />
            点此发送验证码
          </span>
          <span class="captcha-sended" v-if="captcha_sended == 'yes'">
            <svg-icon icon-class="lock" />
            {{ captcha_resend_limit }}秒
          </span>
        </el-tooltip>
      </el-form-item>

      <el-button
        :loading="ajax"
        type="primary"
        style="width:100%;margin-bottom:30px;"
        @click.native.prevent="doRegister()"
      >注册</el-button>

      <router-link to="/login" class="tips">
        已经有账号了，点此登录！
      </router-link>
    </el-form>
  </div>
</template>

<script>
import { sendCaptcha, register } from '@/api/register'

export default {
  name: 'Register',
  data() {
    return {
      register_form: {
        email: '',
        password: '',
        confirm_password: '',
        captcha: ''
      },
      captcha_sended: 'no',
      captcha_resend_limit: 120,
      captcha_timer: undefined,
      ajax: false
    }
  },
  methods: {
    doSendCaptcha() {
      console.log(process.env.VUE_APP_BASE_API)
      this.captcha_sended = 'yes'
      sendCaptcha(this.register_form).then(response => {
        console.log(response)
        this.$message({
          message: response.message,
          showClose: true
        })
        this.captcha_resend_limit = 120
        this.captcha_timer = setInterval(this.changeCaptchaResendLimit, 1000)
      }).catch(err => {
        this.captcha_sended = 'no'
        console.log(err)
      })
    },
    changeCaptchaResendLimit() {
      this.captcha_resend_limit = this.captcha_resend_limit - 1
      if(this.captcha_resend_limit < 0) {
        this.captcha_sended = 'no'
        this.captcha_resend_limit = 120
        clearInterval(this.captcha_timer)
      }
    },
    doRegister() {
      this.$refs.registerForm.validate(valid => {
        if (valid) {
          this.ajax = true
          register(this.register_form).then(response => {
            this.$alert('点击确定，前往登录页面！', '注册成功', {
              callback: action => {
                this.$router.push('/login')
              }
            })
            this.ajax = false
          }).catch(err => {
            this.ajax = false
          })
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
  },
  beforeDestroy() {
    clearInterval(this.captcha_timer)
  }
}
</script>

<style lang="scss">
/* 修复input 背景不协调 和光标变色 */
/* Detail see https://github.com/PanJiaChen/vue-element-admin/pull/927 */

$bg:#283443;
$light_gray:#fff;
$cursor: #fff;

@supports (-webkit-mask: none) and (not (cater-color: $cursor)) {
  .register-container .el-input input {
    color: $cursor;
  }
}

/* reset element-ui css */
.register-container {
  .el-input {
    display: inline-block;
    height: 47px;
    width: 85%;

    input {
      background: transparent;
      border: 0px;
      -webkit-appearance: none;
      border-radius: 0px;
      padding: 12px 5px 12px 15px;
      color: $light_gray;
      height: 47px;
      caret-color: $cursor;

      &:-webkit-autofill {
        box-shadow: 0 0 0px 1000px $bg inset !important;
        -webkit-text-fill-color: $cursor !important;
      }
    }
  }

  .el-form-item {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    color: #454545;
  }
}
</style>

<style lang="scss" scoped>
$bg:#2d3a4b;
$dark_gray:#889aa4;
$light_gray:#eee;

.register-container {
  min-height: 100%;
  width: 100%;
  background-color: $bg;
  overflow: hidden;

  .register-form {
    position: relative;
    width: 520px;
    max-width: 100%;
    padding: 160px 35px 0;
    margin: 0 auto;
    overflow: hidden;
  }

  .tips {
    font-size: 14px;
    color: #fff;
    margin-bottom: 10px;

    span {
      &:first-of-type {
        margin-right: 16px;
      }
    }
  }

  .svg-container {
    padding: 6px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
  }

  .title-container {
    position: relative;

    .title {
      font-size: 26px;
      color: $light_gray;
      margin: 0px auto 40px auto;
      text-align: center;
      font-weight: bold;
    }
  }

  .captcha-send, .captcha-sended {
    position: absolute;
    right: 10px;
    top: 7px;
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
    user-select: none;
  }

  .thirdparty-button {
    position: absolute;
    right: 0;
    bottom: 6px;
  }

  @media only screen and (max-width: 470px) {
    .thirdparty-button {
      display: none;
    }
  }
}
</style>
