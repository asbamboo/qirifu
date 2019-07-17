import Mock from 'mockjs'

export default [
  {
    url: '/merchant/save',
    type: 'post',
    response: () => {
      return {
        status: 'success',
        message: '操作成功'
      }
    }
  },

  {
    url: '/merchant/get-info',
    type: 'get',
    response: () => {
      const item = Mock.mock({
        seq: '@increment',
        name: '@ctitle(2, 4)', //企业简称
        fullname: '@name 有限公司', //企业全称
        profession: '@ctitle(2,6)', //行业
        businecate: '@ctitle(2,6)', //经营类目
        code: '@string(ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890, 16)', // 统一社会信用代码,
        address_register: '@province@city@county@ctitle(4,10)', // 注册地址
        address_actual: '@province@city@county@ctitle(4,10)', // 实际地址
        link_man: '@cname', // 联系人姓名
        link_phone: '@natural(10000000000,99999999999)', // 联系人手机号码
        link_email: '@email', //联系人邮箱
        legal_man: '@cname', //法定代表人姓名
        'legal_id_type|1': ['身份证','其他'], //法定代表人证件类型
        legal_id_no: '@natural(100000000000000000,999999999999999999)', //法定代表人证件号码
        legal_id_indate: '长期', //证件有效期
        'bank_account_type|1': ['对公账户', '法人账户'], //银行结算账户类型
        bank_account_no: '@natural(1000000000000000000,9999999999999999999)', //银行结算账户
        bank_account_name: '@fullname', //开户名称
        bank_name: '中国银行', //开户银行
        create_ymdhis: '@datetime', //创建时间
        update_ymdhis: '@datetime', //最后修改时间
        'files|0-10': [{fileid:'@increment', url: '@image', name: '@word'}] //上传的资料
      })

      return {
        status: 'success',
        data: item,
        editable: true
      }
    }
  },
]
