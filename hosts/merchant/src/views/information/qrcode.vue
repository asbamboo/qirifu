<template>
  <div class="app-container">
      <div id="print_data_container">
        <canvas id="qrcode_canvas" v-bind:data="qrcode"></canvas>
      </div>
      <el-divider></el-divider>
      <router-link target="_blank" to="/window-print">
        <el-button type="primary" :disabled="ajax">打印</el-button>
      </router-link>
  </div>
</template>

<script>
import QRCode from 'qrcode'
import { getQrcodeData } from '@/api/information-qrcode'

export default{
  name: 'InformationQrcode',
  data() {
    return {
      qrcode: '',
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

<style>
#qrcode_canvas {
  width: 100mm !important;
  height:100mm !important;
}
</style>
