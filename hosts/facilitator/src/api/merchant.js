import request from '@/utils/request'

export function fetchList(query) {
  return request({
    url: '/merchant/lists',
    method: 'get',
    params: query
  })
}

export function fetchDetail(id) {
  return request({
    url: '/merchant/detail',
    method: 'get',
    params: { id }
  })
}
