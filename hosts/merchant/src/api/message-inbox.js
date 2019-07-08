import request from '@/utils/request'

export function getList(query) {
  return request({
    url: '/message/inbox/lists',
    method: 'get',
    params: query
  })
}

export function readMessage(seq) {
  let qs = require('qs');

  return request({
    url: '/message/inbox/read',
    method: 'post',
    data: qs.stringify({seq:seq})
  })
}
