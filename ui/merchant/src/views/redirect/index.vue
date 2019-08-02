<script>
export default {
  created() {
    const { params, query } = this.$route
    const { path } = params
    if(path == ''){
      path = "/dashboard"
    }
    this.$router.replace({ path: '/' + path, query })
  },
  render: function(h) {
    return h() // avoid warning message
  }
}
</script>
