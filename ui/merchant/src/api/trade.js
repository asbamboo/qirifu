import request from '@/utils/request'
import qs from 'qs'

export function fetchList(query) {
  return request({
    url: '/trade/lists',
    method: 'get',
    params: query
  })
}

export function fetchChannels() {
  return request({
    url: '/trade/channels',
    method: 'get'
  })
}

export function order(data) {
  return request({
    url: '/trade/order',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function getAuthUrl(data) {
  return request({
    url: '/trade/auth-url',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function getAuthInfo(data) {
  return request({
    url: '/trade/auth-info',
    method: 'post',
    data: qs.stringify(data)
  })
}
