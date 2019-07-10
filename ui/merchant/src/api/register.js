import request from '@/utils/request'
import qs from 'qs'

export function sendCaptcha(data) {
  return request({
    url: '/register/send-captcha',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function register(data) {
  return request({
    url: '/register',
    method: 'post',
    data: qs.stringify(data)
  })
}
