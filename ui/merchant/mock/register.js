export default [
  {
    url: '/register/send-captcha',
    type: 'post',
    response: data => {
      const post = data.body

      if(!post.email || !post.email.trim){
        return {
          status: 'failed',
          message: '请先输入email'
        }
      }

      return {
        status: 'success',
        message: '发送成功，请查收邮件。'
      }
    }
  },

  {
    url: '/register/action',
    type: 'post',
    response: data => {
      const post = data.body

      if(!post.email || !post.email.trim()){
        return {
          status: 'failed',
          message: '请输入email'
        }
      }

      if(!post.password){
        return {
          status: 'failed',
          message: '请输入密码'
        }
      }

      if(!post.confirm_password || post.confirm_password != post.password){
        return {
          status: 'failed',
          message: '两次密码输入不一致'
        }
      }

      if(!post.captcha){
        return {
          status: 'failed',
          message: '请输入验证码'
        }
      }

      return {
        status: 'success',
        message: '发送成功，请查收邮件。'
      }
    }
  }
]
