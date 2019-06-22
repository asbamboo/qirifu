<template>
  <div class="app-container">
    <el-tabs v-model="active_tab">
      <el-tab-pane label="基本设置" name="system_info">
        <system-info-form />
      </el-tab-pane>
      <el-tab-pane label="数据库" name="database_info">
        <database-info-form />
      </el-tab-pane>
      <el-tab-pane label="班布聚合" name="asbamboo_info">
        <asbamboo-info-form />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>

import SystemInfoForm from './setting/system-info'
import DatabaseInfoForm from './setting/database-info'
import AsbambooInfoForm from './setting/asbamboo-info'


export default {
  name: 'SystemSetting',
  components: { SystemInfoForm, DatabaseInfoForm, AsbambooInfoForm },
  data() {
    return {
      active_tab: 'system_info'
    }
  }
}
</script>
