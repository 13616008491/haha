import api from './account'

const api_url = 'http://demand.dev.zhizhuguanjia.com'; //请求地址
const ws_url = "ws://"+api_url.replace(/http:\/\//,'')+"/wss"; //聊天服务器地址
const version = 1; //版本

// const api_url = 'https://card.juqiyoupin.com'; //请求地址
// const ws_url = "wss://"+api_url.replace(/http:\/\//,'')+"/wss"; //聊天服务器地址
// const version = 4; //版本

export default {
  c_url:ws_url,
  // 接口请求函数封装
  request:function(url,param,succ,is_load,err){

    //打开load层
    // is_load?wx.showLoading({title:'加载中',mask:true}):[];

    var that = this;
    param['version'] = version;

    wx.getStorageSync("token")!=''?param['token'] = wx.getStorageSync("token"):[];

    wx.request({
      url: api_url+'/'+url+'.html',
      data: param,
      // method:'POST',
      header: {
        'content-type': 'application/json'
        // 'cookie': wx.getStorageSync("cookie")
      },
      success: function(data){
        console.log(data);
        //判断接口有无返回Cookie
        data.header["Set-Cookie"]!==undefined?wx.setStorageSync('cookie',data.header["Set-Cookie"]):[];

        //关闭load层
        // wx.hideLoading();

        //调用失败
        if(data.statusCode != '200'){
          that.errorToast(data.errMsg+'['+data.statusCode+']');
          return ;
        }

        //接口没有返回任何东西
        if(data.data==''||data.data===undefined){
          return;
        }

        data = data.data;
        
        //成功回调
        if(data['_code'] == '0'){
          
          succ(data);

        }else{

          //登录状态过期
          if(data['_code'] == 'E0001'){

            //抛出提示
            // that.errorToast('登录状态失效，稍后自动重新登录。');
            //打开load层
            wx.showLoading({title:'正在登录',mask:true});
            // that.errorToast('正在登录');

            //设置token
            wx.setStorageSync('token','');

            //2秒后开始重新登录
            setTimeout(()=>{

              //重新登录
              wx.login({
                success:function(e){

                  //登录
                  api.user.login({'code':e.code},e=>{
                    
                    //设置token
                    wx.setStorageSync('token',e.token);

                    //关闭load层
                    wx.hideLoading();
                    
                    //跳转到需求列表
                    wx.redirectTo({
                      url: '../DemandList/DemandList'
                    })


                  })
                }
              })
            },1000)

            return;
          }

          //是否被禁用
          if(data['_code'] == 'E0002'){

            //抛出提示
            that.errorToast(data['_msg']);

            setTimeout(()=>{
              //跳转到需求列表
              wx.redirectTo({
                url: '/pages/DemandList/DemandList'
              });
            },2000)

            return;
          }

          err!==undefined?err(data):[];
          that.errorToast(data['_msg']);
            

        }

        
        
      },
      fail:function(data){
        that.errorToast(data.errMsg);
      },
      // complete:function(){
      //   //失败回调
      //   if(param.complete){
      //     param.complete();
      //   }
      // }
    })
  },
  errorToast:function(title){
    //TODO 由于默认只有success和loading两种，需自定义
    //wx.hideLoading();
    if(title === undefined||title == ''){
      return;
    }
    wx.showToast({
      title: title,
      icon:'none',
      duration: 2000
    })
  },
  //TODO icon的有效值（success，loading，none)默认不传为none
  successToast:function(title,icon){
    (icon === undefined) ? icon = 'none' : [];
    if(title === undefined||title == ''){
      return;
    }
    wx.showToast({
      title: title,
      icon: icon,
      duration: 2000
    })
  },

}