// pages/Details/Details.js
Page({
  //页面请求数据初始化
  initData() {
    //获取列表信息
    api.list.demand_detail({ demand_id: this.data.demand_id }, e => {

      // //设置页面标题
      console.log(e);

    })
  },

  /**
   * 页面的初始数据
   */
  data: {
    demand_id:'',
    images:[
      'http://t2.hddhhn.com/uploads/tu/20150700/jxklgnkbhsf.jpg',
      'http://t2.hddhhn.com/uploads/tu/20150700/jxklgnkbhsf.jpg',
      'http://t2.hddhhn.com/uploads/tu/20150700/v45jx3rpefz.jpg',
      'http://t2.hddhhn.com/uploads/tu/20150700/jxklgnkbhsf.jpg',
      'http://t2.hddhhn.com/uploads/tu/20150700/jxklgnkbhsf.jpg'
    ],

    exhibition: true
  },

  handleImagePreview(e) {
    const idx = e.target.dataset.idx
    const images = this.data.images

    wx.previewImage({
      current: images[idx],
      urls: images,
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    this.data.demand_id = options.target_id;

    this.initData();
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

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})