import Mock from 'mockjs'

export default [
  {
    url: '/information/qrcode/get-data',
    type: 'get',
    response: () => {
      const data = Mock.mock({
        qrcode: '@url'
      })
      return {
        code: 20000,
        data: data
      }
    }
  }
]
