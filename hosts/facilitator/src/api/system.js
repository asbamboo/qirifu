import request from '@/utils/request'


export function fetchSettingInfo() {
  return request({
    url: '/system/setting',
    method: 'get'
  })
}