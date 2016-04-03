/**
 * Created by 18890 on 2016/3/31.
 */
$(function(){
    var validate = {new_pwd:0,confirm_pwd:0};
    validate.username = action == 'edit' ? 1 : 0;
    $("#user-from").submit(function(){
        if(validate.username == 1 && validate.new_pwd==1 && validate.confirm_pwd==1){
            return true;
        }
        if (action == 'add'){
            $('input[name="username"]').trigger('blur')
        }
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
                    url : url,
                    type : 'post',
                    data : {
                        username : username.val().trim()
                    },
                    dataType : 'json',
                    success : function(json){
                        if(json['correct']){
                            username.after('<span class="error">用户名已存在</span>')
                            validate.username = 0;
                        }else if(json['error']){
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
});