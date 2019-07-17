import request from '@/utils/request'
import qs from 'qs'

export function fetchAlipayAvailableStatus() {
  return request({
    url: '/merchant/alipay/channel-available-status',
    method: 'get'
  })
}

export function createAlipayHistory(data) {
  return request({
    url: '/merchant/alipay/channel-create-history',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function fetchWxpayAvailableStatus() {
  return request({
    url: '/merchant/wxpay/channel-available-status',
    method: 'get'
  })
}

export function createWxpayHistory(data) {
  return request({
    url: '/merchant/wxpay/channel-create-history',
    method: 'post',
    data: qs.stringify(data)
  })
}
