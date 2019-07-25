import Mock from 'mockjs'

const List = []
const count = 100

const channels = [{key: 1, label:'支付宝'}, {key: 2, label:'微信'}]
const statuss = [
  {key: 1, label:'未支付'},
  {key: 2, label:'已支付'},
  {key: 3, label:'取消'}
]

for (let i = 0; i < count; i++) {
  List.push(Mock.mock({
    seq: '@increment',
    merchant_name: '@ctitle(2, 4)', //商户简称
    'channel|1': channels, // 支付通道
    'status|1': statuss, // 支付状态
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
        merchant_name,
        channel,
        status,
        in_trade_no,
        out_trade_no,
        create_ymdhis,
        page = 1,
        limit = 10
      } = config.query

      let mockList = List.filter(item => {
        if (merchant_name && item.merchant_name.indexOf(merchant_name) < 0){
          return false
        }
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
        if (
              status
          &&  item.status.key != status
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
    url: '/trade/nopay-lists',
    type: 'get',
    response: config => {
      const {
        start_seq,
        page = 1,
        limit = 10
      } = config.query

      let mockList = List.filter(item => {
        if (item.status.key != 1){
          return false
        }
        if(start_seq && item.seq < start_seq){
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
    url: '/trade/sync',
    type: 'post',
    response: config => {
      let status = 'success'
      let message = '成功'
      let post = config.body
      if( post.trade_no == ''){
        status = 'failed'
        message = '请输入交易编号。'
      }

      for (const item of List) {
        if (item.in_trade_no === post.in_trade_no) {
          let rand = 1
          if(rand == 1){
            item.status = {key: 1, label:'未支付', name: 'nopay'}
          }else if(rand == 2){
            item.status = {key: 2, label:'已支付', name: 'payok'}
          }else{
            item.status = {key: 3, label:'取消', name: 'cancel'}
          }
          return {
            status: 'success',
            data: item
          }
        }
      }
    }
  },

  {
    url: '/trade/search-options',
    type: 'get',
    response: config => {
      return {
        status: 'success',
        data: {
          channels: channels,
          statuss: statuss
        }
      }
    }
  }

]
