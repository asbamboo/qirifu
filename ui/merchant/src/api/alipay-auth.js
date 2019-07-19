import request from '@/utils/request'
import qs from 'qs'

export function doAuthorization(data) {
  return request({
    url: '/alipay/auth',
    method: 'post',
    data: qs.stringify(data)
  })
}
