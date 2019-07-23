<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        v-model="list_query.merchant_name"
        placeholder="商户简称"
        style="width: 200px;"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />
      <el-select
        v-model="list_query.status"
        placeholder="支付状态"
        class="filter-item"
        clearable
      >
        <el-option
          v-for="item in statuss"
          :key="item.key"
          :label="item.label"
          :value="item.key">
        </el-option>
      </el-select>
      <el-select
        v-model="list_query.channel"
        placeholder="支付通道"
        class="filter-item"
        clearable
      >
        <el-option
          v-for="item in channels"
          :key="item.key"
          :label="item.label"
          :value="item.key">
        </el-option>
      </el-select>
      <el-input
        v-model="list_query.in_trade_no"
        placeholder="交易编号(本系统)"
        style="width: 200px;"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />
      <el-input
        v-model="list_query.out_trade_no"
        placeholder="交易编号(支付通道)"
        style="width: 200px;"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />
      <el-date-picker
        v-model="list_query.create_ymdhis"
        value-format="yyyy-MM-dd"
        type="daterange"
        align="right"
        unlink-panels
        range-separator="至"
        start-placeholder="交易开始日期"
        end-placeholder="交易结束日期"
        class="filter-item"
      ></el-date-picker>
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-search"
        @click="handleFilter"
      >查询</el-button>
    </div>

    <el-table v-loading="list_loding" :data="list" border fit highlight-current-row style="width: 100%">

      <el-table-column align="center" label="商户简称">
        <template slot-scope="scope">
          <span>{{ scope.row.merchant_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="支付通道" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.channel.label }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="金额" width="120">
        <template slot-scope="scope">
          <span>{{ scope.row.amount }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="支付状态" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.status.label }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="交易编号（本系统）">
        <template slot-scope="scope">
          <span>{{ scope.row.in_trade_no }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="交易编号（支付通道）">
        <template slot-scope="scope">
          <span>{{ scope.row.out_trade_no }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="交易时间" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.create_ymdhis }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="支付时间" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.pay_ymdhis }}</span>
        </template>
      </el-table-column>

    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="list_query.page" :limit.sync="list_query.limit" @pagination="getList" />

  </div>
</template>

<script>
import { fetchList, getSearchOptions } from '@/api/trade'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

export default {
  name: 'MerchantLists',
  components: { Pagination },
  data() {
    return {
      statuss: undefined,
      channels: undefined,
      list: null,
      total: 0,
      list_loding: true,
      list_query: {
        merchant_name: '',
        in_trade_no: '',
        out_trade_no: '',
        create_ymdhis: '',
        status: '',
        channel: '',
        page: 1,
        limit: 10
      }
    }
  },
  created() {
    this.setSearchData()
    this.getList()
  },
  methods: {
    setSearchData() {
      getSearchOptions().then(response => {
        this.channels = response.data.channels
        this.statuss = response.data.statuss
      })
    },
    getList() {
      this.list_loding = true
      fetchList(this.list_query).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.list_loding = false
      })
    },
    handleFilter() {
      this.list_query.page = 1
      this.getList()
    }
  }
}
</script>
