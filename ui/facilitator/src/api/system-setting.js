import request from '@/utils/request'
import qs from 'qs'

export function fetchSystemInfo() {
  return request({
    url: '/system/setting/system-info',
    method: 'get'
  })
}

export function settingSystemInfo(data) {
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
  return request({
    url: '/system/setting/asbamboo-info',
    method: 'post',
    data: qs.stringify(data)
  })
}

export function fetchEtcInfo() {
  return request({
    url: '/system/setting/etc-info',
    method: 'get'
  })
}

export function settingEtcInfo(data) {
  return request({
    url: '/system/setting/etc-info',
    method: 'post',
    data: qs.stringify(data)
  })
}
