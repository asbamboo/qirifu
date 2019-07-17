<template>
  <div class="app-container">
    <el-collapse v-model="active_collapse">
      <el-collapse-item name="collapse-item-com">
        <template slot="title">
          <div>
            <h4>企业/个体工商户</h4>
          </div>
        </template>
        <div>简称：{{ merchant.name }}</div>
        <div>全称：{{ merchant.fullname }}</div>
        <div>行业：{{ merchant.profession }}</div>
        <div>经营类目：{{ merchant.businecate }}</div>
        <div>统一社会信用代码：{{ merchant.code }}</div>
        <div>注册地址：{{ merchant.address_register }}</div>
        <div>实际经营地址：{{ merchant.address_actual }}</div>
        <div>添加日期：{{ merchant.create_ymdhis }}</div>
        <div>修改日期：{{ merchant.update_ymdhis }}</div>
      </el-collapse-item>
      <el-collapse-item name="collapse-item-linkman">
        <template slot="title">
          <div>
            <h4>联系人</h4>
          </div>
        </template>
        <div>姓名：{{ merchant.link_man }}</div>
        <div>联系电话：{{ merchant.link_phone }}</div>
        <div>email：{{ merchant.link_email }}</div>
      </el-collapse-item>
      <el-collapse-item name="collapse-item-legalman">
        <template slot="title">
          <div>
            <h4>法定代表人</h4>
          </div>
        </template>
        <div>证件类型：{{ merchant.legal_id_type }}</div>
        <div>证件号码：{{ merchant.legal_id_no }}</div>
        <div>证件有效期：{{ merchant.legal_id_indate }}</div>
      </el-collapse-item>
      <el-collapse-item name="collapse-item-bank">
        <template slot="title">
          <div>
            <h4>结算账户</h4>
          </div>
        </template>
        <div>账户类型：{{ merchant.bank_account_type }}</div>
        <div>开户银行：{{ merchant.bank_name }}</div>
        <div>开户名称：{{ merchant.bank_account_name }}</div>
        <div>结算账号：{{ merchant.bank_account_no }}</div>
      </el-collapse-item>
      <el-collapse-item title="上传资料" name="collapse-item-files">
        <template slot="title">
          <div>
            <h4>上传资料</h4>
          </div>
        </template>
        <div v-for="f in merchant.files" class="merchant-image-box">
            <a v-bind:href="f.url" target="_blank">
              <el-image
                :src="f.url"
                fit="scale-down"
                style="width: 100%; height:200px; border: #eeeeee;"
              ></el-image>
              {{ f.name }}
            </a>
        </div>
      </el-collapse-item>
    </el-collapse>
  </div>
</template>

<script>
import { fetchDetail } from '@/api/merchant'

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
  bank_account_type: '',
  bank_name: '',
  bank_account_name: '',
  bank_account_no: '',
  create_ymdhis: '',
  update_ymdhis: ''
}

export default {
  name: 'MerchantDetail',
  data() {
    return {
      merchant: Object.assign({}, merchant),
      active_collapse: [
        'collapse-item-com',
        // 'collapse-item-linkman',
        // 'collapse-item-legalman',
        // 'collapse-item-bank',
        'collapse-item-files'
      ]
    }
  },
  created() {
    this.fetchData(this.$route.params.seq)
  },
  methods: {
    fetchData(seq) {
      fetchDetail(seq).then(response => {
        this.merchant = response.data
      }).catch(err => {
        console.log(err)
      })
    }
  }
}
</script>

<style>
.merchant-image-box {

  width:200px;
  text-align:center;
  float:left;
  margin: 5px;
}
</style>
