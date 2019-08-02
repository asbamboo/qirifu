export default [
  {
    url: '/system/info',
    type: 'get',
    response: _ => {
      return {
        status: 'success',
        data: {
          name: '七日付XXX',
          admin_base_url: 'xxxx'
        }
      }
    }
  },
]
