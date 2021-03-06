function change_code(obj){
	$("#code").attr("src",verifyURL+Math.random());
	return false;
}
//登录验证  1为空   2为错误
var validate={username:1,password:1,code:1}
$(function(){
	$("#login").submit(function(){
		if(validate.username==0 && validate.password==0 && validate.code==0){
			return true;
		}
		//验证用户名
		$("input[name='username']").trigger("blur");
		//验证密码
		$("input[name='password']").trigger("blur");
		//验证验证码
		$("input[name='code']").trigger("blur");
		return false;
	})
})
$(function(){
	//验证用户名
	$("input[name='username']").blur(function(){
		var username = $("input[name='username']");
		if(username.val().trim()==''){
			username.parent().find("span").remove().end().append("<span class='error'>用户名不能为空</span>");
			return ;
		}
		$.ajax({
			url : CONTROL+"/checkusername",
			type : 'post',
			data : {
				username:username.val().trim()
			},
			dataType : 'json',
			success : function(json){
				if (json['error']){
					username.parent().find("span").remove().end().append("<span class='error'>" + json['error'] + "</span>");
				}else if(json['correct']){
					validate.username=0;
					username.parent().find("span").remove().end().append("<span class='correct'>" + json['correct'] + "</span>");;
				}
			},
			error : function(xhr,ajaxOptions,thrownError){
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		})
	})
	//验证密码
	$("input[name='password']").blur(function(){
		var password = $("input[name='password']");
		if(password.val().trim()==''){
			password.parent().find("span").remove().end().append("<span class='error'>密码不能为空</span>");
			return ;
		}
		password.parent().find('span').remove();
		validate.password=0;
	})
	//验证验证码
	$("input[name='code']").blur(function(){
		var code = $("input[name='code']");
		if(code.val().trim()==''){
			code.parent().find("span").remove().end().append("<span class='error'>验证码不能为空</span>");
			return ;
		}
		code.parent().find('span').remove();
		validate.code=0;
	})
})
