<template>
  <div class="app-container">
    <el-form label-width="180px" label-position="right">
      <el-row :gutter="20">
        <el-col :md="14">
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>上传资料</span>
            </div>
            <ol>
              <li>营业执照（必须）</li>
              <li>法人身份证正反面（必须）</li>
              <li>组织机构代码证：个体工商户或者已三证合一，无需提供</li>
              <li>线下门店提供店铺门头照片</li>
              <li>以及其他经营活动相关的照片</li>
            </ol>
            <div>
              <el-upload
                :action="file_upload_url"
                list-type="picture"
                :file-list="merchant.files"
                :on-preview="handleFilePreview"
                :on-success="handleFileUploaded"
                :on-remove="handleFileRemove"
                accept="image/*"
                multiple
                :limit="10"
                drag
              >
                <i class="el-icon-upload"></i>
                <div class="el-upload-text">将文件拖到此处，或<em>点击上传</em></div>
                <div class="el-upload-tip" slot="tip">{{ upfiles_desc }}</div>
              </el-upload>
              <el-dialog :visible.sync="file_dialog_visible">
                <img width="100%" :src="file_dialog_url" />
              </el-dialog>
            </div>
          </el-card>
        </el-col>

        <el-col :md="10">
          <el-collapse v-model="active_collapse">
            <el-collapse-item name="collapse-item-com">
              <template slot="title"><div><h4>企业/个体工商户</h4></div></template>
              <el-tooltip
                content="商户简称会在用户扫码支付页面展示"
                placement="top"
                effect="light"
              >
                <el-form-item label="(必填)商户简称">
                  <el-input
                    v-model="merchant.name"
                    type="text"
                    placeholder="请输入商户简称"
                    required="true"
                  />
                </el-form-item>
              </el-tooltip>
              <el-tooltip
                placement="top"
                effect="light"
              >
                <div slot="content">
                  请根据腾讯经营类目说明表填写一级行业和二级行业。
                  <br/>
                  <a
                    href="https://kf.qq.com/faq/140225MveaUz1506122ueYnE.html"
                    style="color:red;font-weight:bolder;"
                  >
                    微信经营类目说明
                  </a>
                  <br/>
                  <a
                    href="https://kf.qq.com/faq/140225MveaUz1501077rEfqI.html"
                    style="color:red;font-weight:bolder;"
                  >
                    微信经营类目对应的费率与结算周期
                  </a>
                </div>
                <el-form-item label="(必填)微信经营类目">
                  <el-input
                    v-model="merchant.wxpay_businecate"
                    type="text"
                    placeholder="请输入经营类目"
                  />
                </el-form-item>
              </el-tooltip>
              <el-tooltip
                placement="top"
                effect="light"
              >
                <div slot="content">
                  需要法人（个人或企业）实名认证通过的支付宝账号。
                  <br/>
                  <a
                    href="https://memberprod.alipay.com/account/reg/index.htm"
                    style="color:red;font-weight:bolder;"
                  >
                    支付宝账户注册
                  </a>
                </div>
                <el-form-item label="(必填)支付宝账户">
                  <el-input
                    v-model="merchant.alipay_account"
                    type="text"
                    placeholder="请输入支付宝账户"
                  />
                </el-form-item>
              </el-tooltip>
            </el-collapse-item>
            <el-collapse-item name="collapse-item-linkman">
              <template slot="title"><div><h4>联系方式</h4></div></template>
              <el-form-item label="(必填)联系人姓名">
                <el-input
                  v-model="merchant.link_man"
                  type="text"
                  placeholder="请输入联系人姓名"
                />
              </el-form-item>
              <el-form-item label="(必填)联系电话">
                <el-input
                  v-model="merchant.link_phone"
                  type="text"
                  placeholder="请输入联系电话"
                />
              </el-form-item>
              <el-form-item label="(必填)email">
                <el-input
                  v-model="merchant.link_email"
                  type="text"
                  placeholder="请输入email"
                />
              </el-form-item>
              <el-form-item label="(线下必填)实际经营地址">
                <el-input
                  v-model="merchant.address_actual"
                  type="text"
                  placeholder="请输入实际经营地址"
                />
              </el-form-item>
            </el-collapse-item>
            <el-collapse-item name="collapse-item-bank">
              <template slot="title"><div><h4>结算账户</h4></div></template>
              <el-form-item label="(必填)账户类型">
                <el-select
                  v-model="merchant.bank_account_type"
                  placeholder="请选择账户类型"
                >
                  <el-option value="对公账户" />
                  <el-option value="私人(法人)账户（个体工商户可选）" />
                </el-select>
              </el-form-item>
              <el-tooltip
                placement="top"
                effect="light"
                content="需要填写包含省、市信息的完整支行名称"
              >
                <el-form-item label="(必填)开户银行">
                  <el-input
                    v-model="merchant.bank_name"
                    type="text"
                    placeholder="请输入开户银行"
                  />
                </el-form-item>
              </el-tooltip>

              <el-form-item label="(必填)开户名称">
                <el-input
                  v-model="merchant.bank_account_name"
                  type="text"
                  placeholder="请输入开户名称"
                />
              </el-form-item>
              <el-form-item label="(必填)结算账号">
                <el-input
                  v-model="merchant.bank_account_no"
                  type="text"
                  placeholder="请输入结算账号"
                />
              </el-form-item>
            </el-collapse-item>
          </el-collapse>
        </el-col>
      </el-row>

      <template v-if="editable">
        <el-row>
          <el-divider></el-divider>
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
  name: '',
  wxpay_businecate: '',
  alipay_account: '',
  link_man: '',
  link_phone: '',
  link_email: '',
  address_actual: '',
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
      upfiles_desc: '只能上传图片(且小于2M)。',
      file_dialog_visible: false,
      file_dialog_url: '',
      file_upload_url: process.env.VUE_APP_UPLOAD_URL,
      editable: false,
      ajax: false,
      active_collapse: [
        'collapse-item-com',
        'collapse-item-linkman',
        'collapse-item-legalman',
        'collapse-item-bank'
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
    handleFileUploadError(err, file, file_list) {
      console.log(err, file, file_list);
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
