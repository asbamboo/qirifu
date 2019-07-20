import Mock from 'mockjs'

export default [
  {
    url: '/qrcode/get-data',
    type: 'get',
    response: () => {
      const data = Mock.mock({
        qrcode: '@url'
      })
      return {
        status: 'success',
        data: data
      }
    }
  }
]
