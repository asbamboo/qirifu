import request from '@/utils/request'
import qs from 'qs'

export function getMerchantInfo() {
  return request({
    url: '/merchant/get-info',
    method: 'get'
  })
}

export function saveMerchantInfo(data) {
  return request({
    url: '/merchant/save',
    method: 'post',
    data: qs.stringify(data)
  })
}
