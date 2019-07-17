<template>
  <div class="app-container">
    <el-form label-width="130px" label-position="right">
      <el-collapse v-model="active_collapse">
        <el-collapse-item name="collapse-item-com">
          <template slot="title"><div><h4>企业/个体工商户</h4></div></template>
          <el-form-item label="简称">
            <el-input
              v-model="merchant.name"
              type="text"
              placeholder="请输入简称"
            />
          </el-form-item>
          <el-form-item label="全称">
            <el-input
              v-model="merchant.fullname"
              type="text"
              placeholder="请输入全称"
            />
          </el-form-item>
          <el-form-item label="行业">
            <el-input
              v-model="merchant.profession"
              type="text"
              placeholder="请输入行业"
            />
          </el-form-item>
          <el-form-item label="经营类目">
            <el-input
              v-model="merchant.businecate"
              type="text"
              placeholder="请输入经营类目"
            />
          </el-form-item>
          <el-form-item label="统一社会信用代码">
            <el-input
              v-model="merchant.code"
              type="text"
              placeholder="请输入统一社会信用代码"
            />
          </el-form-item>
          <el-form-item label="注册地址">
            <el-input
              v-model="merchant.address_register"
              type="text"
              placeholder="请输入注册地址"
            />
          </el-form-item>
          <el-form-item label="实际经营地址">
            <el-input
              v-model="merchant.address_actual"
              type="text"
              placeholder="请输入实际经营地址"
            />
          </el-form-item>
        </el-collapse-item>
        <el-collapse-item name="collapse-item-linkman">
          <template slot="title"><div><h4>联系人</h4></div></template>
          <el-form-item label="姓名">
            <el-input
              v-model="merchant.link_man"
              type="text"
              placeholder="请输入姓名"
            />
          </el-form-item>
          <el-form-item label="联系电话">
            <el-input
              v-model="merchant.link_phone"
              type="text"
              placeholder="请输入联系电话"
            />
          </el-form-item>
          <el-form-item label="email">
            <el-input
              v-model="merchant.link_email"
              type="text"
              placeholder="请输入email"
            />
          </el-form-item>
        </el-collapse-item>
        <el-collapse-item name="collapse-item-legalman">
          <template slot="title"><div><h4>法定代表人</h4></div></template>
          <el-form-item label="证件类型">
            <el-input
              v-model="merchant.legal_id_type"
              type="text"
              placeholder="请输入证件类型"
            />
          </el-form-item>
          <el-form-item label="证件号码">
            <el-input
              v-model="merchant.legal_id_no"
              type="text"
              placeholder="请输入证件号码"
            />
          </el-form-item>
          <el-form-item label="证件有效期">
            <el-input
              v-model="merchant.legal_id_indate"
              type="text"
              placeholder="请输入证件有效期"
            />
          </el-form-item>
        </el-collapse-item>
        <el-collapse-item name="collapse-item-bank">
          <template slot="title"><div><h4>结算账户</h4></div></template>
          <el-form-item label="账户类型">
            <el-input
              v-model="merchant.bank_account_type"
              type="text"
              placeholder="请输入账户类型"
            />
          </el-form-item>
          <el-form-item label="开户银行">
            <el-input
              v-model="merchant.bank_name"
              type="text"
              placeholder="请输入开户银行"
            />
          </el-form-item>
          <el-form-item label="开户名称">
            <el-input
              v-model="merchant.bank_account_name"
              type="text"
              placeholder="请输入开户名称"
            />
          </el-form-item>
          <el-form-item label="结算账号">
            <el-input
              v-model="merchant.bank_account_no"
              type="text"
              placeholder="请输入结算账号"
            />
          </el-form-item>
        </el-collapse-item>
        <el-collapse-item name="collapse-item-files">
          <template slot="title"><div><h4>上传资料</h4></div></template>
          <el-upload
            :action="file_upload_url"
            list-type="picture"
            :file-list="merchant.files"
            :on-preview="handleFilePreview"
            :on-success="handleFileUploaded"
            :on-remove="handleFileRemove"
            multiple
            drag
          >
            <i class="el-icon-upload"></i>
            <div class="el-upload-text">将文件拖到此处，或<em>点击上传</em></div>
            <div class="el-upload-tip" slot="tip">{{ upfiles_desc }}</div>
          </el-upload>
          <el-dialog :visible.sync="file_dialog_visible">
            <img width="100%" :src="file_dialog_url" />
          </el-dialog>
        </el-collapse-item>
      </el-collapse>
      <template v-if="editable">
        <el-divider></el-divider>
        <el-row>
          <el-col :md="4" :offset="8">
            <el-button
              :disabled="ajax"
              @click="doSubmit"
              type="primary"
            >保存</el-button>
          </el-col>
        </el-row>
      </template>
    </el-form>
  </div>
</template>

<script>
import { saveMerchantInfo, getMerchantInfo } from '@/api/information-merchant'

const merchant = {
  link_man: '',
  link_phone: '',
  link_email: '',
  name: '',
  fullname: '',
  profession: '',
  businecate: '',
  code: '',
  address_register: '',
  address_actual: '',
  legal_id_type: '身份证',
  legal_id_no: '',
  legal_id_indate: '',
  bank_account_type: '对公账户',
  bank_name: '',
  bank_account_name: '',
  bank_account_no: '',
  files: [/*fileid,name,url*/]
}

export default {
  name: 'informationMerchant',
  data() {
    return {
      merchant: Object.assign({}, merchant),
      upfiles_desc: '只能上传图片。',
      file_dialog_visible: false,
      file_dialog_url: '',
      file_upload_url: process.env.VUE_APP_UPLOAD_URL,
      editable: false,
      ajax: false,
      active_collapse: [
        'collapse-item-com',
        'collapse-item-linkman',
        'collapse-item-legalman',
        'collapse-item-bank',
        'collapse-item-files'
      ]
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    handleFilePreview(file) {
      this.file_dialog_url = file.url
      this.file_dialog_visible = true
    },
    handleFileUploaded(response, file, file_list) {
      this.merchant.files = file_list
      for(var i in this.merchant.files){
        if(!this.merchant.files[i].fileid && this.merchant.files[i].response){
          let fileid  = this.merchant.files[i].response.fileid;
          let url = this.merchant.files[i].response.url;
          let name = this.merchant.files[i].response.name;
          this.merchant.files[i].fileid = fileid;
          this.merchant.files[i].url = url;
          this.merchant.files[i].name = name;
        }
      }
      console.log(this.merchant.files);
    },
    handleFileRemove(file, file_list){
      console.log("preremove:", this.merchant.files);
      this.merchant.files = file_list
      console.log("afterremove:", this.merchant.files);
    },
    fetchData() {
      this.ajax = true
      getMerchantInfo().then(response => {
        this.merchant = response.data
        this.editable = response.editable
        this.ajax = false
      }).catch(err => {
        console.log(err)
        this.ajax = false
      })

    },
    doSubmit() {
      this.ajax = true
      saveMerchantInfo(this.merchant).then(response => {
        this.$message({
          message: response.message,
          showClose: true
        })
        this.ajax = false
      }).catch(err => {
        this.ajax = false
      })
    }
  }
}
</script>

<style>
.el-upload--picture,
.el-upload-dragger {
  width: 100%;
}

.el-upload-list__item {
  float: left;
  max-width: 280px;
}

.el-upload-text {
  position: absolute;
  bottom: 15px;
  left: -50%;
  right: -50%;
}
</style>
