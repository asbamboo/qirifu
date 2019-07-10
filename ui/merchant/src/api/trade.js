import request from '@/utils/request'

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
