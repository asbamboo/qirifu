import request from '@/utils/request'

export function fetchSystemInfo() {
  return request({
    url: '/system/setting/system-info',
    method: 'get'
  })
}

export function settingSystemInfo(data) {
  let qs = require('qs');
  return request({
    url: '/system/setting/system-info',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function fetchDatabaseInfo() {
  return request({
    url: '/system/setting/database-info',
    method: 'get'
  })
}

export function settingDatabaseInfo(data) {
  let qs = require('qs');
  return request({
    url: '/system/setting/database-info',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function fetchEmailInfo() {
  return request({
    url: '/system/setting/email-info',
    method: 'get'
  })
}

export function settingEmailInfo(data) {
  let qs = require('qs');
  return request({
    url: '/system/setting/email-info',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function fetchAsbambooInfo() {
  return request({
    url: '/system/setting/asbamboo-info',
    method: 'get'
  })
}

export function settingAsbambooInfo(data) {
  let qs = require('qs');
  return request({
    url: '/system/setting/asbamboo-info',
    method: 'post',
    data: qs.stringify(data)
  })
}
