<template>
  <div class="app-container">
    <div class="filter-container">
      <el-select
        v-model="list_query.is_read"
        placeholder="是否已读"
        class="filter-item"
      >
        <el-option label="全部" value="" />
        <el-option label="未读" value="0" />
        <el-option label="已读" value="1" />
      </el-select>
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-search"
        @click="handleFilter"
      >查询</el-button>
    </div>

    <el-table
      ref="inboxTable"
      v-loading="list_loding"
      :data="list"
      :row-class-name="tableRowIsRead"
      @row-click="toggleRowExpansion"
      @expand-change="changeRowExpansion"
      border
      fit
      highlight-current-row
      style="width: 100%"
    >
      <el-table-column align="left" type="expand">
        <template slot-scope="scope">
          <span>{{ scope.row.content }}</span>
        </template>
      </el-table-column>

      <el-table-column align="left" label="消息标题">
        <template slot-scope="scope">
          <span>
            {{ scope.row.title }}
          </span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="时间" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.create_ymdhis }}</span>
        </template>
      </el-table-column>

    </el-table>

    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="list_query.page"
      :limit.sync="list_query.limit"
      @pagination="fetchData"
    />

  </div>
</template>

<script>
import { getList,readMessage } from '@/api/message-inbox'
import Pagination from '@/components/Pagination'

export default {
  name: 'TradeList',
  components: { Pagination },
  data() {
    return {
      channels: [],
      list: [],
      total: 0,
      list_loding: true,
      list_query: {
        is_read: undefined,
        page: 1,
        limit: 10
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.list_loding = true
      getList(this.list_query).then(response => {
        this.list = response.data.items
        this.total = response.data.total
        this.list_loding = false
      }).catch(err => {
        console.log(err)
        this.list_loding = false
      })
    },
    handleFilter() {
      this.list_query.page = 1
      this.fetchData()
    },
    tableRowIsRead({row, rowIndex}) {
      console.log(row, rowIndex)
      return row.is_read ? '' : 'noread-row'
    },
    toggleRowExpansion(row, expanded) {
      this.$refs.inboxTable.toggleRowExpansion(row)
    },
    changeRowExpansion(row, expandedRows_or_expanded){
      console.log(row, expandedRows_or_expanded)
      if(row.is_read){
        return
      }

      readMessage(row.seq).then(response => {
        row.is_read = true
        console.log(response)
      }).catch(err => {
        console.log(err)
      })
    }
  }
}
</script>

<style>
  .el-table .noread-row {
    font-weight: bolder;
  }
</style>
