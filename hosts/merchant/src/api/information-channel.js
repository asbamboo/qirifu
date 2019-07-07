import request from '@/utils/request'

export function getChannelInfo() {
  return request({
    url: '/information/channel/get-info',
    method: 'get'
  })
}
