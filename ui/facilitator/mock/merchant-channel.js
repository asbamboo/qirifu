export default [
  {
    url: '/merchant/channel-alipay-available-status',
    type: 'get',
    response: config => {
      const items = [
        { key : 0, status: 'no-apply', label: '未申请'},
        { key : 1, status: 'apply-ing', label: '商户申请开通'},
        { key : 2, status: 'send-alipay', label: '资料已提交到支付宝、等待审核'},
        { key : 3, status: 'alipay-refuse', label: '审核未通过，需要商户补充或修改资料'},
        { key : 4, status: 'resend-alipay', label: '补充或修改资料后，重新提交到支付宝，等待审核'},
        { key : 5, status: 'wait-authorization', label: '审核通过，等待商户通过支付宝账号授权'},
        { key : 9, status: 'ok', label: '正式开通'}
      ]
      return {
        code: 20000,
        data: {
          items: items
        }
      }
    }
  },

  {
    url: '/merchant/channel-alipay-create-history',
    type: 'post',
    response: config => {
      let code = 20000
      let message = '成功'
      return {
        code: code,
        message: message
      }
    }
  },

  {
    url: '/merchant/channel-wxpay-available-status',
    type: 'get',
    response: config => {
      const items = [
        { key : 0, status: 'no-apply', label: '未申请'},
        { key : 1, status: 'apply-ing', label: '商户申请开通'},
        { key : 2, status: 'send-wxpay', label: '资料已提交到微信支付、等待审核'},
        { key : 3, status: 'wxpay-refuse', label: '审核未通过，需要商户补充或修改资料'},
        { key : 4, status: 'resend-wxpay', label: '补充或修改资料后，重新提交到微信支付，等待审核'},
        { key : 9, status: 'ok', label: '正式开通'}
      ]
      return {
        code: 20000,
        data: {
          items: items
        }
      }
    }
  },

  {
    url: '/merchant/channel-wxpay-create-history',
    type: 'post',
    response: config => {
      let code = 20000
      let message = '成功'
      return {
        code: code,
        message: message
      }
    }
  }
]
