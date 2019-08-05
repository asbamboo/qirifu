import Mock from 'mockjs'

const List = []
const count = 100
const channel_lists = []

const channel_types = [{key: 1, label:'支付宝'}, {key: 2, label:'微信'}]
const channel_statuss = [
  { key : 0, status: 'no-apply', label: '未申请'},
  { key : 1, status: 'apply-ing', label: '商户申请开通'},
  { key : 2, status: 're-apply', label: '补充或修改资料后，再次申请开通'},
  { key : 3, status: 'refuse', label: '审核未通过，需要商户补充或修改资料'},
  { key : 4, status: 'send-thrid', label: '资料已提交到支付宝、等待审核'},
  { key : 5, status: 'thrid-refuse', label: '支付宝审核未通过，需要商户补充或修改资料'},
  { key : 6, status: 'resend-thrid', label: '补充或修改资料后，重新提交到支付宝，等待审核'},
  { key : 7, status: 'wait-authorization', label: '审核通过，等待商户通过支付宝账号授权'},
  { key : 9, status: 'ok', label: '正式开通'}
]

for (let i = 0; i < count; i++) {
  List.push(Mock.mock({
    seq: '@increment',
    name: '@ctitle(2, 4)', //企业简称
    wxpay_businecate: '@ctitle(2,6)', //经营类目
    alipay_account: '@email', //支付宝账号
    code: '@string(ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890, 16)', // 统一社会信用代码,
    address_actual: '@province@city@county@ctitle(4,10)', // 实际地址
    link_man: '@cname', // 联系人姓名
    link_phone: '@natural(10000000000,99999999999)', // 联系人手机号码
    link_email: '@email', //联系人邮箱
    'bank_account_type|1': ['对公账户', '私人(法人)账户（个体工商户可选）'], //银行结算账户类型
    bank_account_no: '@natural(1000000000000000000,9999999999999999999)', //银行结算账户
    bank_account_name: '@name 有限公司', //开户名称
    bank_name: '中国银行', //开户银行
    create_ymdhis: '@datetime', //创建时间
    update_ymdhis: '@datetime', //最后修改时间
    'files|0-10': [{id:'@increment', url: '@url', name: '@word'}] //上传的资料
  }))
}

for (let i = 0; i < count; i++) {
  channel_lists.push(Mock.mock({
    merchant: List[i],
    'type|1': channel_types,
    'status|1': channel_statuss,
    create_ymdhis: '@datetime', //创建时间
    update_ymdhis: '@datetime', //最后修改时间
  }));
}

export default [
  {
    url: '/merchant/lists',
    type: 'get',
    response: config => {
      const { name, link_man, link_phone, page = 1, limit = 10 } = config.query

      let mockList = List.filter(item => {
        if (name && item.name.indexOf(name) < 0) return false
        if (link_man && item.link_man.indexOf(link_man) < 0) return false
        if (link_phone && item.link_phone.toString().indexOf(link_phone) < 0) return false
        return true
      })

      const pageList = mockList.filter((item, index) => index < limit * page && index >= limit * (page - 1))

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
    url: '/merchant/detail',
    type: 'get',
    response: config => {
      const { seq } = config.query
      for (const item of List) {
        if (item.seq === +seq) {
          return {
            status: 'success',
            data: item
          }
        }
      }
    }
  },

  {
    url: '/merchant/channel-search-options',
    type: 'get',
    response: config => {
      return {
        status: 'success',
        data: {
          channel_types: channel_types,
          channel_statuss: channel_statuss
        }
      }
    }
  },

  {
    url: '/merchant/channel-lists',
    type: 'get',
    response: config => {
      const { merchant_name, channel_type, channel_status, page = 1, limit = 10 } = config.query

      let mockList = channel_lists.filter(item => {
        if (merchant_name && item.merchant.name.indexOf(merchant_name) < 0) return false
        if (channel_type && item.type.key != channel_type) return false
        if (channel_status && item.status.key != channel_status) return false
        return true
      })

      const pageList = mockList.filter((item, index) => index < limit * page && index >= limit * (page - 1))

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
    url: '/merchant/channel',
    type: 'get',
    response: config => {
      const { seq } = config.query
      let merchant = null
      for (const item of List) {
        if (item.seq === +seq) {
          merchant = item
        }
      }
      let alipay_is_ok = Math.round(Math.random())
      let wxpay_is_ok = Math.round(Math.random())
      let channel =
      {
        alipay: {
          is_ok: alipay_is_ok,
          is_apply: true,
          history: [
            {
              'seq': '@increment',
              'status': '商户申请开通。',
              'desc': '@cparagraph',
              'time': '2019-01-01 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '资料已提交到支付宝、等待审核。',
              'desc': '@cparagraph',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '审核未通过，需要商户补充或修改资料。',
              'desc': '@cparagraph',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '资料重新提交到支付宝、等待审核。',
              'desc': '@cparagraph',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '审核通过，等待商户支付宝账号授权。',
              'desc': '@cparagraph',
              'time': '2019-01-03 00:00:00'
            }
          ]
        },
        wxpay: {
          is_ok: wxpay_is_ok,
          is_apply: true,
          history: [
            {
              'seq': '@increment',
              'status': '商户申请开通。',
              'desc': '@cparagraph',
              'time': '2019-01-01 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '资料已提交到微信、等待审核。',
              'desc': '@cparagraph',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '审核未通过，需要商户补充或修改资料。',
              'desc': '@cparagraph',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '资料重新提交到微信、等待审核。',
              'desc': '@cparagraph',
              'time': '2019-01-02 00:00:00'
            }

          ]
        }
      }

      if(alipay_is_ok){
        channel.alipay.history.push({
          'seq': '@increment',
          'status': '正式开通。商户号(seller_id):2088102146225135',
          'desc': '@cparagraph',
          'time': '2019-01-04 00:00:00'
        });
      }
      if(wxpay_is_ok){
        channel.wxpay.history.push({
          'seq': '@increment',
          'status': '审核通过，正式开通。商户号(sub_mch_id):1900000109',
          'desc': '@cparagraph',
          'time': '2019-01-04 00:00:00'
        });
      }
      return {
        status: 'success',
        data: { channel : channel, merchant: merchant }
      }
    }
  },
]
