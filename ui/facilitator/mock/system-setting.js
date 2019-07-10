import Mock from 'mockjs'

export default [
  {
    url: '/system/setting/system-info',
    type: 'get',
    response: config => {
      return {
        code: 20000,
        data: {
          name: '七日付'
        }
      }
    }
  },

  {
    url: '/system/setting/system-info',
    type: 'post',
    response: config => {
      let code = 20000
      let message = '成功'
      let system_info = config.body
      if( system_info.name == ''){
        code = 500
        message = '请输入系统名称。'
      }
      return {
        code: code,
        message: message
      }
    }
  },

  {
    url: '/system/setting/database-info',
    type: 'get',
    response: config => {
      return {
        code: 20000,
        data: {
          host: '127.0.0.1',
          port: '3306',
          database: 'qirifu',
          username: 'root',
          password: 'root'
        }
      }
    }
  },

  {
    url: '/system/setting/database-info',
    type: 'post',
    response: config => {
      let code = 20000
      let message = '成功'
      let database_info = config.body
      if( database_info.host == ''){
        code = 500
        message = '请输入数据库主机地址。'
      }
      if( database_info.port == ''){
        code = 500
        message = '请输入数据库端口。'
      }
      if( database_info.database == ''){
        code = 500
        message = '请输入数据库名称。'
      }
      if( database_info.username == ''){
        code = 500
        message = '请输入数据库用户名。'
      }
      if( database_info.password == ''){
        code = 500
        message = '请输入数据库密码。'
      }
      return {
        code: code,
        message: message
      }
    }
  },

  {
    url: '/system/setting/asbamboo-info',
    type: 'get',
    response: config => {
      return {
        code: 20000,
        data: {
          app_key: '@string(abcdefghijklmnopqrstuvwxyz01234567890, 13)',
          secret: '@string(abcdefghijklmnopqrstuvwxyz01234567890, 32)'
        }
      }
    }
  },

  {
    url: '/system/setting/asbamboo-info',
    type: 'post',
    response: config => {
      let code = 20000
      let message = '成功'
      let asbamboo_info = config.body
      if( asbamboo_info.app_key == ''){
        code = 500
        message = '请输入app_key。'
      }
      if( asbamboo_info.secret == ''){
        code = 500
        message = '请输入secret。'
      }
      return {
        code: code,
        message: message
      }
    }
  }
]