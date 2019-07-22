import request from './request';
import g from '../components/js/general';

export default{
    //用户及公共接口
    user:{
        //登录接口
      login: (para, data) => request.request('api/login/login',para,e=>{data(e)},true),

        //登录测试
      login_test: (para, data) => request.request('api/login/login_test',para,e=>{data(e)}),

    },
    //需求助手接口
    list:{

      //需求列表
      demand_list: (para, data) => request.request('api/demand/get_demand_list', para, e => { data(e) }, true),
      //我的需求列表
      demand_my_list: (para, data) => request.request('api/demand/get_my_demand', para, e => { data(e) }, true),
      //需求上传
      demand_upload: (para, data) => request.request('api/demand/add_demand', para, e => { data(e) }, true),
      //需求详情
      demand_detail: (para, data) => request.request('api/demand/get_demand_detail', para, e => { data(e) }, true),
      //项目列表
      project_list: (para, data) => request.request('api/project/get_project', para, e => { data(e) }, true),
      //上传图片
      get_oss_sign: (para, data) => request.request('api/user/get_oss_sign', para, e => { data(e) }, true),

    },
    //文件上传
    upload:{

        //上传语音
        voice:(para,data) => g.upload( para ,  "card/voice/" , e=>{data(e)} ),

        //上传头像
        avatar:(para,data) => g.upload( para , "card/avatar/" , e=>{data(e)} ),
        
        //上传展示照片
        show:(para,data) => g.upload( para , "picture/show/" , e=>{data(e)} ),

    }
    
}