$().ready(function(){
    $('.view_search').tooltip();
    if(ie>0 && ie==8){
        var $modal = $('#handModal');
        $('body').append($modal);
    }
    var loadFlag = false;
    
    function addList(list, start, size){
        content_html = $('#listcontent').html();
        var i = start;
        $.each(list, function(key, item) {
            i = i+1;
            var trclass = 'line_odd';
            if(i%2 ==0){
                trclass = 'line_even';
            }
            var item_html = '<tr class="highlight '+trclass+'">'+
                '<td class="right padding_right">'+i+'</td>'+
                '<td class="center">'+item.building+'</td>'+
                '<td class="center">'+item.room+'</td>'+
                '<td>'+item.content+'</td>'+
                '<td class="center">'+item.ctime+'</td>'+
                '<td class="center">'+item.ltime+'</td>'+
                '<td class="center">'+item.status+'</td>'+
                '<td>'+item.note+'</td>'+
                '<td class="center no_border_right"><a class="detail_info" href="#" value="'+item.id+'">详情</a></td>'+
                '</tr></tr>';
            content_html += item_html;
            listInfo[key]=item;//添加listinfo
        });
        $('#listcontent').html(content_html);
        $('#count').text(''+i);
        $('.detail_info').click(detailInfoClick);//重新设置点击事件
    }
    
    function getMoreList(searchTxt, count){
        $.ajax({
            type: 'POST',
            url: rootUrl + '/viewmore',
            data: { 
                searchTxt: searchTxt, 
                count: count
            },
            dataType: 'json',
            timeout: 10000,
            async: true,
            success: function(data){
                //console.log(data);
                if(data.success){
                    addList(data.list, count, data.size);
                } else {
                    console.log('获取更多信息失败！'+data.msg);
                }
                $('.loading').hide();
                loadFlag = false;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log('获取更多信息失败！'+textStatus);
                $('.loading').hide();
                loadFlag = false;
            }
        });
    }
    
    $(window).scroll(function(e){
        if(loadFlag){
            return;
        }
        if(!$('#handModal').is(":hidden")){//弹出了对话框，就不响应此事件
            return;
        }
        var htmlHeight=$(document).height();
        var scrollTop=$(document).scrollTop();
        var windowHeight = $(window).height();
        if(scrollTop+windowHeight>=htmlHeight){
            //到达了底部
            var count = parseInt($('#count').text());
            //console.log('到达了底部, 加载.....'+(count%20));
            if((count%20) == 0 && count<500 && count>0){
                loadFlag = true;
                $('.loading').show();
                getMoreList($('.searchText').val(), count);
            } else {
               console.log('到达了底部, 没有什么可以再加载的了'); 
            }
        }
    });
    
    var listInfo = $.parseJSON($('#hidelist').html());
    $('#hidelist').empty();
    
    function setDialogInfo(info){
        $('.dialog_building').html(info['building']);
        $('.dialog_classroom').html(info['room']);
        $('.dialog_create_time').html(info['ctime']);
        $('.dialog_handle_time').html(info['ltime']);
        $('.dialog_status').html(info['status']);
        var smuser = info['smuser'];
        if(!smuser){
            smuser= "未填写";
        }
        $('.dialog_subuser').html(smuser);
        var name = info['handuser_name'];
        if(!name){
            name= "";
        } else {
            name+="&nbsp;";
        }
        var email = info['handuser_email'];
        if(!email){
            email= "";
        }
        var phone = info['handuser_phone'];
        if(!phone){
            phone= "";
        } else {
            phone+="<br>";
        }
        $('.dialog_handleuser').html(name+""+phone+""+email);
        $('.dialog_content_txt').val(info['content_intact']);
        var note = info['note_intact'];
        if(!note){
            note= "未填写";
        } 
        $('.dialog_note_txt').val(note);
    }
    
    function openDetialDialog(){
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
    
    var detailInfoClick = function(e){
        var id = $(this).attr("value");
        var info = listInfo[id];
        //console.log(""+info);
        if(info){
            setDialogInfo(info);
            openDetialDialog();
        }
    };
    
    $('.detail_info').click(detailInfoClick);
    
    var hide_detail_info = trim($('#hide_detail_info').html());
    $('#hide_detail_info').empty();
    if(hide_detail_info && hide_detail_info != null && hide_detail_info != ''){
        var info = $.parseJSON(hide_detail_info);
        if(info != null){
            setDialogInfo(info);
            setTimeout(function(){
                openDetialDialog();
            }, 200);
        }
    }
});
