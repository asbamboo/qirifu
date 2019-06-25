<template>
  <div class="app-container">
    <el-collapse v-model="active_collapse">
      <el-collapse-item title="企业/个体工商户" name="collapse-item-com">
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
      <el-collapse-item title="联系人" name="collapse-item-linkman">
        <div>姓名：{{ merchant.link_man }}</div>
        <div>联系电话：{{ merchant.link_phone }}</div>
        <div>email：{{ merchant.link_email }}</div>
      </el-collapse-item>
      <el-collapse-item title="法定代表人" name="collapse-item-legalman">
        <div>证件类型：{{ merchant.legal_id_type }}</div>
        <div>证件号码：{{ merchant.legal_id_no }}</div>
        <div>证件有效期：{{ merchant.legal_id_indate }}</div>
      </el-collapse-item>
      <el-collapse-item title="结算账户" name="collapse-item-bank">
        <div>账户类型：{{ merchant.bank_account_type }}</div>
        <div>开户银行：{{ merchant.bank_name }}</div>
        <div>开户名称：{{ merchant.bank_account_name }}</div>
        <div>结算账号：{{ merchant.bank_account_no }}</div>
      </el-collapse-item>
      <el-collapse-item title="上传资料" name="collapse-item-files">
        <div >
          <a v-for="f in merchant.files" v-bind:href="f.url" target="_blank">
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
        // 'collapse-item-bank'
      ]
    }
  },
  created() {
    this.fetchData(this.$route.params.id)
  },
  methods: {
    fetchData(id) {
      fetchDetail(id).then(response => {
        this.merchant = response.data
      }).catch(err => {
        console.log(err)
      })
    }
  }
}
</script>
