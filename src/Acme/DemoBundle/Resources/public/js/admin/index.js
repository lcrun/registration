$().ready(function(){
    var oldSearchTxt = $('.searchText').val();
    $('.formHidePage').empty();
    var listInfo = $.parseJSON($('#list_info').html());
    $('#list_info').empty();
    if(page == 1){
        $('#pre_page').attr("disabled", true);
        $('#pre_page_parent').addClass('disabled');
        $('#first_page').attr("disabled", true);
        $('#first_page_parent').addClass('disabled');
    }
    if(page == pages){
        $('#next_page').attr("disabled", true);
        $('#next_page_parent').addClass('disabled');
        $('#last_page').attr("disabled", true);
        $('#last_page_parent').addClass('disabled');
    }
    
    function requireFresh(){
        setTimeout(function(){
            $('.searchText').val(oldSearchTxt);
            $('#search').click();
        }, 20);
    }
    
    function gotoPage(page){
        console.log("goto page "+page);
        $('.formHidePage').val(page);
        requireFresh();
    }
    $('#first_page, #last_page, .page_item, #next_page, #pre_page').click(function(e){
        if($(this).attr("disabled")==true || $(this).attr("disabled")=="disabled"){
            console.log("disable");
            e.preventDefault();
            e.stopPropagation();
        } else {
            gotoPage($(this).attr("value"));
        }
    });
        
    function checkAll(checked){
        var checks=document.getElementsByName("check_one") ;
        for (var i=0; i<checks.length; i++){
            if(checks[i].type=="checkbox"){
                checks[i].checked=checked;
            }
        }  
    }
    
    function checkAndUpdate(){
        var count=0;
        var checks=document.getElementsByName("check_one") ;
        for (var i=0; i<checks.length; i++){
            if(checks[i].type=="checkbox"){
                if(checks[i].checked){
                    count++;
                    var val = checks[i].value;
                    //console.log(""+'#check_parent_'+val);
                    $('#check_parent_'+val).addClass("line_selected");
                } else {
                    var val = checks[i].value;
                    //console.log(""+'#check_parent_'+val);
                    $('#check_parent_'+val).removeClass("line_selected");
                }
            }
        }
        //console.log(count);
        if(count>0){
            $('.delete_button').text("忽略选中报修("+count+")");
            $('.delete_button').removeClass('disabled');
        }else{
            $('.delete_button').text("忽略选中报修");
            $('.delete_button').addClass('disabled');
        }
        
        if(count>=itemCount){
            $('#check_all')[0].checked = true;
        } else {
            $('#check_all')[0].checked = false;
        }
    }
    
    $('#check_all').click(function(e){
        //e.preventDefault();
        //e.stopPropagation();
        if($(this)[0].checked){
            checkAll(true);
            checkAndUpdate();
        }else{
            checkAll(false);
            checkAndUpdate();
        }
        
    });
    
    $('.check_one').click(function(e){
        checkAndUpdate();
    });
    
    $('#check_handle_0').click(function(e){
        //检查是符合至少选中一个
        var flag = true;
        if($('#check_handle_1')[0].checked){
            flag = false;
        }
        if($('#check_handle_2')[0].checked){
            flag = false;
        }
        if(flag){
            e.preventDefault();
            e.stopPropagation();
        } else{
            requireFresh();
        }
    });
    $('#check_handle_1').click(function(e){
        //检查是符合至少选中一个
        var flag = true;
        if($('#check_handle_0')[0].checked){
            flag = false;
        }
        if($('#check_handle_2')[0].checked){
            flag = false;
        }
        if(flag){
            e.preventDefault();
            e.stopPropagation();
        } else{
            requireFresh();
        }
    });
    $('#check_handle_2').click(function(e){
        //检查是符合至少选中一个
        var flag = true;
        if($('#check_handle_0')[0].checked){
            flag = false;
        }
        if($('#check_handle_1')[0].checked){
            flag = false;
        }
        if(flag){
            e.preventDefault();
            e.stopPropagation();
        } else{
            requireFresh();
        }
    });
    
    $('.check_building').click(function(e){
        //检查是符合至少选中一个
        var flag = true;
        var checks=document.getElementsByName("check_building") ;
        for (var i=0; i<checks.length; i++){
            if(checks[i].type=="checkbox" && checks[i]!=$(this)[0]){
                if(checks[i].checked){
                    flag = false;
                }
            }
        }
        if(flag){
            e.preventDefault();
            e.stopPropagation();
        } else{
            requireFresh();
        }
    });
    
    $('#search').click(function(e){
        //check building
        var builds={};
        var checks=document.getElementsByName("check_building") ;
        for (var i=0; i<checks.length; i++){
            if(checks[i].type=="checkbox"){
                if(checks[i].checked){
                    builds["'"+checks[i].value+"'"]=true;
                } else {
                    builds["'"+checks[i].value+"'"]=false;
                }
            }
        }
        var status={};
        status[0]=$('#check_handle_0')[0].checked;
        status[1]=$('#check_handle_1')[0].checked;
        status[2]=$('#check_handle_2')[0].checked;
        var hideTxt = {};
        hideTxt['builds']=builds;
        hideTxt['status']=status;
        $('.formHideTxt').val(JSON.stringify(hideTxt));
    });
    
    function setDialogInfo(info){
        $('.dialog_mal_info').attr("value", info['id']);
        $('#dialog_building').html(''+info['building']);
        $('#dialog_classroom').text(''+info['room']);
        $('#dialog_time').html(''+info['ctime']);
        $('#dialog_last_time').html(''+info['ltime']);
        if(info['smuser']){
            $('#dialog_subUser').html(''+info['smuser']);
        } else {
            $('#dialog_subUser').html('无');
        }
        $('.dialog_detail').html(''+info['content_intact']);
        if(info['note_intact']){
            $('.dialog_note').html(''+info['note_intact']);
        } else {
            $('.dialog_note').html('');
        }
        $('.dialog_status').html(''+info['status']);
        
        $('#dialog_confirm').html("标为处理中");
        $('#dialog_confirm_handled').html("标为已处理");
        $('#dialog_delete').show();
        if(info['statusId'] == 2){
            $('#dialog_confirm_handled').html("保存");
            //$('#dialog_delete').hide();
        } else if(info['statusId'] == 1){
            $('#dialog_confirm').html("保存");
        }
    }
    
    $('.button-handle').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var info = listInfo[$(this).attr("value")];
        if(info){
            setDialogInfo(info);
            openDetialDialog();
        }
        
    });
    
    function openDetialDialog(){
        enableTwoButton();
        $('.dialog_tip').hide();
        if($('#handModal').hasClass("modal_adapter")){
            $('#handModal').removeClass("modal_adapter");
        }
        var dialog_height = $('#handModal').height();
        var window_height = $(window).height();
        if(window_height < dialog_height){
            $('#handModal').addClass("modal_adapter");
        }
        $('#openDialog').click();
    }
    
    function disableTwoButton(){
        $('#dialog_confirm').attr('disabled', 'disabled');
        $('#dialog_confirm_handled').attr('disabled', 'disabled');
        $('#dialog_delete').attr('disabled', 'disabled');
    }
    
    function enableTwoButton(){
        $('#dialog_confirm').removeAttr('disabled');
        $('#dialog_confirm_handled').removeAttr('disabled');
        $('#dialog_delete').removeAttr('disabled');
    }
    
    function showDialogMsg(msg, type){
        if(type == 'ERROR'){
            $('.dialog_tip').addClass('error');
        } else {
            $('.dialog_tip').removeClass('error');
        }
        $('.dialog_tip').html(msg).show();
    }
    
    function handMal(id, status, note){
        console.log(''+id+'  '+status+'  '+note);
        $.ajax({
            type: 'POST',
            url: rootUrl + '/admin/handle',
            data: { 
                id: id, 
                action: 'modifyOne',
                status: status,
                note: note
            },
            dataType: 'json',
            timeout: 5000,
            async: true,
            success: function(data){
                //console.log(data);
                if(data.success){
                    showDialogMsg("修改成功！正在更新显示...", 'SUCCESS');
                    window.location.reload(true);
                } else {
                    showDialogMsg("修改失败，错误提示："+data.msg, 'ERROR');
                    //window.location.reload(true);
                    enableTwoButton();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log("error " + textStatus);
                showDialogMsg("网络或服务器异常！", 'ERROR');
                enableTwoButton();
            }
        });
    }
    
    $('#dialog_confirm_handled').click(function(e){
        disableTwoButton();
        var id =  $('.dialog_mal_info').attr("value");
        var status = 2;
        var note = $('.dialog_note').val();
        handMal(id, status, note);
    });
    
    $('#dialog_confirm').click(function(e){
        disableTwoButton();
        var id =  $('.dialog_mal_info').attr("value");
        var status = 1;
        //parseInt($("input[name='dialog_status']:checked").val());
        var note = $('.dialog_note').val();
        handMal(id, status, note);
    });
    
    $('#dialog_delete').click(function(e){
        disableTwoButton();
        var id =  $('.dialog_mal_info').attr("value");
        console.log(''+id);
        $.ajax({
            type: 'POST',
            url: rootUrl + '/admin/handle',
            data: { 
                id: id, 
                action: 'deleteOne'
            },
            dataType: 'json',
            timeout: 5000,
            async: true,
            success: function(data){
                //console.log(data);
                if(data.success){
                    showDialogMsg("删除成功！正在更新显示...", 'SUCCESS');
                    window.location.reload(true);
                } else {
                    showDialogMsg("删除失败，错误提示："+data.msg, 'ERROR');
                    //window.location.reload(true);
                    enableTwoButton();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log("error " + textStatus);
                showDialogMsg("网络或服务器异常！", 'ERROR');
                enableTwoButton();
            }
        });
    });
    
    function deleteNoUseMals(){
        var ids = {};
        var count = 0;
        var checks=document.getElementsByName("check_one") ;
        for (var i=0; i<checks.length; i++){
            if(checks[i].type=="checkbox"){
                if(checks[i].checked){
                    var val = $(checks[i]).attr('mid');
                    ids[val]=val;
                    count++;
                }
            }
        }
        //console.log(JSON.stringify(ids));
        if(count>0){
            $.ajax({
                type: 'POST',
                url: rootUrl + '/admin/handle',
                data: { 
                    ids: ids, 
                    action: 'deleteMany'
                },
                dataType: 'json',
                timeout: 5000,
                async: true,
                success: function(data){
                    //console.log(data);
                    if(data.success){
                        console.log("勾选删除成功！正在更新显示...", 2000, 'TIP');
                        window.location.reload(true);
                    } else {
                        console.log("勾选删除失败，错误提示："+data.msg+'ERROR');
                        window.location.reload(true);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("error " + textStatus);
                    console.log("网络或服务器异常！"+'ERROR');
                }
            });
        }
    }
    
    $('.delete_button').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        if($('.delete_button').hasClass('disabled')){
            return;
        }
        var count = 0;
        var checks=document.getElementsByName("check_one") ;
        for (var i=0; i<checks.length; i++){
            if(checks[i].type=="checkbox"){
                if(checks[i].checked){
                    count++;
                }
            }
        }
        var window_height = $(window).height();
        var button_top = $('.delete_button').offset().top;
        var top = button_top-window_height/2;
        $('.alertBoxWrapper').css("top", top+"px");
        AlertBox.confirm("是否确定要忽略选中的这 <b>"+count+"</b> 条报修！", function(){
            deleteNoUseMals();
        }, function(){
            //console.log("no");
        });
    });
    
    function init(){
        $selectedPage = $('#page_'+page);
        $selectedPage.addClass('page-select');
        checkAndUpdate();
        $('.modal-footer').tooltip();
        //console.log(""+detail_need);
        if(detail_need){
            var detail_info = JSON.parse($('#detail_hide_info').html());
            $('#detail_hide_info').empty();
            var info = detail_info['info'];
            setDialogInfo(info);
            setTimeout(function(){
                openDetialDialog();
            }, 200);
        }
        $('#dialog_delete').tooltip();
    }
    init();
});