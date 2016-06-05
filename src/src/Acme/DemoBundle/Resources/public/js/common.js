$(function(){

	//导航栏点击的添加和取消类
	$('.nav1 li a').click(function(){
		$(this).addClass('nav_active').parents().siblings().find('a').removeClass('nav_active');
	});

	//个人信息的导航栏的添加类和取消类

	$('.module_content_diff_operating li').click(function(){
		//$(this).find('a').css({'background-color':'#1874CD','color':'#fff'});
		$(this).find('a').addClass('module_content_diff_active');
		$(this).siblings().find('a').removeClass('module_content_diff_active');
	})

	var time;
	var count=0;
	//在浏览器刚刚进来加载的时候，让图片先滑动着
	slide();
	//鼠标在小圆圈上上浮的时候，取消自动滑动，并且图片跟着响应的挪动；
	$('.slide_flag').mouseover(function(){
		clearTimeout(time);
		$(this).css('background-color','#f70');
		var index=$(this).attr('id').substr(6);
		$('#pic_'+index).css('z-index',count++);
	}).mouseout(function(){
		$(this).css('background-color','#fff');
		$('#pic_'+index).css('z-index',count);
		slide();
	});

	function slide(){
		count++;
		if(count%3==1){
			$('#pic_1').css('z-index',count);
			$('#slide_1').css('background-color','#f70').siblings().css('background-color','#fff');
		}else if(count%3==2){
			$('#pic_2').css('z-index',count);
			$('#slide_2').css('background-color','#f70').siblings().css('background-color','#fff');
		}else{
			$('#pic_3').css('z-index',count);
			$('#slide_3').css('background-color','#f70').siblings().css('background-color','#fff');
		}

		time=setTimeout(function(){
			slide()
		},2000);
	}
	
	//当鼠标在图片上的时候需要暂停图片的自动移动
	$('.pic').mouseover(function(){
		clearTimeout(time);
	}).mouseout(function(){
		slide();
	});
	

	//点击联系我们的时候不跳转，直接闪烁显示
	$('#contact_us').click(function(){
		$('#contact_module').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn();
	});

	//点击个人中心的时候提示先登录在查看
	$('#personal_center').click(function(){
		$('#login_tips').show();
	});
	//点击确定消失
	$('#btn_suround').click(function(){
		$('#login_tips').hide();
	})
})
