(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d22e188"],{f9fe:function(a,t,e){"use strict";e.r(t);var s=function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",{staticClass:"app-container"},[e("el-tabs",{model:{value:a.active_tab,callback:function(t){a.active_tab=t},expression:"active_tab"}},[e("el-tab-pane",{attrs:{label:"基本设置",name:"system_info",lazy:""}},[e("system-info-form")],1),a._v(" "),e("el-tab-pane",{attrs:{label:"数据库",name:"database_info",lazy:""}},[e("database-info-form")],1),a._v(" "),e("el-tab-pane",{attrs:{label:"班布聚合",name:"asbamboo_info",lazy:""}},[e("asbamboo-info-form")],1)],1)],1)},o=[],n=function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",{staticClass:"app-container"},[e("el-form",{ref:"system-info-form",staticClass:"form-container",attrs:{model:a.system_info}},[e("el-row",[e("el-col",{attrs:{md:8}},[e("el-form-item",{attrs:{"label-width":"100px",label:"系统名称:"}},[e("el-input",{attrs:{type:"text",name:"system_name",placeholder:"请输入系统名称"},model:{value:a.system_info.name,callback:function(t){a.$set(a.system_info,"name",t)},expression:"system_info.name"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{md:8}},[e("el-form-item",{attrs:{"label-width":"100px",label:"管理员账号:"}},[e("el-input",{attrs:{type:"text",name:"system_user",placeholder:"请输入管理员账号"},model:{value:a.system_info.user,callback:function(t){a.$set(a.system_info,"user",t)},expression:"system_info.user"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{md:8}},[e("el-form-item",{attrs:{"label-width":"100px",label:"管理员密码:"}},[e("el-input",{attrs:{type:"password",name:"system_password",placeholder:"请输入管理员密码"},model:{value:a.system_info.password,callback:function(t){a.$set(a.system_info,"password",t)},expression:"system_info.password"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{offset:3}},[e("el-button",{attrs:{type:"primary",plain:""},on:{click:a.submitForm}},[a._v("\n          提交修改\n        ")])],1)],1)],1)],1)},l=[],i=e("b775");function r(){return Object(i["a"])({url:"/system/setting/system-info",method:"get"})}function m(a){var t=e("4328");return Object(i["a"])({url:"/system/setting/system-info",method:"post",data:t.stringify(a)})}function c(){return Object(i["a"])({url:"/system/setting/database-info",method:"get"})}function f(a){var t=e("4328");return Object(i["a"])({url:"/system/setting/database-info",method:"post",data:t.stringify(a)})}function b(){return Object(i["a"])({url:"/system/setting/asbamboo-info",method:"get"})}function u(a){var t=e("4328");return Object(i["a"])({url:"/system/setting/asbamboo-info",method:"post",data:t.stringify(a)})}var d={name:"",user:"",password:""},p={name:"SystemInfoForm",data:function(){return{system_info:Object.assign({},d),ajax:!1}},created:function(){this.fetchData()},methods:{fetchData:function(){var a=this;1!=this.ajax&&(this.ajax=!0,r().then(function(t){a.ajax=!1,a.system_info=t.data}).catch(function(t){a.ajax=!1,console.log(t)}))},submitForm:function(){var a=this;1!=this.ajax&&(this.ajax=!0,m(this.system_info).then(function(t){a.ajax=!1,a.$message({message:t.message})}).catch(function(t){a.ajax=!1,console.log(t)}))}}},_=p,h=e("2877"),x=Object(h["a"])(_,n,l,!1,null,null,null),y=x.exports,v=function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",{staticClass:"app-container"},[e("el-form",{ref:"database-form",staticClass:"form-container",attrs:{model:a.database_info}},[e("el-row",[e("el-col",{attrs:{md:8}},[e("el-form-item",{attrs:{"label-width":"100px",label:"主机名称:"}},[e("el-input",{attrs:{type:"text",name:"database_host",placeholder:"请输入数据库主机名称"},model:{value:a.database_info.host,callback:function(t){a.$set(a.database_info,"host",t)},expression:"database_info.host"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{md:8}},[e("el-form-item",{attrs:{"label-width":"100px",label:"端口:"}},[e("el-input",{attrs:{type:"text",name:"database_port",placeholder:"请输入数据库端口号"},model:{value:a.database_info.port,callback:function(t){a.$set(a.database_info,"port",t)},expression:"database_info.port"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{md:8}},[e("el-form-item",{attrs:{"label-width":"100px",label:"数据库名称:"}},[e("el-input",{attrs:{type:"text",name:"database_name",placeholder:"请输入数据库名称"},model:{value:a.database_info.database,callback:function(t){a.$set(a.database_info,"database",t)},expression:"database_info.database"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{md:8}},[e("el-form-item",{attrs:{"label-width":"100px",label:"账号:"}},[e("el-input",{attrs:{type:"text",name:"database_username",placeholder:"请输入数据库账号"},model:{value:a.database_info.username,callback:function(t){a.$set(a.database_info,"username",t)},expression:"database_info.username"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{md:8}},[e("el-form-item",{attrs:{"label-width":"100px",label:"密码:"}},[e("el-input",{attrs:{type:"text",name:"database_password",placeholder:"请输入数据库密码"},model:{value:a.database_info.password,callback:function(t){a.$set(a.database_info,"password",t)},expression:"database_info.password"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{offset:3}},[e("el-button",{attrs:{type:"primary",plain:""},on:{click:a.submitForm}},[a._v("\n          提交修改\n        ")])],1)],1)],1)],1)},j=[],w={host:"",port:"",database:"",username:"",password:""},g={name:"DatabaseInfoForm",data:function(){return{database_info:Object.assign({},w),ajax:!1}},created:function(){this.fetchData()},methods:{fetchData:function(){var a=this;1!=this.ajax&&(this.ajax=!0,c().then(function(t){a.database_info=t.data,a.ajax=!1}).catch(function(t){console.log(t),a.ajax=!1}))},submitForm:function(){var a=this;1!=this.ajax&&(this.ajax=!0,f(this.database_info).then(function(t){a.ajax=!1,a.$message({message:t.message})}).catch(function(t){a.ajax=!1,console.log(t)}))}}},k=g,$=Object(h["a"])(k,v,j,!1,null,null,null),O=$.exports,F=function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",{staticClass:"app-container"},[e("el-form",{ref:"asbamboo_form",staticClass:"form-container",attrs:{model:a.asbamboo_info}},[e("el-row",[e("el-col",{attrs:{md:8}},[e("el-form-item",{attrs:{"label-width":"75px",label:"APP Key:"}},[e("el-input",{attrs:{type:"text",name:"asbamboo_app_key",placeholder:"请输入班步聚合平台app_key"},model:{value:a.asbamboo_info.app_key,callback:function(t){a.$set(a.asbamboo_info,"app_key",t)},expression:"asbamboo_info.app_key"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{md:12}},[e("el-form-item",{attrs:{"label-width":"75px",label:"Security:"}},[e("el-input",{attrs:{type:"text",name:"asbamboo_secret",placeholder:"请输入班步聚合平台secret"},model:{value:a.asbamboo_info.secret,callback:function(t){a.$set(a.asbamboo_info,"secret",t)},expression:"asbamboo_info.secret"}})],1)],1)],1),a._v(" "),e("el-row",[e("el-col",{attrs:{offset:3}},[e("el-button",{attrs:{type:"primary",plain:""},on:{click:a.submitForm}},[a._v("\n          提交修改\n        ")])],1)],1)],1)],1)},D=[],C={app_key:"",secret:""},I={name:"AsbambooInfoForm",data:function(){return{asbamboo_info:Object.assign({},C),ajax:!1}},created:function(){this.fetchData()},methods:{fetchData:function(){var a=this;1!=this.ajax&&(this.ajax=!0,b().then(function(t){a.asbamboo_info=t.data,a.ajax=!1}).catch(function(t){console.log(t),a.ajax=!1}))},submitForm:function(){var a=this;1!=this.ajax&&(this.ajax=!0,u(this.asbamboo_info).then(function(t){a.ajax=!1,a.$message({message:t.message})}).catch(function(t){a.ajax=!1,console.log(t)}))}}},S=I,E=Object(h["a"])(S,F,D,!1,null,null,null),z=E.exports,A={name:"SystemSetting",components:{SystemInfoForm:y,DatabaseInfoForm:O,AsbambooInfoForm:z},data:function(){return{active_tab:"system_info"}}},J=A,P=Object(h["a"])(J,s,o,!1,null,null,null);t["default"]=P.exports}}]);