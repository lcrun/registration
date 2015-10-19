$().ready(function(){
    var builds = JSON.parse($('#hideinfo').html());
    //console.log(builds);
    
    function delBuildingSelectChanged(){
        var val = $('.select_building_del option:selected').val();
        var build = builds[val];
        if(build){
            var content_html = "";
            var rooms = build['rooms'];
            if(rooms){
                $.each(rooms, function(key, room) {
                    //console.log(room.name);
                    content_html += "<option value='"+room.id+"'>"+room.room+"</option>";
                });
            }
            $('.select_classrooms').empty().html(content_html);
            var content_html = build['exists'];
            $('.exist_classroom').empty().html(content_html);
        }
    }
    
    function addBuildingSelectChanged(){
        var val = $('.select_building_add option:selected').val();
        var build = builds[val];
        if(build){
            var content_html = build['exists'];
            $('.exist_classroom').empty().html(content_html);
        }
    }
    
    $('.select_building_del').change(function(e){
        delBuildingSelectChanged();
    });
    
    $('.select_building_add').change(function(e){
        addBuildingSelectChanged();
    });
    
    function disableClassRoomTwoButton(){
        $('#classroom_add').attr('disabled', 'disabled');
        $("#classroom_del").attr('disabled', 'disabled');
    }
    
    function enableClassRoomTwoButton(){
        $('#classroom_add').removeAttr('disabled');
        $('#classroom_del').removeAttr('disabled');
    }
    
    function showClassRoomMsg(msg, type){
        if(type == 'ERROR'){
            $('.classroom_tip').addClass('error');
        } else {
            $('.classroom_tip').removeClass('error');
        }
        $('.classroom_tip').html(msg).show();
    }
    
    $('#classroom_add').click(function(e){
        $('.classroom_tip').html("").hide();
        var buildingId =  parseInt($('.select_building_add option:selected').val());
        var room = trim($('#classroom_txt').val());
        if(room == ''){
            showClassRoomMsg("您还没填写教室名称!", 'ERROR');
            $('#classroom_txt').focus();
            return;
        }
        console.log(''+buildingId+' '+room);
        disableClassRoomTwoButton();
        $.ajax({
            type: 'POST',
            url: rootUrl + '/admin/classroom/manager',
            data: { 
                action: 'classroom_add',
                buildingId: buildingId, 
                room: room
            },
            dataType: 'json',
            timeout: 5000,
            async: true,
            success: function(data){
                //console.log(data);
                if(data.success){
                    showClassRoomMsg("添加成功！正在更新显示...", 'SUCCESS');
                    window.location.reload(true);
                } else {
                    showClassRoomMsg("添加失败，错误提示："+data.msg, 'ERROR');
                    //window.location.reload(true);
                    enableClassRoomTwoButton();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log("error " + textStatus);
                showClassRoomMsg("网络或服务器异常！", 'ERROR');
                enableClassRoomTwoButton();
            }
        });
    });
    
    $('#classroom_del').click(function(e){
        $('.classroom_tip').html("").hide();
        var buildingId =  parseInt($('.select_building_del option:selected').val());
        var roomId = parseInt($('.select_classrooms option:selected').val());
        console.log(''+buildingId+' '+roomId);
        disableClassRoomTwoButton();
        $.ajax({
            type: 'POST',
            url: rootUrl + '/admin/classroom/manager',
            data: { 
                action: 'classroom_del',
                buildingId: buildingId, 
                roomId: roomId
            },
            dataType: 'json',
            timeout: 5000,
            async: true,
            success: function(data){
                //console.log(data);
                if(data.success){
                    showClassRoomMsg("删除成功！正在更新显示...", 'SUCCESS');
                    window.location.reload(true);
                } else {
                    showClassRoomMsg("删除失败，错误提示："+data.msg, 'ERROR');
                    //window.location.reload(true);
                    enableClassRoomTwoButton();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log("error " + textStatus);
                showClassRoomMsg("网络或服务器异常！", 'ERROR');
                enableClassRoomTwoButton();
            }
        });
    });
    
    if(isSuperAdmin){
        function disableBuildingTwoButton(){
            $('#building_add').attr('disabled', 'disabled');
            $("#building_del").attr('disabled', 'disabled');
        }

        function enableBuildingTwoButton(){
            $('#building_add').removeAttr('disabled');
            $('#building_del').removeAttr('disabled');
        }

        function showBuildingMsg(msg, type){
            if(type == 'ERROR'){
                $('.building_tip').addClass('error');
            } else {
                $('.building_tip').removeClass('error');
            }
            $('.building_tip').html(msg).show();
        }
        $('#building_add').click(function(e){
            $('.building_tip').html("").hide();
            var name =  trim($('#building_add_txt').val());
            if(name == ''){
                showBuildingMsg("您还没填写教学楼名称!", 'ERROR');
                $('#building_add_txt').focus();
                return;
            }
            var number =  parseInt($('#building_add_number').val());
            if($('#building_add_number').val() == ''){
                showBuildingMsg("您还没填写教室代号!", 'ERROR');
                $('#building_add_number').focus();
                return;
            } else if((''+number) != $('#building_add_number').val()){
                showBuildingMsg("您填写的教室代号不是数字!", 'ERROR');
                $('#building_add_number').focus();
                return;
            }
            console.log(''+name+' '+number);
            disableBuildingTwoButton();
            $.ajax({
                type: 'POST',
                url: rootUrl + '/admin/classroom/manager',
                data: { 
                    action: 'building_add',
                    name: name, 
                    number: number
                },
                dataType: 'json',
                timeout: 5000,
                async: true,
                success: function(data){
                    //console.log(data);
                    if(data.success){
                        showBuildingMsg("添加成功！正在更新显示...", 'SUCCESS');
                        window.location.reload(true);
                    } else {
                        showBuildingMsg("添加失败，错误提示："+data.msg, 'ERROR');
                        //window.location.reload(true);
                        enableBuildingTwoButton();
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("error " + textStatus);
                    showBuildingMsg("网络或服务器异常！", 'ERROR');
                    enableBuildingTwoButton();
                }
            });
        });
        $('#building_del').click(function(e){
            $('.building_tip').html("").hide();
            var buildingId =  parseInt($('#building_del_select option:selected').val());
            console.log(''+buildingId);
            disableBuildingTwoButton();
            $.ajax({
                type: 'POST',
                url: rootUrl + '/admin/classroom/manager',
                data: { 
                    action: 'building_del',
                    buildingId: buildingId
                },
                dataType: 'json',
                timeout: 5000,
                async: true,
                success: function(data){
                    //console.log(data);
                    if(data.success){
                        showBuildingMsg("删除成功！正在更新显示...", 'SUCCESS');
                        window.location.reload(true);
                    } else {
                        showBuildingMsg("删除失败，错误提示："+data.msg, 'ERROR');
                        //window.location.reload(true);
                        enableBuildingTwoButton();
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("error " + textStatus);
                    showBuildingMsg("网络或服务器异常！", 'ERROR');
                    enableBuildingTwoButton();
                }
            });
        });
        
        function disableNormalTwoButton(){
            $('#normal_add').attr('disabled', 'disabled');
            $("#normal_del").attr('disabled', 'disabled');
        }

        function enableNormalTwoButton(){
            $('#normal_add').removeAttr('disabled');
            $('#normal_del').removeAttr('disabled');
        }

        function showNormalMsg(msg, type){
            if(type == 'ERROR'){
                $('.normal_tip').addClass('error');
            } else {
                $('.normal_tip').removeClass('error');
            }
            $('.normal_tip').html(msg).show();
        }
        
        $('#normal_add').click(function(e){
            $('.normal_tip').html("").hide();
            var info =  trim($('#normal_txt').val());
            if(info == ''){
                showNormalMsg("您还没填写故障描述信息!", 'ERROR');
                $('#normal_txt').focus();
                return;
            }
            console.log(''+info);
            disableNormalTwoButton();
            $.ajax({
                type: 'POST',
                url: rootUrl + '/admin/classroom/manager',
                data: { 
                    action: 'normal_add',
                    info: info
                },
                dataType: 'json',
                timeout: 5000,
                async: true,
                success: function(data){
                    //console.log(data);
                    if(data.success){
                        showNormalMsg("添加成功！正在更新显示...", 'SUCCESS');
                        window.location.reload(true);
                    } else {
                        showNormalMsg("添加失败，错误提示："+data.msg, 'ERROR');
                        //window.location.reload(true);
                        enableNormalTwoButton();
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("error " + textStatus);
                    showNormalMsg("网络或服务器异常！", 'ERROR');
                    enableNormalTwoButton();
                }
            });
        });
        $('#normal_del').click(function(e){
            $('.normal_tip').html("").hide();
            var normalId =  parseInt($('#normal_del_select option:selected').val());
            console.log(''+normalId);
            disableNormalTwoButton();
            $.ajax({
                type: 'POST',
                url: rootUrl + '/admin/classroom/manager',
                data: { 
                    action: 'normal_del',
                    normalId: normalId
                },
                dataType: 'json',
                timeout: 5000,
                async: true,
                success: function(data){
                    //console.log(data);
                    if(data.success){
                        showNormalMsg("删除成功！正在更新显示...", 'SUCCESS');
                        window.location.reload(true);
                    } else {
                        showNormalMsg("删除失败，错误提示："+data.msg, 'ERROR');
                        //window.location.reload(true);
                        enableNormalTwoButton();
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    console.log("error " + textStatus);
                    showNormalMsg("网络或服务器异常！", 'ERROR');
                    enableNormalTwoButton();
                }
            });
        });
    }
    
    delBuildingSelectChanged();
    addBuildingSelectChanged();
});