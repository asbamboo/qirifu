import Mock from 'mockjs'

const List = []
const count = 100

for (let i = 0; i < count; i++) {
  List.push(Mock.mock({
    seq: '@increment',
    is_read: '@boolean',
    title: '@ctitle',
    content: '@cparagraph',
    create_ymdhis: '@datetime' //交易时间
  }))
}

export default [
  {
    url: '/message/inbox/lists',
    type: 'get',
    response: config => {
      const { is_read, page = 1, limit = 10 } = config.query

      let mockList = List.filter(item => {
        if (is_read && item.is_read != is_read) {
          return false
        }
        return true
      })

      const pageList = mockList.filter(
        (item, index) => index < limit * page && index >= limit * (page - 1)
      )

      return {
        status: 'success',
        data: {
          total: mockList.length,
          items: pageList
        }
      }
    }
  },

  {
    url: '/message/inbox/read',
    type: 'post',
    response: config => {
      let post  = config.body

      for (const item of List) {
        if (item.seq === +post.seq) {
          item.is_read = true
          return {
            status: 'success',
            message: '消息状态修改为已阅读'
          }
        }
      }
    }
  }

]
