<template>
  <div class="app-container">
    <div
      class="print-data-container"
      id="print_data_container"
      :style="containerStyle"
    >
      <div class="logo-container">
        <el-image :src="alipay_jpg"/>
        <el-image :src="wxpay_jpg"/>
      </div>
      <div class="qrcode-container">
        <canvas id="qrcode_canvas" v-bind:data="qrcode"></canvas>
      </div>
      <div class="faciltator-desc">
        <p>感谢{{ faciltator }}提供技术支持</p>
      </div>
    </div>
    <el-divider></el-divider>
    <router-link target="_blank" to="/window-print">
      <el-button type="primary" :disabled="ajax">打印</el-button>
    </router-link>
  </div>
</template>

<script>
import AlipayJpg from '@/assets/qrcode_images/alipay.jpg'
import WxpayJpg from '@/assets/qrcode_images/wxpay.jpg'
import BgJpg from '@/assets/qrcode_images/bg.jpg'
import QRCode from 'qrcode'
import { getQrcodeData } from '@/api/information-qrcode'

export default{
  name: 'InformationQrcode',
  data() {
    return {
      alipay_jpg: AlipayJpg,
      wxpay_jpg: WxpayJpg,
      containerStyle: {
        background: 'url(' + BgJpg + ')'
      },
      qrcode: '',
      faciltator: 'www.asbamboo.com',
      ajax: false
    }
  },
  created() {
    this.fetchData()
  },
  watch: {
    qrcode() {
      this.generateQrcodeCanvas()
    }
  },
  methods: {
    fetchData() {
      this.ajax = true
      getQrcodeData().then(response => {
        this.qrcode = response.data.qrcode
        if(response.data.faciltator){
          this.faciltator = response.data.faciltator
        }
        this.ajax = false
      }).catch(err => {
        console.log(err)
        this.ajax = false
      })
    },
    generateQrcodeCanvas() {
      QRCode.toCanvas(
        document.getElementById('qrcode_canvas'),
        this.qrcode,
        error => {
          console.log(error);
        }
      )
    }
  }
}
</script>

<style lang="scss" media="print">
.print-data-container{

  width: calc(210mm * 0.5);
  height: calc(290mm * 0.5);
  border: 1px solid #DCDFE6;

  .logo-container {

    text-align: center;
    background: #FFF;

    .el-image {
      width:49%;
    }
  }

  .qrcode-container {

    margin: 10%;
    text-align: center;

    #qrcode_canvas {
      width: 100% !important;
      height: 100% !important;
    }
  }

  .faciltator-desc{
    text-align: center;
    background: #FFF;
    margin: 0 10%;
    line-height: 2em;

    p {
      font-size: 3%;
      color: #303133;
    }
  }
}

</style>
