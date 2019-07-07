<template>
  <div class="app-container"></div>
</template>

<script>
import QRCode from 'qrcode'

export default {
  name: 'PrintQrcode',
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

      this.$nextTick(() => {
        window.print()
      })
    }
  }
}
</script>

<style>
#app-container, #print_data_container {
  text-align: center;
}
#qrcode_canvas {
  width: 200mm !important;
  height:200mm !important;
}
</style>
