import request from '@/utils/request'
import qs from 'qs'

export function getList(query) {
  return request({
    url: '/message/inbox/lists',
    method: 'get',
    params: query
  })
}

export function readMessage(seq) {
  return request({
    url: '/message/inbox/read',
    method: 'post',
    data: qs.stringify({seq:seq})
  })
}
