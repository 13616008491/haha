// pages/DemandList/DemandList.js
import api from '../../api/account'
var app = getApp()
Page({
  /**
   * 单选框数据
   */
  radioChange(e) {
    console.log('radio发生change事件，携带value值为：', e.detail.value)

  },

  //页面请求数据初始化
  initData() {
    //获取列表信息
    api.list.demand_my_list({ demand_status: this.data.demand_status, bottom_id: this.data.demand_id }, e => {

      // //设置页面标题
      console.log(e);

      //数据拼接
      this.data.list.push(...e.demand_list);

      //设置要渲染的数据
      this.setData({'list': this.data.list }, () => {
        wx.hideLoading();
      })

      //设置bottom_id
      if (e.demand_list.length == 10) {
        this.data.project_id = e.demand_list[9].demand_id;
      } else {
        this.data.is_load_all = true;
        this.setData({ is_load_all: true });
      }

      //数据缺省
      if (this.data.demand_id == '') {
        e.demand_list.length == 0 ? this.setData({ 'un_data': true, is_load_all: false }) : [];
      }
    })
  },


  navbarTap: function (e) {
    this.setData({
      demand_status:e.currentTarget.dataset.idx,
      currentTab: e.currentTarget.dataset.idx
    })
    this.data.demand_id = '';
    this.data.list = [];
    this.initData();
  },
  /**
   * 页面的初始数据
   */
  data: {
    navbar: ['总需求', '待定中', '进行中', '已完成', '已拒绝'],
    currentTab: 0,
    demand_id:'',
    demand_status:'',
    list:[]
  },
  nav_demand_detail(e) {
    var demand_id = e.currentTarget.dataset.target_id;

    wx.navigateTo({
      url: '/pages/Details/Details?target_id=' + demand_id,
    })

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    wx.showLoading({ title: '加载中', mask: true });

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
    this.data.demand_id = '';
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
  // onShareAppMessage: function () {

  // }
})