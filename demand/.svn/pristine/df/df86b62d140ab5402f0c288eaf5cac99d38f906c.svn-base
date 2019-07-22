 /**
 * @功能：通过html2canvas.js+jsPdf.js实现html导出pdf功能。
 * @开发者：zj
 * 2018-4-24
 */
//主体方法
var htmltopdfmain = {
	init: function(e) {
		//分页
        htmltopdfmain.page(e);
        
		htmltopdfmain.setListener(e);

	},
	//设置监听事件
	setListener: function(e) {
		var btnShare = document.getElementById(e.btnid); //下载按钮 
		btnShare.onclick = function() {
        	htmltopdfmain.iserror(e);//异常处理
			var myHeight = $("#"+e.domid);
			myHeight.css("width", e.dom_width);
			$('html, body').animate({//重置文档位置
		        scrollTop:0
		    }, 0);
			$('body').append(htmltopdfmain.mask);//添加遮罩层
			myHeight.removeClass("myHeight");
			htmltopdfmain.html2Canvas(e);
			myHeight.addClass("myHeight")
			myHeight.css("width", "100%");
		}
	},
	//获取像素密度，这个函数 就是下面设置的倍数 即scaleBy ,现在不调用这个函数 ，直接写死 就行
	getPixelRatio: function(context) {
		var backingStore = context.backingStorePixelRatio ||
			context.webkitBackingStorePixelRatio ||
			context.mozBackingStorePixelRatio ||
			context.msBackingStorePixelRatio ||
			context.oBackingStorePixelRatio ||
			context.backingStorePixelRatio || 1;
		return(window.devicePixelRatio || 1) / backingStore;
	},
	//绘制dom 元素，生成截图canvas
	html2Canvas: function(e) {
		(e.size === undefined)?[]:htmltopdfmain.pagesize=e.size;//是否有指定品质
		var shareContent = $("#"+e.domid); // 需要绘制的部分的 (原生）dom 对象 ，注意容器的宽度不要使用百分比，使用固定宽度，避免缩放问题
		var width = shareContent.outerWidth(); // 获取(原生）dom 宽度
		var height = shareContent.outerHeight(); // 获取(原生）dom 高
		/* var offsetTop = shareContent.offset().top; //元素距离顶部的偏移量
		var offsetLeft=shareContent.offset().left;//元素距离左边的偏移量*/
		shareContent.offset({
			top: 0,
			left: 0
		}); //这里必须这样处理，不然canvas 转成img会出现黑屏部分
		var canvas = document.createElement('canvas'); //创建canvas 对象
		var context = canvas.getContext('2d');
		var scaleBy = htmltopdfmain.pagesize; //这个比例 主要是为了防止 html转成canvas时出现模糊 ，越大越卡 ，最好是2
		//var scaleBy = htmltopdfmain.getPixelRatio(context); //获取像素密度的方法 (也可以采用自定义缩放比例)
		//这个canvas 的宽度和高度 可以设置偏移量 也可以不设置 设置偏移量时，改为canvas.width = (width + offsetTop)* scaleBy;高度类似
		canvas.width = (width) * scaleBy; //这里 由于绘制的dom 为固定宽度，居中，所以没有偏移
		canvas.height = (height) * scaleBy; // 注意高度问题，由于顶部有个距离所以要加上顶部的距离，解决图像高度偏移问题
		context.scale(scaleBy, scaleBy);
		var opts = {
			allowTaint: true, //允许加载跨域的图片
			tainttest: true, //检测每张图片都已经加载完成
			scale: scaleBy, // 添加的scale 参数
			canvas: canvas, //自定义 canvas
			logging: e.logging, //日志开关，发布的时候记得改成false
			width: width, //dom 原始宽度
			height: height //dom 原始高度
		};
		html2canvas(shareContent, opts).then(function(canvas) {
			//$("body").append(canvas);
			var context = canvas.getContext('2d');
			// 【重要】关闭抗锯齿
			context.mozImageSmoothingEnabled = false;
			context.webkitImageSmoothingEnabled = false;
			context.msImageSmoothingEnabled = false;
			context.imageSmoothingEnabled = false;
			$(canvas).css({
				"width": canvas.width / scaleBy + "px",
				"height": canvas.height / scaleBy + "px",
				"background-color": "white"
			});
			var imageData = context.getImageData(0, 0, canvas.width, canvas.height);
			//下面处理防止canvas转成img时 出现黑色背景，现在更改为白色背景
			for(var i = 0; i < imageData.data.length; i += 4) {
				// 当该像素是透明的，则设置成白色 
				if(imageData.data[i + 3] == 0) {
					imageData.data[i] = 255;
					imageData.data[i + 1] = 255;
					imageData.data[i + 2] = 255;
					imageData.data[i + 3] = 255;
				}
			}
			context.putImageData(imageData, 0, 0);
			/* 以上代码将html转成canvas完成，下面代码将canvas 转成pdf 按照A4的大小比例来转 以下数字 592.28代表宽度，841.89代表高度 */
			var contentWidth = canvas.width;
			var contentHeight = canvas.height;
			//一页pdf显示html页面生成的canvas高度; 
			var pageHeight = contentWidth / 592.28 * 841.89;
			//未生成pdf的html页面高度 
			var leftHeight = contentHeight;
			var imgWidth = 595.28;
			var imgHeight = 592.28 / contentWidth * contentHeight;
			//pdf页面偏移 
			var position = 0;
			//var img = Canvas2Image.convertToJPEG(canvas, canvas.width, canvas.height);
			var pageData = canvas.toDataURL('image/jpeg', 1.0);
			var pdf = new jsPDF('', 'pt', 'a4');
			//有两个高度需要区分，一个是html页面的实际高度，和生成pdf的页面高度(841.89) 
			//当内容未超过pdf一页显示的范围，无需分页 
			if(leftHeight < pageHeight) {
				pdf.addImage(pageData, 'JPEG', e.po.left, 0, imgWidth, imgHeight);
			} else {
				while(leftHeight > 0) {
					pdf.addImage(pageData, 'JPEG', e.po.left, position, imgWidth, imgHeight)
					leftHeight -= pageHeight;
					position -= 841.89+e.po.bottom;
					//避免添加空白页 
					if(leftHeight > 0) {
						pdf.addPage();
					}
				}
			}

			pdf.save(e.file_name+'.pdf');
			//恢复样式
			htmltopdfmain.homing(e);
		});
	},
	//分页
	page:function (e) {
		console.log()
		var page_size=$("#"+e.domid+" .page").length;
		if(e.size === undefined){//品质根据页面大小自适应
			if(page_size<5){
				htmltopdfmain.pagesize=3;
			}else if(page_size<10){
				htmltopdfmain.pagesize=2;
			}else{
				htmltopdfmain.pagesize=1;
			}
		}
		for(var i=0;i<page_size;i++){
            $("#"+e.domid+" .page").eq(i).append('<div class="aft-page">'+(i+1)+'/'+(page_size)+'</div>');
		}
   	},
   	//生成成功后重置样式
	homing:function (e) {
		$("#"+e.domid).attr("style","");
		$("#"+e.domid).removeClass("myHeight");
		$("#_mask").remove();
		clearInterval(htmltopdfmain.error_code);//清除计时器
    },
   	//遮罩层
	mask: '<div class="mask" id="_mask">'+
		  '	<div>生成中...稍后会自动为您下载。</div>'+
		  '</div>',
   	//响应超时（异常处理）
	iserror: function(e){
		  var i=0;
		  htmltopdfmain.error_code=setInterval(function(){
		  	i++;
		  	//console.log(i)
		  	if(i>15){
		  		htmltopdfmain.homing(e);
		  		alert("生成pdf失败，文档页数超出最大限制。");
		  	}
		  },1000)
	},
   	//响应超时（计时器）
	error_code:'',
   	//pdf品质
	pagesize:'',
};