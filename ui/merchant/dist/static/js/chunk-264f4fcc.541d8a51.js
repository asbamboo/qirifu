(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-264f4fcc"],{3423:function(t,a,e){"use strict";e.r(a);var s=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"app-container"},[e("el-row",[e("el-col",{attrs:{md:8}},[e("el-card",{staticClass:"box-card"},[e("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[e("span",[t._v("用于登录的账号、email等信息")])]),t._v(" "),e("div",[e("el-form",{staticClass:"form-container",attrs:{"label-width":"100px","label-position":"right"}},[e("el-form-item",{attrs:{label:"账号"}},[e("el-input",{attrs:{type:"text",placeholder:"请输入账号"},model:{value:t.account,callback:function(a){t.account=a},expression:"account"}}),t._v(" "),t.account_isset?t._e():e("el-button",{staticClass:"form-item-button",attrs:{disabled:t.ajax,type:"primary"},on:{click:t.doSetAccount}},[t._v("\n                设置账号\n              ")])],1)],1),t._v(" "),e("el-form",{staticClass:"form-container",attrs:{"label-width":"100px","label-position":"right"}},[e("el-form-item",{attrs:{label:"Email"}},[e("el-input",{attrs:{type:"text",placeholder:"请输入email"},model:{value:t.email,callback:function(a){t.email=a},expression:"email"}}),t._v(" "),t.email_isset?t._e():e("el-button",{staticClass:"form-item-button",attrs:{disabled:t.ajax,type:"primary"},on:{click:t.doSettingEmail}},[t._v("\n                立即绑定\n              ")])],1)],1),t._v(" "),t.phone_used?e("el-form",{staticClass:"form-container",attrs:{"label-width":"100px","label-position":"right"}},[e("el-form-item",{attrs:{label:"手机号码"}},[e("el-input",{attrs:{type:"text",placeholder:"请输入手机号码"},model:{value:t.phone,callback:function(a){t.phone=a},expression:"phone"}}),t._v(" "),t.phone_isset?t._e():e("el-button",{staticClass:"form-item-button",attrs:{disabled:t.ajax,type:"primary"},on:{click:t.doSettingPhone}},[t._v("\n                立即绑定\n              ")])],1)],1):t._e()],1)]),t._v(" "),e("el-divider"),t._v(" "),e("el-card",{staticClass:"box-card"},[e("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[e("span",[t._v("重置密码")])]),t._v(" "),e("div",[e("el-form",{staticClass:"form-container",attrs:{"label-width":"100px","label-position":"right"}},[e("el-form-item",{attrs:{label:"原密码"}},[e("el-input",{attrs:{type:"password",placeholder:"请输入原始密码"},model:{value:t.org_password,callback:function(a){t.org_password=a},expression:"org_password"}})],1),t._v(" "),e("el-form-item",{attrs:{label:"新密码"}},[e("el-input",{attrs:{type:"password",placeholder:"请输入新密码"},model:{value:t.new_password,callback:function(a){t.new_password=a},expression:"new_password"}})],1),t._v(" "),e("el-form-item",{attrs:{label:"确认新密码"}},[e("el-input",{attrs:{type:"password",placeholder:"请再次输入新密码"},model:{value:t.confirm_password,callback:function(a){t.confirm_password=a},expression:"confirm_password"}})],1),t._v(" "),e("el-row",[e("el-col",{attrs:{offset:8}},[e("el-button",{attrs:{disabled:t.ajax,type:"primary"},on:{click:t.doResetPassword}},[t._v("提交")])],1)],1)],1)],1)])],1)],1)],1)},o=[],n=e("b775"),i=e("4328"),c=e.n(i);function r(){return Object(n["a"])({url:"/account/info",method:"get"})}function l(t){return Object(n["a"])({url:"/account/setting-account",method:"post",data:c.a.stringify(t)})}function u(t){return Object(n["a"])({url:"/account/setting-email",method:"post",data:c.a.stringify(t)})}function p(t){return Object(n["a"])({url:"/account/setting-phone",method:"post",data:c.a.stringify(t)})}function d(t){return Object(n["a"])({url:"/account/reset-password",method:"post",data:c.a.stringify(t)})}function h(t){return Object(n["a"])({url:"/account/send-captcha",method:"post",data:c.a.stringify(t)})}var m=e("61f7"),f=!1,_={name:"InformationLogin",data:function(){return{phone_used:f,account:"",account_isset:!1,email:"",email_isset:!1,phone:"",phone_isset:!1,org_password:"",new_password:"",confirm_password:"",ajax:!1}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.ajax=!0,r().then(function(a){t.account=a.data.account,t.email=a.data.email,t.phone=a.data.phone,t.account_isset=t.account.length>0,t.email_isset=t.email.length>0,t.phone_isset=t.phone.length>0,t.ajax=!1}).catch(function(a){t.ajax=!1})},doSetAccount:function(){var t=this;this.ajax=!0,l({account:this.account}).then(function(a){t.ajax=!1,t.account_isset=!0,t.$message({message:a.message,showClose:!0})}).catch(function(a){t.ajax=!1,console.log(a)})},doSettingEmail:function(){var t=this;if(!Object(m["d"])(this.email))return this.$message.error("请输入有效的email");this.ajax=!0,h({email:this.email}).then(function(a){t.ajax=!1,t.$prompt("请输入email验证码(请注意查收email)","确认email绑定",{inputPattern:/./,inputErrorMessage:"请输入验证码"}).then(function(a){var e=a.value;t.ajax=!0,u({email:t.email,captcha:e}).then(function(a){t.ajax=!1,t.email_isset=!0,t.$message({message:a.message,showClose:!0})}).catch(function(a){console.log(a),t.ajax=!1})})}).catch(function(a){console.log(a),t.ajax=!1})},doSettingPhone:function(){var t=this;if(!/\d+/.test(this.phone.trim()))return this.$message.error("请输入有效的手机号");this.ajax=!0,h({phone:this.phone}).then(function(a){t.ajax=!1,t.$prompt("请输入验证码(请注意查收手机短信)","确认绑定手机号",{inputPattern:/./,inputErrorMessage:"请输入验证码"}).then(function(a){var e=a.value;t.ajax=!0,p({phone:t.phone,captcha:e}).then(function(a){t.ajax=!1,t.phone_isset=!0,t.$message({message:a.message,showClose:!0})}).catch(function(a){console.log(a),t.ajax=!1})})}).catch(function(a){console.log(a),t.ajax=!1})},doResetPassword:function(){var t=this;this.ajax=!0,d({org_password:this.org_password,new_password:this.new_password,confirm_password:this.confirm_password}).then(function(a){t.ajax=!1,t.$message({message:a.message,showClose:!0})}).catch(function(a){console.log(a),t.ajax=!1})}}},g=_,w=(e("4a63"),e("2877")),b=Object(w["a"])(g,s,o,!1,null,null,null);a["default"]=b.exports},"4a63":function(t,a,e){"use strict";var s=e("d487"),o=e.n(s);o.a},d487:function(t,a,e){}}]);