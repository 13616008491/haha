// pages/ProjectList/ProjectList.js
import api from '../../api/account'
var app = getApp()
Page({

  //页面请求数据初始化
  initData() {
    //获取列表信息
    api.list.project_list({ bottom_id: this.data.project_id }, e => {

      // //设置页面标题
      console.log(e);

      var newA = e.project_list;

      for (var i = 0; i < e.project_list.length; i++) {

        newA[i].status = [
          {
             title:'总需求',
             num: e.project_list[i].pending + e.project_list[i].confirm + e.project_list[i].finish + e.project_list[i].refuse
          },
          {
            title: '待定中',
            num: e.project_list[i].pending 
          },
          {
            title: '已确定',
            num: e.project_list[i].confirm
          },
          {
            title: '已完成',
            num: e.project_list[i].finish
          },
          {
            title: '已拒绝',
            num: e.project_list[i].refuse
          }
        ]
      }

      //数据拼接
      this.data.list.push(...e.project_list);

      //设置要渲染的数据
      this.setData({ 'list': this.data.list }, () => {
        wx.hideLoading();
      })

      //设置bottom_id
      if (e.project_list.length == 10) {
        this.data.project_id = e.project_list[9].code;
      } else {
        this.data.is_load_all = true;
        this.setData({ is_load_all: true });
      }

      //数据缺省
      if (this.data.project_id == '') {
        e.project_list.length == 0 ? this.setData({ 'un_data': true, is_load_all: false }) : [];
      }
    })
  },

  navbarTap: function (e) {
    this.setData({
      currentTab: e.currentTarget.dataset.idx
    })
  },
  /**
   * 页面的初始数据
   */
  data: {
    project_id:'',
    status:[],
    list: []
  },
  nav_demand_list(e) {
    var project_id = e.currentTarget.dataset.target_id;
    wx.navigateTo({
      url: '/pages/DemandSubList/DemandSubList?target_id=' + project_id,
    })

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    this.data.project_id = options.project_id;
    this.setData({ isShow: this.data.project_id == '' ? true : false, project_id: this.data.project_id });

    // wx.showLoading({ title: '加载中', mask: true });

    //登录
    app.login(() => {
      this.initData();
    });
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
    this.data.is_load_all = false;
    this.setData({ is_load_all: false });
    this.data.project_id = '';
    this.data.list = [];
    this.initData();
    wx.stopPullDownRefresh();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    if (this.data.is_load_all != true) {
      //加载数据
      wx.showLoading({ title: '加载中', mask: true });
      this.initData();
    }
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})