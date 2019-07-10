import request from '@/utils/request'

export function getMerchantInfo() {
  return request({
    url: '/information/merchant/get-info',
    method: 'get'
  })
}

export function saveMerchantInfo(data) {
  let qs = require('qs');
  return request({
    url: '/information/merchant/save',
    method: 'post',
    data: qs.stringify(data)
  })
}
