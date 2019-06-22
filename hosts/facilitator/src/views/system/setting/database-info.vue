<template>
  <div class="app-container">
    <el-form ref="database-form"
      :model="database_info"
      class="form-container"
    >
      <el-row>
        <el-col :md="8">
          <el-form-item label-width="100px" label="主机名称:">
            <el-input
              v-model="database_info.host"
              type="text"
              name="database_host"
              placeholder="请输入数据库主机名称"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label-width="100px" label="端口:">
            <el-input
              v-model="database_info.port"
              type="text"
              name="database_port"
              placeholder="请输入数据库端口号"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label-width="100px" label="数据库名称:">
            <el-input
              v-model="database_info.database"
              type="text"
              name="database_name"
              placeholder="请输入数据库名称"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label-width="100px" label="账号:">
            <el-input
              v-model="database_info.username"
              type="text"
              name="database_username"
              placeholder="请输入数据库账号"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="8">
          <el-form-item label-width="100px" label="密码:">
            <el-input
              v-model="database_info.password"
              type="text"
              name="database_password"
              placeholder="请输入数据库密码"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :offset="3">
          <el-button type="primary" plain @click="submitForm">
            提交修改
          </el-button>
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import { fetchDatabaseInfo, settingDatabaseInfo } from '@/api/system-setting'

const database_info = {
  host: '',
  port: '',
  database: '',
  username: '',
  password: ''
};

export default {
  name: 'DatabaseInfoForm',
  data() {
    return {
      database_info: Object.assign({}, database_info)
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      fetchDatabaseInfo().then(response => {
        this.database_info = response.data
      }).catch(err => {
        console.log(err)
      })
    },
    submitForm() {
      settingDatabaseInfo(this.database_info).then(response => {
        this.$message({
          message: response.message
        })
      }).catch(err => {
        console.log(err)
      })
    }
  }
}
</script>
