/**
 * Created by 18890 on 2016/3/31.
 */
$(function(){
    var validate = {new_pwd:0,confirm_pwd:0,email:0};
    validate.username = action == 'edit' ? 1 : 0;
    validate.phone = module == 'User' ? 0 : 1;
    $("#user-from").submit(function(){
        if(validate.username == 1 && validate.new_pwd == 1 && validate.confirm_pwd == 1 && validate.phone == 1){
            return true;
        }
        if (action == 'add') $('input[name="username"]').trigger('blur')
        if (module == 'User') $('input[name="phone"]').trigger('blur')
        //验证新密码
        $("input[name='new_password']").trigger("blur");
        //验证确认密码
        $("input[name='confirm_password']").trigger("blur");
        return false;
    })
    if (action == 'add'){
        $('input[name="username"]').blur(function () {
            var username = $(this);
            username.next('span').remove()
            if (username.val().trim() == ''){
                username.after('<span class="error">用户名不得为空</span>')
                validate.username = 0;
            }else{
                $.ajax({
                    url : url + '/checkUsername',
                    type : 'post',
                    data : {
                        username : username.val().trim()
                    },
                    dataType : 'json',
                    success : function(json){
                        if(json['isset']){
                            username.after('<span class="error">用户名已存在</span>')
                            validate.username = 0;
                        }else{
                            validate.username = 1;
                        }
                    },
                    error : function(xhr,ajaxOptions,thrownError){
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                })
            }
        })
    }
    $('input[name="new_password"]').blur(function(){
        var new_pwd = $(this);
        new_pwd.next('span').remove()
        if (action == 'add' || new_pwd.val().trim() != ''){
            if (new_pwd.val().trim().length < 6){
                new_pwd.after('<span class="error">密码不得小于6位数</span>')
                validate.new_pwd = 0;
            }else{
                validate.new_pwd = 1;
            }
        }else{
            validate.new_pwd = 1;
        }
    })
    $("input[name='confirm_password']").blur(function(){
        var confirm_pwd = $(this);
        var new_pwd = $('input[name="new_password"]');
        confirm_pwd.next('span').remove()
        if (new_pwd.val().trim() != '' && confirm_pwd.val().trim() != new_pwd.val().trim()){
            confirm_pwd.after('<span class="error">密码不一致</span>')
            validate.confirm_pwd = 0;
        }else{
            validate.confirm_pwd = 1;
        }
    })
    $('input[name="email"]').blur(function () {
        var email = $(this);
        email.next('span').remove()
        if(email.val().trim() != ''){
            if (email.val().trim().match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)){
                $.ajax({
                    url : url + '/checkEmail',
                    type : 'post',
                    data : {
                        email : email.val().trim(),
                        user_id : $('input[name="user_id"]').val().trim()
                    },
                    dataType : 'json',
                    success : function(json){
                        if(json['isset']){
                            email.after('<span class="error">邮箱已注册</span>')
                            validate.email = 0;
                        }else{
                            validate.email = 1;
                        }
                    },
                    error : function(xhr,ajaxOptions,thrownError){
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                })
            }else{
                validate.email = 0;
                email.after('<span class="error">邮箱格式不正确</span>')
            }
        }else{
            validate.email = 1;
        }
    })
    if (module == 'User'){
        $('input[name="phone"]').blur(function(){
            var phone = $(this);
            phone.next('span').remove()
            if (phone.val().trim() == ''){
                validate.phone = 0;
                phone.after('<span class="error">手机号码不得为空</span>')
            }else{
                if(phone.val().trim().match(/^1[3|4|5|8][0-9]\d{4,8}$/)){
                    $.ajax({
                        url : url + '/checkPhone',
                        type : 'post',
                        data : {
                            phone : phone.val().trim(),
                            user_id : $('input[name="user_id"]').val().trim()
                        },
                        dataType : 'json',
                        success : function(json){
                            if(json['isset']){
                                phone.after('<span class="error">手机号已注册</span>')
                                validate.phone = 0;
                            }else{
                                validate.phone = 1;
                            }
                        },
                        error : function(xhr,ajaxOptions,thrownError){
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    })
                }else {
                    validate.phone = 0;
                    phone.after('<span class="error">手机号格式不正确</span>')
                }
            }
        })
    }
});