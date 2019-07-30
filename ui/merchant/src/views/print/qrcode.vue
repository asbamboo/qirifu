<template>
  <div class="app-container" v-loading.fullscreen.lock="fullscreenLoading" ></div>
</template>

<script>
import QRCode from 'qrcode'

export default {
  name: 'PrintQrcode',
  data() {
    return {
      fullscreenLoading: true
    }
  },
  mounted() {
    this.createPrintDocument()
  },
  methods: {
    createPrintDocument() {
      let data = opener.document.getElementById(
        'print_data_container'
      ).cloneNode(true)

      console.log(data)

      document.querySelector('.app-container').appendChild(data)

      let qrcode = document.getElementById('qrcode_canvas').getAttribute('data')

      QRCode.toCanvas(
        document.getElementById('qrcode_canvas'),
        qrcode,
        error => {
          console.log(error);
        }
      )

      let _this = this
      setTimeout(function(){
        _this.fullscreenLoading = false
        _this.$nextTick(() => {
          window.print()
        })
      },3000)
    }
  }
}
</script>

<style lang="scss" media="print">
.print-data-container{

  width: 210mm;
  height: 290mm;
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
    line-height: 3em;

    p {
      font-size: 14px;
      color: #303133;
    }
  }
}

</style>
