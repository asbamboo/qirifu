<template>
  <div class="app-container index" :style="background_style">
    <div class="index-main">
      <div class="index-logo">
        <img :src="images.logo"><span>{{system_info.name}}</span>
      </div>
      <el-row justify="center" align="middle" type="flex" class="index-container">
        <el-col :md="24">
          <div class="index-title">
            <div class="index-qrcode">
              <img :src="images.qrcode" style="max-width: 150px">
            </div>
            <el-divider content-position="left">
              {{system_info.name}}(扫码体验)
            </el-divider>
          </div>
          <div class="index-content">使用支付宝与微信官方通道（官方标准费率）</div>
          <div class="index-content">一码付收款系统</div>
          <div class="index-actions">
            <el-button type="info" round>操作手册</el-button>
            <router-link :to="'/login'">
              <el-button round>商户登录</el-button>
            </router-link>
            <router-link :to="'/register'">
              <el-button type="primary" round>免费开通</el-button>
            </router-link>
            <a href="https://github.com/asbamboo/qirifu">
              <el-button round>开源GITHUB</el-button>
            </a>
          </div>
        </el-col>
      </el-row>
    </div>
    <div class="index-bg-footer">
      <img :src="images.footer">
    </div>
    <div class="index-extra">
      <div class="info-menu">
        <div class="info-menu-sign-in">
          <el-avatar icon="el-icon-user" size="small" class="sign-avatar" />
          <el-link href="#/login" type="text" class="sign-button">
            商户登录
          </el-link>
        </div>
        <div class="info-menu-add">
          <el-dropdown trigger="click">
            <span class="el-dropdown-link">
              <i class="el-icon-plus"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <router-link :to="'/register'">
                <el-dropdown-item>商户注册</el-dropdown-item>
              </router-link>
              <el-dropdown-item>操作手册</el-dropdown-item>
              <el-dropdown-item>
                <a href="https://github.com/asbamboo/qirifu">开源GITHUB</a>
              </el-dropdown-item>
              <el-dropdown-item divided>
                <a :href="system_info.admin_base_url">
                  服务商入口
                </a>
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LogoPng from '@/assets/logo_images/logo.png'
import QrcodePng from '@/assets/index_images/qrcode.png'
import FooterPng from '@/assets/index_images/footer.png'
import BackgroundJpg from '@/assets/index_images/background.jpg'
import { getInfo } from '@/api/system'

const system_info = {
  name: '七日付',
  admin_base_url: location.origin + location.pathname.replace('/www/', '/admin/'),
}

export default {
  name: 'HomePage',
  data(){
    return {
      system_info: Object.assign({}, system_info),
      background_style:{
        'background': 'url(' + BackgroundJpg + ')',
        'background-color': '#fff',
        'background-repeat': 'no-repeat',
        'background-size': 'cover',
        'background-position': '50%'
      },
      images: {
        logo: LogoPng,
        qrcode: QrcodePng,
        footer: FooterPng
      }
    }
  },
  created(){
    this.doGetSystemInfo()
  },
  methods:{
    doGetSystemInfo() {
      console.log(process.env.VUE_APP_SYSTEM_INFO_IS_FROM_API)
      if(process.env.VUE_APP_SYSTEM_INFO_IS_FROM_API) {
        getInfo().then(response => {
          this.system_info = response.data
        }).catch(err => {
          console.log(err)
        })
      }
    }
  }
}

</script>

<style lang="scss">
.el-row--flex {
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  flex-wrap: wrap;
  flex-direction: row;
}
.index {

  width: 100%;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  overflow: hidden;
  color: #515a6e;
  line-height: 1.5;

  .index-main {
    width: 50%;
    height: 100%;

    .index-logo {

      vertical-align: middle;
      font-size: 30px;
      font-weight: bolder;

      img {
        height: 30px;
      }
    }

    .index-container {

      height: 100%;
      padding-left: 10%;

      .index-title {

        .el-divider {
          height: 3px;
          background: blue;
          width: 200px;
        }
      }

      .index-content {
        font-size: 24px;
        margin-top: 10px;
        padding-left: 16px;
      }

      .index-actions {
        margin-top: 80px;
        margin-bottom: 150px;
        margin-left: 16px;
      }
    }
  }

  .index-bg-footer {
    width: 260px;
    height: 90px;
    position: absolute;
    bottom: 0;
    left: 0;
  }

  .index-extra {

    position: absolute;
    top: 16px;
    right: 16px;

    .info-menu {
      display: inline-block;
      margin-left: 32px;

      .info-menu-sign-in {
        cursor: pointer;
        display: inline-block;
        margin-right: 8px;

        .sign-avatar {
          background-color: #87d068;
        }

        .sign-button {
          color: #ffffff;
          padding-bottom: 20px;
        }
      }

      .info-menu-add {

        display: inline-block;
        color: #ffffff;

        .el-dropdown {
          color: #ffffff;
          font-size: 24px;
          bottom: 5px;
          cursor: pointer;
        }
      }
    }
  }
}
</style>
