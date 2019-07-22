//app.js
import api from './api/account'
App({
  onLaunch: function () {

  },

  //登录与权限
  login(succ) {

    if (wx.getStorageSync("token") != '') {

      //已登录回调
      succ !== undefined ? succ() : [];

      return;
    }
    //微信登录
    wx.login({

      success: function (e) {

        //登录
        api.user.login({ 'code': e.code }, e => {

          //设置token
          wx.setStorageSync('token', e.token);

          //登录成功后回调
          succ !== undefined ? succ() : [];

        })

      }

    })
  },


  globalData: {
    aut_userinfo: false,
    is_login: false,
    userInfo: null,
    project_id:''
  }

})