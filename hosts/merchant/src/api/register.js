import request from '@/utils/request'

export function sendCaptcha(data) {
  let qs = require('qs');
  return request({
    url: '/register/send-captcha',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function register(data) {
  let qs = require('qs');
  return request({
    url: '/register',
    method: 'post',
    data: qs.stringify(data)
  })
}
