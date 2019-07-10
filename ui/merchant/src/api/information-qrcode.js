import request from '@/utils/request'

export function getQrcodeData() {
  return request({
    url: '/information/qrcode/get-data',
    method: 'get'
  })
}
