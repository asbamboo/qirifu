export default [
  {
    url: '/information/channel/get-info',
    type: 'get',
    response: () => {
      let channel =
      {
        alipay: {
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
            },
            {
              'seq': '@increment',
              'status': '正式开通。商户号(seller_id):2088102146225135',
              'time': '2019-01-04 00:00:00'
            }
          ]
        },
        wxpay: {
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
            },
            {
              'seq': '@increment',
              'status': '审核通过，正式开通。商户号(sub_mch_id):1900000109',
              'time': '2019-01-04 00:00:00'
            }
          ]
        }
      }

      return {
        code: 20000,
        data: { channel : channel }
      }
    }
  }
]