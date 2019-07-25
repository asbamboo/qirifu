<template>
  <div class="app-container">
    <el-alert
      v-if="sync_status == 'complated'"
      title="交易同步完成"
      type="success"
      show-icon
      :closable='false'
    >
    已同步交易成功{{ sync_total }}个，其中:
    {{ sync_pay }}个交易状态变更为支付成功，
    {{ sync_cancel }}个交易状态变更为取消支付，
    {{ sync_nopay }}个交易状态依然是未支付付。
      同步失败{{ sync_failed }}个。
      <el-button @click="doSync" type="primary">
        同步失败的交易点此重新同步
      </el-button>
    </el-alert>
    <el-alert
      v-if="sync_status == 'syncing'"
      title="交易同步中"
      type="info"
      show-icon
      :closable='false'
    >
      已同步交易成功{{ sync_total }}个，其中:
      {{ sync_pay }}个交易状态变更为支付成功，
      {{ sync_cancel }}个交易状态变更为取消支付，
      {{ sync_nopay }}个交易状态依然是未支付付。
      同步失败{{ sync_failed }}个。
      <el-button @click="doSync" type="primary">
        同步失败的交易点此重新同步
      </el-button>
    </el-alert>
    <el-alert
      v-if="sync_status == 'nosync'"
      title="未支付交易信息同步"
      type="info"
      show-icon
      :closable='false'
    >
      系统中未完成支付的交易，可能是由于与支付通道之间通信不及时导致的。
      <el-button @click="doSync" type="primary">
        点此立即开始同步未支付的交易信息
      </el-button>
    </el-alert>
    <el-divider></el-divider>
    <el-table
      v-loading="list_loding"
      :data="list"
      border fit highlight-current-row
    >

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

      <el-table-column align="center" label="金额">
        <template slot-scope="scope">
          <span>{{ scope.row.amount }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="支付通道">
        <template slot-scope="scope">
          <span>{{ scope.row.channel.label }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="商户简称">
        <template slot-scope="scope">
          <span>{{ scope.row.merchant_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="状态同步信息" width="350">
        <template slot-scope="scope">
          <span>{{ scope.row.sync_info }}</span>
        </template>
      </el-table-column>

      <div
        slot="append"
        v-loading="list_loding"
        v-if="has_more"
        style="text-align:center"
      >
        <el-button type="text" @click="getList">点击加载更多</el-button>
      </div>
    </el-table>
  </div>
</template>

<script>
import { fetchNopayList, syncTrade } from '@/api/trade'
import Pagination from '@/components/Pagination' // Secondary package based on el-pagination

export default {
  name: 'MerchantLists',
  components: { Pagination },
  data() {
    return {
      list: [],
      has_more: false,
      list_loding: true,
      sync_status: "nosync",
      sync_total: 0,
      sync_nopay: 0,
      sync_cancel: 0,
      sync_pay: 0,
      sync_failed: 0,
      list_query: {
        start_seq: 0
      }
    }
  },
  created() {
    this.getList()
  },
  methods: {
    startSyncTrade(sync_index) {
      if(this.list[sync_index].synced){
        sync_index ++
        if(this.list[sync_index]){
          this.startSyncTrade(this.list[sync_index])
        }
        return
      }
      this.list[sync_index].sync_info = '正在同步中......'
      syncTrade(this.list[sync_index]).then(response => {
        let org_status  = this.list[sync_index].status.label
        let new_status  = response.data.status.label
        let sync_info   = '同步完成。'
                        +'状态由[' + org_status + ']修改为[' + new_status + ']'
        this.list[sync_index].sync_info = sync_info


        let status_name = response.data.status.name
        this.sync_total++
        if(status_name == 'payok' || status_name == 'payed'){
          this.sync_pay++
        }else if(status_name == 'cancel'){
          this.sync_cancel++
        }else{
          this.sync_nopay++
        }

        this.list[sync_index].synced = true
        if(this.list[sync_index].syncerr) {
          this.sync_failed--
          this.list[sync_index].syncerr = false
        }

        sync_index++
        console.log(
          sync_index,
          this.list[sync_index],
          this.has_more
        )
        if(this.list[sync_index]){
          this.startSyncTrade(sync_index)
        }else{
          if(this.has_more){
            this.list_loding = true
            fetchNopayList(this.list_query).then(response => {
              this.list_loding = false
              this.pushListData(response.data)
              if(this.has_more){
                this.startSyncTrade(sync_index)
              }else{
                this.sync_status = "complated"
              }
            }).catch(err => {
              console.log(err)
              this.list_loding = false
            })
          }else{
            this.sync_status = "complated"
          }
        }
      }).catch(err => {

        this.list[sync_index].syncerr = true
        this.sync_failed++
        this.list[sync_index].sync_info = '同步失败，稍后您可以重试。'

        sync_index++
        if(this.list[sync_index]){
          this.startSyncTrade(sync_index)
        }else{
          if(this.has_more){
            this.list_loding = true
            fetchNopayList(this.list_query).then(response => {
              this.list_loding = false
              this.pushListData(response.data)
              if(this.has_more){
                this.startSyncTrade(sync_index)
              }else{
                this.sync_status = "complated"
              }
            }).catch(err => {
              console.log(err)
              this.list_loding = false
            })
          }else{
            this.sync_status = "complated"
          }
        }

        console.log(err)
      })
    },
    doSync() {
      if(this.list[0]){
        this.sync_status = "syncing"
        this.startSyncTrade(0)
      }
    },
    pushListData(data) {
      for (var i in data.items) {
        data.items[i].sync_info = "等待同步"
        this.list.push(data.items[i])
        if(this.list_query.start_seq < data.items[i].seq){
          this.list_query.start_seq = data.items[i].seq + 1
        }
      }
      if(data.items.length < 1){
        this.has_more = false
      }else{
        this.has_more = true
      }
    },
    getList() {
      this.list_loding = true
      fetchNopayList(this.list_query).then(response => {
        this.pushListData(response.data)
        this.list_loding = false
      }).catch(err => {
        console.log(err)
        this.list_loding = false
      })
    }
  }
}
</script>
