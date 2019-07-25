import request from '@/utils/request'
import qs from 'qs'

export function fetchList(query) {
  return request({
    url: '/trade/lists',
    method: 'get',
    params: query
  })
}

export function fetchNopayList(query) {
  return request({
    url: '/trade/nopay-lists',
    method: 'get',
    params: query
  })
}

export function syncTrade(data)
{
  return request({
    url: '/trade/sync',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function getSearchOptions() {
  return request({
    url: '/trade/search-options',
    method: 'get'
  })
}
