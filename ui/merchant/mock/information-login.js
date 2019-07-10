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
        code: 20000,
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
          code: 500,
          message: '请输入账号'
        }
      }

      return {
        code: 20000,
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
          code: 500,
          message: '请输入email'
        }
      }

      if(!post.captcha || !post.captcha.trim){
        return {
          code: 500,
          message: '请输入验证码'
        }
      }

      return {
        code: 20000,
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
          code: 500,
          message: '请输入手机号'
        }
      }

      if(!post.captcha || !post.captcha.trim){
        return {
          code: 500,
          message: '请输入验证码'
        }
      }

      return {
        code: 20000,
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
          code: 500,
          message: '请输入原始密码'
        }
      }

      if(!post.new_password){
        return {
          code: 500,
          message: '请输入新密码'
        }
      }

      if(post.new_password != post.confirm_password){
        return {
          code: 500,
          message: '两次密码输入不一致'
        }
      }

      return {
        code: 20000,
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
          code: 500,
          message: '请先输入email或手机号'
        }
      }

      return {
        code: 20000,
        message: '发送成功，请查收邮件或手机短信。'
      }
    }
  }
]
