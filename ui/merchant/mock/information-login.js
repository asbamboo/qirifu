export default [

  {
    url: '/information/login/info',
    type: 'get',
    response: config => {

      const info = {
        account : '',
        email: 'sadf@aa.com',
        phone: ''
      }

      return {
        status: 'success',
        data: info
      }
    }
  },

  {
    url: '/information/login/setting-account',
    type: 'post',
    response: data => {
      const post = data.body

      if(!post.account || !post.account.trim){
        return {
          status: 'failed',
          message: '请输入账号'
        }
      }

      return {
        status: 'success',
        message: '账号设置成功。'
      }
    }
  },

  {
    url: '/information/login/setting-email',
    type: 'post',
    response: data => {
      const post = data.body

      if(!post.email || !post.email.trim){
        return {
          status: 'failed',
          message: '请输入email'
        }
      }

      if(!post.captcha || !post.captcha.trim){
        return {
          status: 'failed',
          message: '请输入验证码'
        }
      }

      return {
        status: 'success',
        message: 'email绑定成功。'
      }
    }
  },

  {
    url: '/information/login/setting-phone',
    type: 'post',
    response: data => {
      const post = data.body

      if(!post.phone || !post.phone.trim){
        return {
          status: 'failed',
          message: '请输入手机号'
        }
      }

      if(!post.captcha || !post.captcha.trim){
        return {
          status: 'failed',
          message: '请输入验证码'
        }
      }

      return {
        status: 'success',
        message: '手机号绑定成功。'
      }
    }
  },

  {
    url: '/information/login/reset-password',
    type: 'post',
    response: data => {
      const post = data.body

      if(!post.org_password){
        return {
          status: 'failed',
          message: '请输入原始密码'
        }
      }

      if(!post.new_password){
        return {
          status: 'failed',
          message: '请输入新密码'
        }
      }

      if(post.new_password != post.confirm_password){
        return {
          status: 'failed',
          message: '两次密码输入不一致'
        }
      }

      return {
        status: 'success',
        message: '密码重置成功。'
      }
    }
  },

  {
    url: '/information/login/send-captcha',
    type: 'post',
    response: data => {
      const post = data.body

      if(   (!post.email || !post.email.trim)
        &&  (!post.phone || !post.phone.trim)
      ){
        return {
          status: 'failed',
          message: '请先输入email或手机号'
        }
      }

      return {
        status: 'success',
        message: '发送成功，请查收邮件或手机短信。'
      }
    }
  }
]
