import request from '@/utils/request'

export function fetchAlipayAvailableStatus() {
  return request({
    url: '/merchant/channel-alipay-available-status',
    method: 'get'
  })
}

export function createAlipayHistory(data) {
  let qs = require('qs');
  return request({
    url: '/merchant/channel-alipay-create-history',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function fetchWxpayAvailableStatus() {
  return request({
    url: '/merchant/channel-wxpay-available-status',
    method: 'get'
  })
}

export function createWxpayHistory(data) {
  let qs = require('qs');
  return request({
    url: '/merchant/channel-wxpay-create-history',
    method: 'post',
    data: qs.stringify(data)
  })
}
