import request from '@/utils/request'

export function fetchList(query) {
  return request({
    url: '/merchant/lists',
    method: 'get',
    params: query
  })
}

export function fetchDetail(seq) {
  return request({
    url: '/merchant/detail',
    method: 'get',
    params: { seq }
  })
}

export function fetchChannelInfo(seq){
  return request({
    url: '/merchant/channel',
    method: 'get',
    params: { seq }
  })
}

export function getChannelSearchOptions(){
  return request({
    url: '/merchant/channel-search-options',
    method: 'get'
  })
}

export function fetchChannelList(query){
  return request({
    url: '/merchant/channel-lists',
    method: 'get',
    params: query
  })
}
