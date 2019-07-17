import request from '@/utils/request'
import qs from 'qs'

export function getChannelInfo() {
  return request({
    url: '/channel/get-info',
    method: 'get'
  })
}

export function createChannel(data) {
  return request({
    url: '/channel/new',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function updateChannel(data) {
  return request({
    url: '/channel/update',
    method: 'post',
    data: qs.stringify(data)
  })
}
