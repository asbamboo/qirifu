(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0b07f57c"],{2465:function(t,n,e){"use strict";e.d(n,"a",function(){return o}),e.d(n,"b",function(){return l}),e.d(n,"d",function(){return c}),e.d(n,"c",function(){return r});var a=e("b775"),s=e("4328"),i=e.n(s);function o(t){return Object(a["a"])({url:"/trade/lists",method:"get",params:t})}function l(t){return Object(a["a"])({url:"/trade/nopay-lists",method:"get",params:t})}function c(t){return Object(a["a"])({url:"/trade/sync",method:"post",data:i.a.stringify(t)})}function r(){return Object(a["a"])({url:"/trade/search-options",method:"get"})}},"333d":function(t,n,e){"use strict";var a=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"pagination-container",class:{hidden:t.hidden}},[e("el-pagination",t._b({attrs:{background:t.background,"current-page":t.currentPage,"page-size":t.pageSize,layout:t.layout,"page-sizes":t.pageSizes,total:t.total},on:{"update:currentPage":function(n){t.currentPage=n},"update:current-page":function(n){t.currentPage=n},"update:pageSize":function(n){t.pageSize=n},"update:page-size":function(n){t.pageSize=n},"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}},"el-pagination",t.$attrs,!1))],1)},s=[];e("c5f6");Math.easeInOutQuad=function(t,n,e,a){return t/=a/2,t<1?e/2*t*t+n:(t--,-e/2*(t*(t-2)-1)+n)};var i=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||function(t){window.setTimeout(t,1e3/60)}}();function o(t){document.documentElement.scrollTop=t,document.body.parentNode.scrollTop=t,document.body.scrollTop=t}function l(){return document.documentElement.scrollTop||document.body.parentNode.scrollTop||document.body.scrollTop}function c(t,n,e){var a=l(),s=t-a,c=20,r=0;n="undefined"===typeof n?500:n;var u=function t(){r+=c;var l=Math.easeInOutQuad(r,a,s,n);o(l),r<n?i(t):e&&"function"===typeof e&&e()};u()}var r={name:"Pagination",props:{total:{required:!0,type:Number},page:{type:Number,default:1},limit:{type:Number,default:20},pageSizes:{type:Array,default:function(){return[10,20,30,50]}},layout:{type:String,default:"total, sizes, prev, pager, next, jumper"},background:{type:Boolean,default:!0},autoScroll:{type:Boolean,default:!0},hidden:{type:Boolean,default:!1}},computed:{currentPage:{get:function(){return this.page},set:function(t){this.$emit("update:page",t)}},pageSize:{get:function(){return this.limit},set:function(t){this.$emit("update:limit",t)}}},methods:{handleSizeChange:function(t){this.$emit("pagination",{page:this.currentPage,limit:t}),this.autoScroll&&c(0,800)},handleCurrentChange:function(t){this.$emit("pagination",{page:t,limit:this.pageSize}),this.autoScroll&&c(0,800)}}},u=r,d=(e("e498"),e("2877")),_=Object(d["a"])(u,a,s,!1,null,"6af373ef",null);n["a"]=_.exports},7365:function(t,n,e){"use strict";e.r(n);var a=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"app-container"},["complated"==t.sync_status?e("el-alert",{attrs:{title:"交易同步完成",type:"success","show-icon":"",closable:!1}},[t._v("\n  已同步交易成功"+t._s(t.sync_total)+"个，其中:\n  "+t._s(t.sync_pay)+"个交易状态变更为支付成功，\n  "+t._s(t.sync_cancel)+"个交易状态变更为取消支付，\n  "+t._s(t.sync_nopay)+"个交易状态依然是未支付付。\n    同步失败"+t._s(t.sync_failed)+"个。\n    "),e("el-button",{attrs:{type:"primary"},on:{click:t.doSync}},[t._v("\n      同步失败的交易点此重新同步\n    ")])],1):t._e(),t._v(" "),"syncing"==t.sync_status?e("el-alert",{attrs:{title:"交易同步中",type:"info","show-icon":"",closable:!1}},[t._v("\n    已同步交易成功"+t._s(t.sync_total)+"个，其中:\n    "+t._s(t.sync_pay)+"个交易状态变更为支付成功，\n    "+t._s(t.sync_cancel)+"个交易状态变更为取消支付，\n    "+t._s(t.sync_nopay)+"个交易状态依然是未支付付。\n    同步失败"+t._s(t.sync_failed)+"个。\n    "),e("el-button",{attrs:{type:"primary"},on:{click:t.doSync}},[t._v("\n      同步失败的交易点此重新同步\n    ")])],1):t._e(),t._v(" "),"nosync"==t.sync_status?e("el-alert",{attrs:{title:"未支付交易信息同步",type:"info","show-icon":"",closable:!1}},[t._v("\n    系统中未完成支付的交易，可能是由于与支付通道之间通信不及时导致的。\n    "),e("el-button",{attrs:{type:"primary"},on:{click:t.doSync}},[t._v("\n      点此立即开始同步未支付的交易信息\n    ")])],1):t._e(),t._v(" "),e("el-divider"),t._v(" "),e("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.list_loding,expression:"list_loding"}],attrs:{data:t.list,border:"",fit:"","highlight-current-row":""}},[e("el-table-column",{attrs:{align:"center",label:"交易编号（本系统）"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.in_trade_no))])]}}])}),t._v(" "),e("el-table-column",{attrs:{align:"center",label:"交易编号（支付通道）"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.out_trade_no))])]}}])}),t._v(" "),e("el-table-column",{attrs:{align:"center",label:"金额"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.amount))])]}}])}),t._v(" "),e("el-table-column",{attrs:{align:"center",label:"支付通道"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.channel.label))])]}}])}),t._v(" "),e("el-table-column",{attrs:{align:"center",label:"商户简称"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.merchant_name))])]}}])}),t._v(" "),e("el-table-column",{attrs:{align:"center",label:"状态同步信息",width:"350"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("span",[t._v(t._s(n.row.sync_info))])]}}])}),t._v(" "),t.has_more?e("div",{directives:[{name:"loading",rawName:"v-loading",value:t.list_loding,expression:"list_loding"}],staticStyle:{"text-align":"center"},attrs:{slot:"append"},slot:"append"},[e("el-button",{attrs:{type:"text"},on:{click:t.getList}},[t._v("点击加载更多")])],1):t._e()],1)],1)},s=[],i=(e("7f7f"),e("2465")),o=e("333d"),l={name:"MerchantLists",components:{Pagination:o["a"]},data:function(){return{list:[],has_more:!1,list_loding:!0,sync_status:"nosync",sync_total:0,sync_nopay:0,sync_cancel:0,sync_pay:0,sync_failed:0,list_query:{start_seq:0}}},created:function(){this.getList()},methods:{startSyncTrade:function(t){var n=this;if(this.list[t].synced)return t++,void(this.list[t]&&this.startSyncTrade(this.list[t]));this.list[t].sync_info="正在同步中......",Object(i["d"])(this.list[t]).then(function(e){var a=n.list[t].status.label,s=e.data.status.label,o="同步完成。状态由["+a+"]修改为["+s+"]";n.list[t].sync_info=o;var l=e.data.status.name;n.sync_total++,"payok"==l||"payed"==l?n.sync_pay++:"cancel"==l?n.sync_cancel++:n.sync_nopay++,n.list[t].synced=!0,n.list[t].syncerr&&(n.sync_failed--,n.list[t].syncerr=!1),t++,console.log(t,n.list[t],n.has_more),n.list[t]?n.startSyncTrade(t):n.has_more?(n.list_loding=!0,Object(i["b"])(n.list_query).then(function(e){n.list_loding=!1,n.pushListData(e.data),n.has_more?n.startSyncTrade(t):n.sync_status="complated"}).catch(function(t){console.log(t),n.list_loding=!1})):n.sync_status="complated"}).catch(function(e){n.list[t].syncerr=!0,n.sync_failed++,n.list[t].sync_info="同步失败，稍后您可以重试。",t++,n.list[t]?n.startSyncTrade(t):n.has_more?(n.list_loding=!0,Object(i["b"])(n.list_query).then(function(e){n.list_loding=!1,n.pushListData(e.data),n.has_more?n.startSyncTrade(t):n.sync_status="complated"}).catch(function(t){console.log(t),n.list_loding=!1})):n.sync_status="complated",console.log(e)})},doSync:function(){this.list[0]&&(this.sync_status="syncing",this.startSyncTrade(0))},pushListData:function(t){for(var n in t.items)t.items[n].sync_info="等待同步",this.list.push(t.items[n]),this.list_query.start_seq<t.items[n].seq&&(this.list_query.start_seq=t.items[n].seq+1);t.items.length<1?this.has_more=!1:this.has_more=!0},getList:function(){var t=this;this.list_loding=!0,Object(i["b"])(this.list_query).then(function(n){t.pushListData(n.data),t.list_loding=!1}).catch(function(n){console.log(n),t.list_loding=!1})}}},c=l,r=e("2877"),u=Object(r["a"])(c,a,s,!1,null,null,null);n["default"]=u.exports},7456:function(t,n,e){},e498:function(t,n,e){"use strict";var a=e("7456"),s=e.n(a);s.a}}]);