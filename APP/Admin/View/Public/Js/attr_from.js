/**
 * Created by 18890 on 2016/4/4.
 */
$(function(){
    var cat_id      = $('#cat_id');
    var attr_group  = $('#attr_group');
    var attr_value  = $('#attr_values');
    var input_types = $('input[name="attr_input_type"]');
    cat_id.change(function () {
        $.ajax({
            url : url,
            type : 'post',
            data : {
                cat_id : $(this).val(),
            },
            dataType : 'json',
            success : function(json){
                attr_group.children('option').remove()
                if(json[0] != ''){
                    attr_group.parent().parent().css('display','table-row')
                    $.each(json, function (k, v) {
                        if(k == attr_group_number){
                            attr_group.append('<option value="' + k + '" selected>' + v + '</option>')
                        }else{
                            attr_group.append('<option value="' + k + '">' + v + '</option>')
                        }
                    })
                }else {
                    attr_group.parent().parent().css('display','none')
                }
            },
            error : function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
    })
    input_types.click(function () {
        if ($(this).val() == 1){
            attr_value.attr('disabled',false)
        }else{
            attr_value.attr('disabled',true)
        }
    })
    input_types.eq(attr_input_type).trigger('click')
    cat_id.trigger('change')
})