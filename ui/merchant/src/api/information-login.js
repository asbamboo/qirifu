import request from '@/utils/request'
import qs from 'qs'

export function getData() {
  return request({
    url: '/account/info',
    method: 'get'
  })
}

export function settingAccount(data) {
  return request({
    url: '/account/setting-account',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function settingEmail(data) {
  return request({
    url: '/account/setting-email',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function settingPhone(data) {
  return request({
    url: '/account/setting-phone',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function resetPassword(data) {
  return request({
    url: '/account/reset-password',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function sendCaptcha(data) {
  return request({
    url: '/account/send-captcha',
    method: 'post',
    data: qs.stringify(data)
  })
}
