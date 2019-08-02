<template>
  <section class="app-main">
    <transition name="fade-transform" mode="out-in">
      <keep-alive :include="cachedViews">
        <router-view :key="key" />
      </keep-alive>
    </transition>
  </section>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'AppMain',
  mounted(){
    this.notifyListener()
  },
  computed: {
    cachedViews() {
      return this.$store.state.tagsView.cachedViews
    },
    key() {
      return this.$route.path
    },
    ...mapGetters([
      'unread_message_cnt'
    ])
  },
  methods: {
    notifyListener() {
      // 进入页面时如果有唯独消息那么弹出提示未读消息
      if(this.unread_message_cnt > 0){
        let message =
          '<a href="#/message/inbox">' +
            '您有<i>' + this.unread_message_cnt + '<i>条消息未读取，请及时查收。' +
          '</a>'

        this.$notify({
          type: 'info',
          title: '消息提醒',
          message: message,
          dangerouslyUseHTMLString: true,
          duration: false
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.app-main {
  /* 50= navbar  50  */
  min-height: calc(100vh - 50px);
  width: 100%;
  position: relative;
  overflow: hidden;
}

.fixed-header+.app-main {
  padding-top: 50px;
}

.hasTagsView {
  .app-main {
    /* 84 = navbar + tags-view = 50 + 34 */
    min-height: calc(100vh - 84px);
  }

  .fixed-header+.app-main {
    padding-top: 84px;
  }
}
</style>

<style lang="scss">
// fix css style bug in open el-dialog
.el-popup-parent--hidden {
  .fixed-header {
    padding-right: 15px;
  }
}
</style>
