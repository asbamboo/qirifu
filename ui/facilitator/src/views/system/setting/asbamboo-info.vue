<template>
  <div class="app-container">
    <el-form
      ref="asbamboo_form"
      :model="asbamboo_info"
      class="form-container"
    >
      <el-row>
        <el-col :md="8">
          <el-form-item label-width="75px" label="APP Key:">
            <el-input
              v-model="asbamboo_info.app_key"
              type="text"
              name="asbamboo_app_key"
              placeholder="请输入班步聚合平台app_key"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="12">
          <el-form-item label-width="75px" label="Security:">
            <el-input
              v-model="asbamboo_info.secret"
              type="text"
              name="asbamboo_secret"
              placeholder="请输入班步聚合平台secret"
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
import { fetchAsbambooInfo, settingAsbambooInfo } from '@/api/system-setting'

const asbamboo_info = {
  app_key: '',
  secret: ''
};

export default {
  name: 'AsbambooInfoForm',
  data() {
    return {
      asbamboo_info: Object.assign({}, asbamboo_info),
      ajax: false
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      if(this.ajax == true){
        return
      }
      this.ajax = true
      fetchAsbambooInfo().then(response => {
        this.asbamboo_info = response.data
        this.ajax = false
      }).catch(err => {
        console.log(err)
        this.ajax = false
      })
    },
    submitForm() {
      if(this.ajax == true){
        return
      }
      this.ajax = true
      settingAsbambooInfo(this.asbamboo_info).then(response => {
        this.ajax = false
        this.$message({
          message: response.message
        })
      }).catch(err => {
        this.ajax = false
        console.log(err)
      })
    }
  }
}
</script>
