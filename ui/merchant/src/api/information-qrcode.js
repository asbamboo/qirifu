import request from '@/utils/request'

export function getQrcodeData() {
  return request({
    url: '/qrcode/get-data',
    method: 'get'
  })
}
