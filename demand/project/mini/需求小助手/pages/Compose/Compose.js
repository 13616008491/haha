// pages/Compose/Compose.js
import api from '../../api/account'
// import api from '../../components/js/general.js'
var app = getApp()

Page({

  /**
   * 单选框数据
   */
  radioChange(e) {
    console.log('radio发生change事件，携带value值为：', e.detail.value)

    let _self = this

    const value = _self.data.priority_level

    if (e.detail.value == 3){

      wx.showModal({
        title: '提示',
        content: '模态弹窗',
        success: function (res) {
          if (res.confirm) {
            console.log('用户点击确定')

            _self.radioChangeSele(e.detail.value)

          } else {
            console.log('用户点击取消')

            _self.radioChangeSele(value)

          }
        }
      })
    }else{

      _self.radioChangeSele(e.detail.value)

    }
    
  },

  radioChangeSele(value){

    var items = this.data.items

    for (var i = 0; i < items.length; i++) {

      const valueNew = items[i].value

      if (valueNew == value){

        items[i].checked = true

      }else{

        items[i].checked = false

      }
    }

    this.setData({

      items: items,
      priority_level: value

    })

  },

  /**
   * 页面的初始数据
   */
  data: {
    project_id: '',
    items: [
      { value: '1', name: '普通', checked: 'true' },
      { value: '2', name: '急', checked: '' },
      { value: '3', name: '加急', checked: '' },
    ],

    priority_level:1,
    titleCount: 0,
    contentCount: 0,
    proposer_name: '',
    demand_describe: '',
    demand_photo_url:'',
    images: []
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    this.data.project_id = options.target_id;
    // this.setData()
  },

  handleTitleInput(e) {
    const value = e.detail.value
    this.data.proposer_name = value
    this.data.titleCount = value.length
    this.setData({
      proposer_name: this.data.proposer_name,
      titleCount: this.data.titleCount
    })
  },

  handleContentInput(e) {
    const value = e.detail.value
    this.data.demand_describe = value
    this.data.contentCount = value.length
    this.setData({
      demand_describe: this.data.demand_describe,
      contentCount: this.data.contentCount
    })
  },

  chooseImage(e) {
    wx.chooseImage({
      count: 9,
      sizeType: ['original', 'compressed'],
      sourceType: ['album', 'camera'],
      success: res => {
        const images = this.data.images.concat(res.tempFilePaths)
        this.data.images = images.length <= 9 ? images : images.slice(0, 9)
        this.setData({
          images:this.data.images
        })
      }
    })
  },

  removeImage(e) {
    const idx = e.target.dataset.idx
    this.data.images.splice(idx, 1)
    this.setData({
      images: this.data.images
    })
  },

  handleImagePreview(e) {
    const idx = e.target.dataset.idx
    const images = this.data.images

    wx.previewImage({
      current: images[idx],
      urls: images,
    })
  },

  submitForm(e) {
    const title = this.data.proposer_name
    const content = this.data.demand_describe
    const selValue = this.data.selValue
    let _self = this
    //取得新的图片资源
    var images = _self.data.images;

    if (title == '' || content == '') {
      wx.showToast({ title: '必填项不能为空', icon: 'none', duration: 2000 });
      this.setData({ is_submit: true });
      return;
    }

    //上传新的图片展示
    if (images.length > 0) {

      var newImages = [];
      //上传
      api.upload.show(images, e => {
        console.log(e);
        //数据拼接
        newImages.push(...e);
        //赋值图片展示
        _self.data.show_info = JSON.stringify(newImages);
        //容器置为空
        images = [];
        //赋值基本容器
        _self.data.images = newImages;

        for (var i = 0; i < images.length; i++){

          if(_self.data.demand_photo_url.length == 0){

            _self.data.demand_photo_url = images[i];
          }else{

            _self.data.demand_photo_url = _self.data.demand_photo_url + ',' + images[i];
          }

        }

        console.log( _self.data.demand_photo_url)
        api.list.demand_upload({
          project_id: _self.data.project_id,
          proposer_name: _self.data.proposer_name, 
          demand_describe: _self.data.demand_describe, 
          demand_photo_url: _self.data.demand_photo_url, 
          priority_level: _self.data.priority_level}, e => {
          wx.showToast({ title: '保存成功', icon: 'none', duration: 2000 });
          //设置首页更新
          // app.pageUpDate.open_card = true;
          setTimeout(() => {
            //返回上一页
            wx.navigateBack({
              delta: 1
            })
          }, 1000)
        });
      })
    }

    

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