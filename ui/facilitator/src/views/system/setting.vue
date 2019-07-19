<template>
  <div class="app-container">
    <el-tabs v-model="active_tab">
      <el-tab-pane label="基本设置" name="system_info" lazy>
        <system-info-form />
      </el-tab-pane>
      <el-tab-pane label="数据库" name="database_info" lazy>
        <database-info-form />
      </el-tab-pane>
      <el-tab-pane label="Email服务" name="email_info" lazy>
        <email-info-form />
      </el-tab-pane>
      <el-tab-pane label="班布聚合" name="asbamboo_info" lazy>
        <asbamboo-info-form />
      </el-tab-pane>
      <el-tab-pane label="其他" name="etc_info" lazy>
        <etc-info-form />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>

import SystemInfoForm from './setting/system-info'
import DatabaseInfoForm from './setting/database-info'
import AsbambooInfoForm from './setting/asbamboo-info'
import EmailInfoForm from './setting/email-info'
import EtcInfoForm from './setting/etc-info'

export default {
  name: 'SystemSetting',
  components: {
    SystemInfoForm,
    DatabaseInfoForm,
    AsbambooInfoForm,
    EmailInfoForm,
    EtcInfoForm
  },
  data() {
    return {
      active_tab: 'system_info'
    }
  }
}
</script>
