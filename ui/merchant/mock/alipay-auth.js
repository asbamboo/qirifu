export default [
  {
    url: '/alipay/auth',
    type: 'post',
    response: data => {
      const post = data.body
      return {
        status: 'success',
        message: '授权成功。'
      }
    }
  },
]
