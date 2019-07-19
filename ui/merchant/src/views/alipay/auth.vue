<script>
import { doAuthorization } from '@/api/alipay-auth'

export default {
  name: 'AlipayAuth',
  created() {
    doAuthorization(this.$route.query).then(response=>{
      this.$message({
        message: response.message,
        showClose: true
      })
      this.$router.push('/information/channel');
    }).catch(err => {
      console.log(err)
    });
  }
}
</script>
