import Mock from 'mockjs'

export default [
  {
    url: '/system/setting/system-info',
    type: 'get',
    response: config => {
      return {
        status: 'success',
        data: {
          name: '七日付',
          user: 'admin',
          password: 'password'
        }
      }
    }
  },

  {
    url: '/system/setting/system-info',
    type: 'post',
    response: config => {
      let status = 'success'
      let message = '成功'
      let system_info = config.body
      if( system_info.name == ''){
        status = 'failed'
        message = '请输入系统名称。'
      }
      return {
        status: status,
        message: message
      }
    }
  },

  {
    url: '/system/setting/database-info',
    type: 'get',
    response: config => {
      return {
        status: 'success',
        data: {
          host: '127.0.0.1',
          port: '3306',
          database: 'qirifu',
          username: 'root',
          password: 'root',
          charset: 'utf8mb4'
        }
      }
    }
  },

  {
    url: '/system/setting/database-info',
    type: 'post',
    response: config => {
      let status = 'success'
      let message = '成功'
      let database_info = config.body
      if( database_info.host == ''){
        status = 'failed'
        message = '请输入数据库主机地址。'
      }
      if( database_info.port == ''){
        status = 'failed'
        message = '请输入数据库端口。'
      }
      if( database_info.database == ''){
        status = 'failed'
        message = '请输入数据库名称。'
      }
      if( database_info.username == ''){
        status = 'failed'
        message = '请输入数据库用户名。'
      }
      if( database_info.password == ''){
        status = 'failed'
        message = '请输入数据库密码。'
      }
      return {
        status: status,
        message: message
      }
    }
  },

  {
    url: '/system/setting/email-info',
    type: 'get',
    response: config => {
      return {
        status: 'success',
        data: {
          "host":'@domain',
          "port":'@natural(99, 9000)',
          "encryption":"ssl",
          "user":"qirifu",
          "password":"qirifu_password"
        }
      }
    }
  },

  {
    url: '/system/setting/email-info',
    type: 'post',
    response: config => {
      let status = 'success'
      let message = '成功'
      let email_info = config.body
      if( email_info.host == ''){
        status = 'failed'
        message = '请输入email host。'
      }
      if( email_info.port == ''){
        status = 'failed'
        message = '请输入email port。'
      }
      if( email_info.encryption == ''){
        status = 'failed'
        message = '请输入email encryption。'
      }
      if( email_info.user == ''){
        status = 'failed'
        message = '请输入email账号。'
      }
      if( email_info.password == ''){
        status = 'failed'
        message = '请输入email密码。'
      }
      return {
        status: status,
        message: message
      }
    }
  },

  {
    url: '/system/setting/asbamboo-info',
    type: 'get',
    response: config => {
      return {
        status: 'success',
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
      let status = 'success'
      let message = '成功'
      let asbamboo_info = config.body
      if( asbamboo_info.app_key == ''){
        status = 'failed'
        message = '请输入app_key。'
      }
      if( asbamboo_info.secret == ''){
        status = 'failed'
        message = '请输入secret。'
      }
      return {
        status: status,
        message: message
      }
    }
  }
]
