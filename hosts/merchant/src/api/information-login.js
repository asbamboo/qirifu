import request from '@/utils/request'

export function getData() {
  return request({
    url: '/information/login/info',
    method: 'get'
  })
}
export function settingAccount(data) {
  let qs = require('qs');
  return request({
    url: '/information/login/setting-account',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function settingEmail(data) {
  let qs = require('qs');
  return request({
    url: '/information/login/setting-email',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function settingPhone(data) {
  let qs = require('qs');
  return request({
    url: '/information/login/setting-phone',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function resetPassword(data) {
  let qs = require('qs');
  return request({
    url: '/information/login/reset-password',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function sendCaptcha(data) {
  let qs = require('qs');
  return request({
    url: '/information/login/send-captcha',
    method: 'post',
    data: qs.stringify(data)
  })
}
