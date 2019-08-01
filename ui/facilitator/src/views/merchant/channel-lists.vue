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
        v-model="list_query.channel_type"
        placeholder="支付通道"
        class="filter-item"
        clearable
      >
        <el-option
          v-for="item in search_data.channel_types"
          :key="item.key"
          :label="item.label"
          :value="item.key">
        </el-option>
      </el-select>
      <el-select
        v-model="list_query.channel_status"
        placeholder="申请状态"
        class="filter-item"
        clearable
      >
        <el-option
          v-for="item in search_data.channel_statuss"
          :key="item.key"
          :label="item.label"
          :value="item.key">
        </el-option>
      </el-select>

      <el-button class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        查询
      </el-button>
    </div>

    <el-table v-loading="list_loding" :data="list" border fit highlight-current-row style="width: 100%">

      <el-table-column align="center" label="商户简称" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.merchant.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="支付通道" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.type.label }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="状态" min-width="350">
        <template slot-scope="scope">
          <span>{{ scope.row.status.label }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="创建时间" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.create_ymdhis }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="修改时间" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.update_ymdhis }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="操作" width="120">
        <template slot-scope="scope">
          <router-link :to="'/merchant/detail/'+scope.row.merchant.seq">
            <el-button type="primary" size="small" icon="el-icon-view">
              商户详情
            </el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="list_query.page" :limit.sync="list_query.limit" @pagination="getList" />
  </div>
</template>

<script>
import { getChannelSearchOptions, fetchChannelList } from '@/api/merchant'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

export default {
  name: 'MerchantChannelLists',
  components: { Pagination },
  data() {
    return {
      list: null,
      total: 0,
      list_loding: true,
      search_data: {
        channel_types : undefined,
        channel_statuss : undefined
      },
      list_query: {
        merchant_name: '',
        channel_type: '',
        channel_status: '',
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
    setSearchData(){
      getChannelSearchOptions().then(response => {
        this.search_data.channel_types  = response.data.channel_types
        this.search_data.channel_statuss  = response.data.channel_statuss
      }).catch(err => {
        console.log(err)
      })
    },
    getList() {
      this.list_loding = true
      fetchChannelList(this.list_query).then(response => {
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
