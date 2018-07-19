
//无文章时撑开整个屏幕
if($(window).height()>$('html').height()) {
    var headH = $('#header_wrap').outerHeight();
    var footH = $('#footer_wrap').outerHeight();
    $('#xh_container').height($(window).height()-40-headH-footH + 'px');  //40为xh_container设置的margin值
}

//navi
$('#navi').children('ul').children('li').each(function () {
    if ($(this).children().is('ul')) $(this).children('a:first').addClass('havechild');
});
$('#navi').children('ul').children('li').hover(function () {
    $(this).children('ul').show();
}, function () {
    $(this).children('ul').hide();
});

//文字行数处理
function cutWords(obj,rowNum,cutNum) {

    obj.each(function() {
        if(isNaN($(this).css('line-height'))) {
            $(this).css('line-height','1.5');
        }
        var rowh = parseInt($(this).css('line-height'));
        var content = $(this).text();
        var conl = content.length;
        var conth = $(this).height();
        var mul = conth/rowh;
        var rowlength = Math.ceil(content.length/mul);
        if(mul > rowNum+1) {
            var content = content.substr(0,rowlength*(rowNum+1));
        }
        if(conth > rowNum*rowh) {
            while(conth > rowNum*rowh) {
                conl = conl-2;
                content = content.substr(0,conl);
                conl = content.length;
                $(this).text(content);
                conth = $(this).height();
            }
            var cut =  cutNum?cutNum:2;
            conl = conl-cut;
            content = content.substr(0,conl)+'...';
            $(this).text(content);
        }
    });
}