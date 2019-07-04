import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if set true, the page will no be cached(default is false)
    affix: true                  if set true, the tag will affix in the tags-view
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        component: () => import('@/views/redirect/index')
      }
    ]
  },
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },
  {
    path: '/register',
    component: () => import('@/views/register/index'),
    hidden: true
  },
  {
    path: '/auth-redirect',
    component: () => import('@/views/login/auth-redirect'),
    hidden: true
  },
  {
    path: '/404',
    component: () => import('@/views/error-page/404'),
    hidden: true
  },
  {
    path: '/401',
    component: () => import('@/views/error-page/401'),
    hidden: true
  },
  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index'),
        name: '控制台',
        meta: { title: '控制台', icon: 'dashboard', affix: true }
      }
    ]
  }
]

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [

  {
    path: '/information',
    component: Layout,
    redirect: 'noRedirect',
    name: 'information',
    alwaysShow: true,
    meta: { title: '信息中心', icon: 'edit' },
    children: [
      {
        path: 'login',
        component: () => import('@/views/information/login'),
        name: 'informationLogin',
        meta: { title: '账号信息', icon: 'edit' }
      },
      {
        path: 'merchant',
        component: () => import('@/views/information/merchant'),
        name: 'informationMerchant',
        meta: { title: '商户资料', icon: 'edit' }
      },
      {
        path: 'qrcode',
        component: () => import('@/views/information/qrcode'),
        name: 'informationQrcode',
        meta: { title: '二维码', icon: 'edit' }
      },
      {
        path: 'channel',
        component: () => import('@/views/information/channel'),
        name: 'informationChannel',
        meta: { title: '支付通道', icon: 'edit' }
      }
    ]
  },

  {
    path: '/trade',
    component: Layout,
    redirect: 'noRedirect',
    name: 'trade',
    meta: { title: '交易管理', icon: 'edit' },
    children: [
      {
        path: 'list',
        component: () => import('@/views/trade/list'),
        name: 'tradeList',
        meta: { title: '交易列表', icon: 'edit' }
      }
    ]
  },

  {
    path: '/message',
    component: Layout,
    redirect: 'noRedirect',
    name: 'message',
    meta: { title: '消息管理', icon: 'edit' },
    children: [
      {
        path: 'inbox',
        component: () => import('@/views/message/inbox'),
        name: 'messageInbox',
        meta: { title: '收到的消息', icon: 'edit' }
      }
    ]
  },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
