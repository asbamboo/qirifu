import request from '@/utils/request'
import qs from 'qs'

export function getData() {
  return request({
    url: '/information/login/info',
    method: 'get'
  })
}

export function settingAccount(data) {
  return request({
    url: '/information/login/setting-account',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function settingEmail(data) {
  return request({
    url: '/information/login/setting-email',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function settingPhone(data) {
  return request({
    url: '/information/login/setting-phone',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function resetPassword(data) {
  return request({
    url: '/information/login/reset-password',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function sendCaptcha(data) {
  return request({
    url: '/information/login/send-captcha',
    method: 'post',
    data: qs.stringify(data)
  })
}
