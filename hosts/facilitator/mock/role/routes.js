// Just a mock data

export const constantRoutes = [
  {
    path: '/redirect',
    component: 'layout/Layout',
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        component: 'views/redirect/index'
      }
    ]
  },
  {
    path: '/login',
    component: 'views/login/index',
    hidden: true
  },
  {
    path: '/auth-redirect',
    component: 'views/login/auth-redirect',
    hidden: true
  },
  {
    path: '/404',
    component: 'views/error-page/404',
    hidden: true
  },
  {
    path: '/401',
    component: 'views/error-page/401',
    hidden: true
  },
  {
    path: '',
    component: 'layout/Layout',
    redirect: 'dashboard',
    children: [
      {
        path: 'dashboard',
        component: 'views/dashboard/index',
        name: 'dashboard',
        meta: { title: '控制台', icon: 'dashboard', affix: true }
      }
    ]
  }
]

export const asyncRoutes = [
  {
    path: '/system',
    component: 'layout/Layout',
    redirect: 'noRedirect',
    name: 'SystemSetting',
    meta: { title: '系统设置', icon: 'edit' },
    children: [
      // {
      //   path: 'setting',
      //   component: 'views/system/setting',
      //   name: 'system_setting',
      //   meta: { title: '系统设置', icon: 'edit' }
      // },
      {
        path: 'setting/system-info',
        component: '/views/system/setting/system-info',
        name: 'SystemSettingSystemInfo',
        meta: { title: '系统信息', icon: 'edit' }
      },
      {
        path: 'setting/database-info',
        component: '/views/system/setting/databse-info',
        name: 'SystemSettingDatabaseInfo',
        meta: { title: '数据库', icon: 'edit' }
      },
      {
        path: 'setting/asbamboo-info',
        component: '/views/system/setting/asbamboo-info',
        name: 'SystemSettingAsbambooInfo',
        meta: { title: '班布聚合', icon: 'edit' }
      }
    ]
  },

  { path: '*', redirect: '/404', hidden: true }
]
