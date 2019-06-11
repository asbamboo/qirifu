<template>
  <div class="createPost-container">
    <div class="createPost-main-container">
      <el-tabs v-model="active_name" @tab-click="handleClick">
        <el-tab-pane label="基本设置" name="system_info">
          <el-form ref="system_form" :model="system_form" :rules="rules" class="form-container">
            <el-row>
              <el-col :md="8">
                <el-form-item label-width="100px" label="系统名称:">
                  <el-input v-model="system_form.name" type="text" name="system_name" placeholder="请输入系统名称" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row><el-col :offset="3"><el-button type="primary" plain>提交修改</el-button></el-col></el-row>
          </el-form>
        </el-tab-pane>
        <el-tab-pane label="数据库" name="database_info">
          <el-form ref="database_form" :model="database_form" :rules="rules" class="form-container">
            <el-row>
              <el-col :md="8">
                <el-form-item label-width="100px" label="主机名称:">
                  <el-input v-model="database_form.host" type="text" name="database_host" placeholder="请输入数据库主机名称" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :md="8">
                <el-form-item label-width="100px" label="端口:">
                  <el-input v-model="database_form.port" type="text" name="database_port" placeholder="请输入数据库端口号" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :md="8">
                <el-form-item label-width="100px" label="数据库名称:">
                  <el-input v-model="database_form.database" type="text" name="database_name" placeholder="请输入数据库名称" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :md="8">
                <el-form-item label-width="100px" label="账号:">
                  <el-input v-model="database_form.username" type="text" name="database_username" placeholder="请输入数据库账号" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :md="8">
                <el-form-item label-width="100px" label="密码:">
                  <el-input v-model="database_form.password" type="text" name="database_password" placeholder="请输入数据库密码" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row><el-col :offset="3"><el-button type="primary" plain>提交修改</el-button></el-col></el-row>
          </el-form>
        </el-tab-pane>
        <el-tab-pane label="班布聚合" name="asbamboo_info">
          <el-form ref="asbamboo_form" :model="asbamboo_form" :rules="rules" class="form-container">
            <el-row>
              <el-col :md="8">
                <el-form-item label-width="75px" label="APP Key:">
                  <el-input v-model="asbamboo_form.app_key" type="text" name="asbamboo_app_key" placeholder="请输入班步聚合平台app_key" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-col :md="12">
                <el-form-item label-width="75px" label="Security:">
                  <el-input v-model="asbamboo_form.secret" type="text" name="asbamboo_secret" placeholder="请输入班步聚合平台security" />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row><el-col :offset="3"><el-button type="primary" plain>提交修改</el-button></el-col></el-row>
          </el-form>
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script>

import { fetchSettingInfo } from '@/api/system'

const system_form = {
  name: '七日付'
};
const database_form = {
  host: '127.0.0.1',
  port: '3306',
  database: 'qirifu',
  username: 'root',
  password: 'root'
};
const asbamboo_form = {
  app_key: '',
  secret: ''
};

export default {
  name: 'system_setting',
  data() {
    return {
      system_form: Object.assign({}, system_form),
      database_form: Object.assign({}, database_form),
      asbamboo_form: Object.assign({}, asbamboo_form),
      loading: false,
      active_name: 'system_info',
      tempRoute: {}
    }
  },
  created() {
    this.fetchData()
    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    // https://github.com/PanJiaChen/vue-element-admin/issues/1221
    this.tempRoute = Object.assign({}, this.$route)
  },
  methods: {
    fetchData() {
      fetchSettingInfo().then(response => {
        this.system_form = Object.assign({}, response.data.system)
        this.database_form = Object.assign({}, response.data.database)
        this.asbamboo_form = Object.assign({}, response.data.asbamboo)
      }).catch(err => {
        console.log(err)
      })
    },
  }
}
</script>

<style lang="scss" scoped>
@import "~@/styles/mixin.scss";

.createPost-container {
  position: relative;

  .createPost-main-container {
    padding: 40px 45px 20px 50px;

    .postInfo-container {
      position: relative;
      @include clearfix;
      margin-bottom: 10px;

      .postInfo-container-item {
        float: left;
      }
    }
  }

  .word-counter {
    width: 40px;
    position: absolute;
    right: 10px;
    top: 0px;
  }
}

.article-textarea /deep/ {
  textarea {
    padding-right: 40px;
    resize: none;
    border: none;
    border-radius: 0px;
    border-bottom: 1px solid #bfcbd9;
  }
}
</style>
