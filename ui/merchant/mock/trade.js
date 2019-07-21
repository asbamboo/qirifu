import Mock from 'mockjs'

const List = []
const count = 100

const channels = [{key: 1, label:'支付宝'}, {key: 2, label:'微信'}]

for (let i = 0; i < count; i++) {
  List.push(Mock.mock({
    seq: '@increment',
    'channel|1': channels, // 支付通道
    amount: '@float(0, 9999, 0, 2)', // 金额
    in_trade_no: '@string(ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890, 13)', // 交易编号（本系统）
    out_trade_no: '@string(ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890, 13)', // 交易编号（支付通道）
    pay_ymdhis: '@datetime', //支付时间
    create_ymdhis: '@datetime' //交易时间
  }))
}

export default [
  {
    url: '/trade/lists',
    type: 'get',
    response: config => {
      const {
        channel,
        in_trade_no,
        out_trade_no,
        create_ymdhis,
        page = 1,
        limit = 10
      } = config.query

      let mockList = List.filter(item => {
        if (in_trade_no && item.in_trade_no.indexOf(in_trade_no) < 0) {
          return false
        }
        if (channel) {
          for(var i in item.channel){
            if(item.channel.key != channel){
              return false
            }
          }
        }
        if (
              out_trade_no
          &&  item.out_trade_no.toString().indexOf(out_trade_no) < 0
        ){
          return false
        }
        if (create_ymdhis) {
          let search_ymdhis = create_ymdhis.sort()
          if(item.create_ymdhis < search_ymdhis[0]){
            return false
          }
          if(item.create_ymdhis > search_ymdhis[1]){
            return false
          }
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
    url: '/trade/channels',
    type: 'get',
    response: config => {
      return {
        status: 'success',
        data: {
          channels: channels
        }
      }
    }
  },

  {
    url: '/trade/auth-url',
    type: 'post',
    response: config => {
      return {
        status: 'success',
        data: {
          auth_url: 'https://openauth.alipaydev.com/oauth2/publicAppAuthorize.htm?app_id=2016090900468991&scope=auth_base&redirect_uri=http%3A%2F%2F192.168.0.104%3A9528%2Fhosts%2Fwww%2Fpublic%2F%23%2Ftrade%2F5d2c0a2d48de1%2Forder'
        }
      }
    }
  },

  {
    url: '/trade/auth-info',
    type: 'post',
    response: config => {
      return {
        status: 'success',
        data: {
          user_id: 123456789
        }
      }
    }
  },

  {
    url: '/trade/order',
    type: 'post',
    response: config => {
      return {
        status: 'success',
        data: {
          onecd_pay_json: {trade_no: 4546546}
        }
      }
    }
  }
]
