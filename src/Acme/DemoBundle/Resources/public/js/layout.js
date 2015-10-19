var rootUrl = "";

//去掉字符串两端的空格 
function trim(str){ 
    return str.replace(/(^\s*)|(\s*$)/g, ""); 
}
//去除两端换行 
function clearBr(str){ 
    return str.replace(/(^<\/?.+?>)|([\r\n]*$)/g,""); 
} 
//去除两端换行 & 空格
function clearBrAndSpace(str){
    var old = str;
    str = trim(str);
    str = clearBr(str); 
    while(old !== str){
        old = str;
        str = trim(str); 
        str = clearBr(str); 
    }
    return str;
}
if(ie > 0){
    console.log("ie = ie"+ie);
    (function ($) {
        $.fn.placeholder = function (options) {
            var defaults = {
                pColor: "#BBB",
                pActive: "#BBB",
                pFont: "12px",
                activeBorder: "#080",
                posL: 8,
                zIndex: "99"
            },
            opts = $.extend(defaults, options);
            //
            return this.each(function () {
                if ("placeholder" in document.createElement("input")) return;
                $(this).parent().css("position", "relative");
                var isIE = true,
                    version = ie;

                //不支持placeholder的浏览器
                var $this = $(this),
                    msg = $this.attr("placeholder"),
                    iH = $this.outerHeight(),
                    iW = $this.outerWidth(),
                    iX = $this.position().left,
                    iY = $this.position().top,
                    oInput = $("<label>", {
                        "class": "test",
                        "text": msg,
                        "css": {
                            "position": "absolute",
                            "left": iX + "px",
                            "top": iY + "px",
                            "padding-left": opts.posL + "px",
                            "height": iH + "px",
                            "line-height": iH + "px",
                            "color": opts.pColor,
                            "font-size": opts.pFont,
                            "z-index": opts.zIndex,
                            "cursor": "text"
                        }
                    }).insertBefore($this);
                //初始状态就有内容
                var value = $this.val();
                if (value.length > 0) {
                    oInput.hide();
                };

                //
                $this.on("focus", function () {
                    var value = $(this).val();
                    if (value.length > 0) {
                        oInput.hide();
                    }
                    oInput.css("color", opts.pActive);
                    //

                    if(isIE && version < 9){
                        var myEvent = "propertychange";
                    }else{
                        var myEvent = "input";
                    }

                    $(this).on(myEvent, function () {
                        var value = $(this).val();
                        if (value.length == 0) {
                            oInput.show();
                        } else {
                            oInput.hide();
                        }
                    });

                }).on("blur", function () {
                    var value = $(this).val();
                    if (value.length == 0) {
                        oInput.css("color", opts.pColor).show();
                    }
                });
                //
                oInput.on("click", function () {
                    $this.trigger("focus");
                    $(this).css("color", opts.pActive)
                });
                //
                $this.filter(":focus").trigger("focus");
            });
        }
    })(jQuery);
}
$().ready(function(){
    var user = $('#time_tmp').html();
    $('#time_tmp').show();
    var initTime = function(){
        //like 欢迎您，今天是2014年7月16日 星期三，夏季学期
        var today=new Date();  
        var yy=today.getFullYear();  
        var mm=today.getMonth()+1; 
        var dd=today.getDate(); 
        var date = ''+yy+'年'+mm+'月'+dd+'日';
        var week = '星期'+'日一二三四五六'.charAt(today.getDay());
        var term = '';
        //if(mm>=7 && dd>=15 && mm<9){
        //    term = '，夏季学期';
        //} else if(mm<=7 && mm>=2){
        //    term = '，春季学期';
        //} else if(mm>=9 || mm<=1){
        //    term = '，秋季学期';
        //}//need to check
        var tmp_time = '欢迎您<font color="#C01F23">'+user+'</font>，今天是 '+date+' '+week+''+term;
        $('#time_tmp').html(tmp_time);
    };

    initTime();
    setInterval(initTime, 1000*60);//一分钟更新一次时间
    if(ie>0) { 
        $(":input[placeholder]").each(function(){
            $(this).placeholder();
        });
    }
    var window_height = $(window).height();//浏览器显示窗高度
    var bottom = $('#footer').offset().top+$('#footer').height();//显示内容最底部位置
    //console.log(bottom);
    var dd = window_height-bottom;//显示内容最底部位置距浏览器显示窗部部高度
    //如果屏幕太高，做一下适应，加大middle内容的padding top & bottom值
    if(dd>20 && dd<120){
        $('#middle').css("padding-top", dd/2);
        $('#middle').css("padding-bottom", dd/2);
    }
});