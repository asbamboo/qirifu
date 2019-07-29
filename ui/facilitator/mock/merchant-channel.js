export default [
  {
    url: '/merchant/alipay/channel-available-status',
    type: 'get',
    response: config => {
      const items = [
        { key : 0, status: 'no-apply', label: '未申请', 'desc': ''},
        { key : 1, status: 'apply-ing', label: '商户申请开通', 'desc': '状态修改为商户申请开通'},
        { key : 2, status: 're-apply', label: '补充或修改资料后，再次申请开通', 'desc': '状态修改为补充或修改资料后，再次申请开通'},
        { key : 3, status: 'refuse', label: '审核未通过，需要商户补充或修改资料', 'desc': '状态修改为审核未通过，需要商户补充或修改资料'},
        { key : 4, status: 'send-alipay', label: '资料已提交到支付宝、等待审核', 'desc': '状态修改为资料已提交到支付宝、等待审核'},
        { key : 5, status: 'alipay-refuse', label: '支付宝审核未通过，需要商户补充或修改资料', 'desc': '状态修改为支付宝审核未通过，需要商户补充或修改资料'},
        { key : 6, status: 'resend-alipay', label: '补充或修改资料后，重新提交到支付宝，等待审核', 'desc': '状态修改为补充或修改资料后，重新提交到支付宝，等待审核'},
        { key : 7, status: 'wait-authorization', label: '审核通过，等待商户通过支付宝账号授权', 'desc': '状态修改为补充或修改资料后，审核通过，等待商户通过<a href="http://auth.example">支付宝账号授权</a>'},
        { key : 9, status: 'ok', label: '正式开通', 'desc': '状态修改为补充或修改资料后，审核通过，等待商户通过支付宝账号授权'}
      ]
      return {
        status: 'success',
        data: {
          items: items
        }
      }
    }
  },

  {
    url: '/merchant/alipay/channel-create-history',
    type: 'post',
    response: config => {
      let code = 20000
      let message = '成功'
      return {
        status: 'success',
        message: message,
        data: {
          is_ok: false,
          is_apply: true,
          history: [
            {
              'seq': '@increment',
              'status': '商户申请开通。',
              'time': '2019-01-01 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '资料已提交到支付宝、等待审核。',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '审核未通过，需要商户补充或修改资料。',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '资料重新提交到支付宝、等待审核。',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '审核通过，等待商户支付宝账号授权。',
              'time': '2019-01-03 00:00:00'
            }
          ]
        }
      }
    }
  },

  {
    url: '/merchant/wxpay/channel-available-status',
    type: 'get',
    response: config => {
      const items = [
        { key : 0, status: 'no-apply', label: '未申请', 'desc': ''},
        { key : 1, status: 'apply-ing', label: '商户申请开通', 'desc': ''},
        { key : 2, status: 'send-wxpay', label: '资料已提交到微信支付、等待审核', 'desc': ''},
        { key : 3, status: 'wxpay-refuse', label: '审核未通过，需要商户补充或修改资料', 'desc': ''},
        { key : 4, status: 're-apply', label: '补充或修改资料后，再次申请开通', 'desc': ''},
        { key : 5, status: 'resend-wxpay', label: '补充或修改资料后，重新提交到微信支付，等待审核', 'desc': ''},
        { key : 9, status: 'ok', label: '正式开通', 'desc': '<a href="http://example.com">excample</a>'}
      ]
      return {
        status: 'success',
        data: {
          items: items
        }
      }
    }
  },

  {
    url: '/merchant/wxpay/channel-create-history',
    type: 'post',
    response: config => {
      let message = '成功'
      return {
        status: 'success',
        message: message,
        data: {
          is_ok: false,
          is_apply: true,
          history: [
            {
              'seq': '@increment',
              'status': '商户申请开通。',
              'time': '2019-01-01 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '资料已提交到微信、等待审核。',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '审核未通过，需要商户补充或修改资料。',
              'time': '2019-01-02 00:00:00'
            },
            {
              'seq': '@increment',
              'status': '资料重新提交到微信、等待审核。',
              'time': '2019-01-02 00:00:00'
            }
          ]
        }
      }
    }
  }
]
