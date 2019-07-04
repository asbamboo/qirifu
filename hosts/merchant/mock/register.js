export default [
  {
    url: '/register/send-captcha',
    type: 'post',
    response: data => {
      const post = data.body

      if(!post.email || !post.email.trim){
        return {
          code: 500,
          message: '请先输入email'
        }
      }

      return {
        code: 20000,
        message: '发送成功，请查收邮件。'
      }
    }
  },

  {
    url: '/register',
    type: 'post',
    response: data => {
      const post = data.body

      if(!post.email || !post.email.trim()){
        return {
          code: 500,
          message: '请输入email'
        }
      }

      if(!post.password){
        return {
          code: 500,
          message: '请输入密码'
        }
      }

      if(!post.confirm_password || post.confirm_password != post.password){
        return {
          code: 500,
          message: '两次密码输入不一致'
        }
      }

      if(!post.captcha){
        return {
          code: 500,
          message: '请输入验证码'
        }
      }

      return {
        code: 20000,
        message: '发送成功，请查收邮件。'
      }
    }
  }
]
