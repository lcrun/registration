$().ready(function(){
    function enableMal(id){
        console.log('enableMal '+id);
        $.ajax({
            type: 'POST',
            url: rootUrl + '/admin/ignore/action',
            data: { 
                id: id
            },
            dataType: 'json',
            timeout: 5000,
            async: true,
            success: function(data){
                //console.log(data);
                if(data.success){
                    console.log("修改成功！正在更新显示...", 'SUCCESS');
                    window.location.reload(true);
                } else {
                    console.log("修改失败，错误提示："+data.msg, 'ERROR');
                    //window.location.reload(true);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log("error " + textStatus);
                console.log("网络或服务器异常！", 'ERROR');
            }
        });
    }
    
    $('.button-handle').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var id =  $(this).attr("value");
        enableMal(id);
    });
});