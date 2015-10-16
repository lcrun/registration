var submitWaitTime = 15;//second

$().ready(function(){
    var $messageTip = null;
    var messageTipId;
    var roomRecords = $.parseJSON($('#some_info').html());
    $('#some_info').empty();
    //显示提示信息
    function showmessage(text, duration, type){
        if($messageTip && $messageTip.text().length>0){
            $messageTip.empty().append('<span>'+text+'</span>');
            $messageTip.fadeIn(200);
            if(messageTipId){
                clearTimeout(messageTipId);
            }
        }else{
            $('#main').append('<div class="center_alert_tip"><span>'+text+'</span></div>'); 
            $messageTip = $('.center_alert_tip');
        }
        if(type === 'TIP'){
            $messageTip.addClass("success_tip");
            //console.log("success_tip");
        } else if($messageTip.hasClass("success_tip")){
            $messageTip.removeClass("success_tip");
        }
        messageTipId = setTimeout(function(){
            $messageTip.fadeOut(2000);
            messageTipId = null;
        }, duration);
    };
    
    $('#right').tooltip();
    
    $('#submit').click(function(e){
        var bid = $('.building option:selected').val();
        var rid = $('.classroom option:selected').val();
        var sname = $('.username').val();
        var detail = $('.mal_detail_text').val();
        //console.log(''+$('.building option:selected').text()+'\n'+$('.classroom option:selected').text()+'\n'+sname+'\n'+detail);
        if(!bid || bid === '-1'){
            showmessage('您还没有选择教学楼！', 3000);
            e.preventDefault();
            e.stopPropagation();
            return;
        }
        if(!rid || rid === '-1'){
            showmessage('您还没有选择教室！', 3000);
            e.preventDefault();
            e.stopPropagation();
            return;
        }
        //console.log('B'+detail+'E');
        detail = clearBrAndSpace(detail);
        //console.log('B'+detail+'E');
        if(!detail){
            showmessage('您还没有填写故障描述！', 3000);
            e.preventDefault();
            e.stopPropagation();
            return;
        }
        $('.building_hidden').val(bid);
        $('.classroom_hidden').val(rid);
    });

    $('.normal').click(function(e){
        var txt = $('.mal_detail_text').val();
        if(txt != ''){
            txt += "\n";
        }
        txt += $(this).text();
        $('.mal_detail_text').val(txt);
    });
    
    $('.building').change(function(e){
        var val = $('.building option:selected').val();
        var currentrooms = roomRecords[val];
        var content_html = "<option value='-1' selected>请选择教室</option>";
        if(currentrooms && currentrooms.length==1){//只有一个选择时，不要请选择这个提示
            content_html = "";
        }
        if(currentrooms){
            $.each(currentrooms, function(key, room) {
                //console.log(room.name);
                content_html += "<option value='"+room.id+"'>"+room.name+"</option>";
            });
        }
        $('.classroom').empty().html(content_html);
    });
    
    function showSucess(){
        showmessage('提交成功，我们会尽快安排工作人员处理，<br>感谢您对我们工作的支持！', 6000, 'TIP');
        //清空数据
        setTimeout(function(){
            //$("#classroom option[value=-1]").attr("selected", true);
            $(".classroom").val(-1);
            $('.username').val('');
            $('.mal_detail_text').val('');
        }, 1000);
        //限制提交问隔
        $('#submit').attr("disabled", true);
        $('#submit').removeClass("glow");
        var waitSecond = submitWaitTime;
        $('#count_down_tip').html("您还有 "+waitSecond+"秒才可再次提交！");
        $('#count_down_tip').show();
        var repeat = setInterval(function(){
            waitSecond = waitSecond-1;
            $('#count_down_tip').html("您还有 "+waitSecond+"秒才可再次提交！");
        }, 1000);//一秒钟更新一次提示
        setTimeout(function(){
            $('#submit').removeAttr("disabled");
            $('#submit').addClass("glow");
            $('#count_down_tip').hide();
            delete localStorage['last_submit_time'];
            clearInterval(repeat);
        }, submitWaitTime*1000);
    }
    
    function init(){
        /*var builds = roomRecords['builds'];
        var builds_html = "<option value='-1'>请选择教学楼</option>";
        if(builds){
            $.each(builds, function(key, build) {
                var selected = "";
                if(build.bid == page){
                    selected = " selected ";
                }
                builds_html += "<option value='"+build.id+"' "+selected+">"+build.name+"</option>";
            });
        }
        $('.building').empty().html(builds_html);
        
        var val = $('.building option:selected').val();
        var currentrooms = roomRecords[val];
        var content_html = "<option value='-1' selected>请选择教室</option>";
        if(currentrooms){
            $.each(currentrooms, function(key, room) {
                //console.log(room.name);
                content_html += "<option value='"+room.id+"'>"+room.name+"</option>";
            });
        }
        $('.classroom').empty().html(content_html);
        */
        if(isPost){
            var res = $.parseJSON($('#postres').html());
            $('#postres').empty();
            if(res.success){
                //not need, because redirect
            } else {
                showmessage('提交失败, 错误信息：'+res.msg, 5000);
            }
        }else if(submit_success){
            //console.log(""+new Date().getTime());
            localStorage['last_submit_time'] = parseInt(Date.now()/1000);
            showSucess();
        }else{
            if(localStorage['last_submit_time']){
                var last_time = parseInt(localStorage['last_submit_time']);
                var now = parseInt(Date.now()/1000);
                var dt = now - last_time;
                console.log('now = ' + now + '  last = ' + last_time + '  dt = ' + dt);
                if(dt >= 1 && dt <= submitWaitTime){
                    $('#submit').attr("disabled", true);
                    $('#submit').removeClass("glow");
                    var waitSecond = 15-dt;
                    $('#count_down_tip').html("您还有 "+waitSecond+"秒才可再次提交！");
                    $('#count_down_tip').show();
                    var repeat = setInterval(function(){
                        waitSecond = waitSecond-1;
                        $('#count_down_tip').html("您还有 "+waitSecond+"秒才可再次提交！");
                        //console.log('waitSecond = '+waitSecond);
                        if(waitSecond<1){
                            $('#submit').removeAttr("disabled");
                            $('#submit').addClass("glow");
                            $('#count_down_tip').hide();
                            delete localStorage['last_submit_time'];
                            //console.log('clear');
                            clearInterval(repeat);
                        }
                    }, 1000);//一秒钟更新一次提示
                }
            }
        }
    }
    init();
});
//JSON.parse()
//JSON.stringify()