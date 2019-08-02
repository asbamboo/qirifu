import request from '@/utils/request'

export function getInfo() {
  return request({
    url: '/system/info',
    method: 'get'
  })
}
