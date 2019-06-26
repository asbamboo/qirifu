<template>
  <div class="app-container">

    <div class="filter-container">
      <el-input v-model="list_query.name" placeholder="商户简称" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-input v-model="list_query.link_man" placeholder="联系人姓名" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-input v-model="list_query.link_phone" placeholder="联系人手机" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        Search
      </el-button>
    </div>

    <el-table v-loading="list_loding" :data="list" border fit highlight-current-row style="width: 100%">

      <el-table-column align="center" label="商户简称">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="联系人姓名">
        <template slot-scope="scope">
          <span>{{ scope.row.link_man }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="联系人手机">
        <template slot-scope="scope">
          <span>{{ scope.row.link_phone }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="创建时间">
        <template slot-scope="scope">
          <span>{{ scope.row.create_ymdhis }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="修改时间">
        <template slot-scope="scope">
          <span>{{ scope.row.update_ymdhis }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="操作" width="120">
        <template slot-scope="scope">
          <router-link :to="'/merchant/detail/'+scope.row.id">
            <el-button type="primary" size="small" icon="el-icon-edit">
              详情
            </el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="list_query.page" :limit.sync="list_query.limit" @pagination="getList" />
  </div>
</template>

<script>
import { fetchList } from '@/api/merchant'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

export default {
  name: 'MerchantLists',
  components: { Pagination },
  data() {
    return {
      list: null,
      total: 0,
      list_loding: true,
      list_query: {
        name: '',
        link_man: '',
        link_phone: '',
        page: 1,
        limit: 10
      }
    }
  },
  created() {
    this.getList()
  },
  methods: {
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
