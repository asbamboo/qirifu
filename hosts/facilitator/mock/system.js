import Mock from 'mockjs'

export default [
  // system setting info
  {
    url: '/system/setting',
    type: 'get',
    response: config => {
      const system = {
    	name: '七日付'
      };
      const database = {
        host: '127.0.0.1',
        port: '3306',
        database: 'qirifu',
        username: 'root',
        password: 'root'
      };
      const asbamboo = {
    	app_key: '@string(13)',
    	secret: '@string(32)'
      };
      return {
        code: 20000,
        data: { system: system, database: database, asbamboo: asbamboo }
      }
    }
  }
]