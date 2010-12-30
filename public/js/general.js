$(function(){
    var a;
    if( (a = $('#banner')).length>0 ){
        a.cycle({
            fx: "fade",
            slideExpr: "img"
        });
    }

    a = $('#sidebar-submenu li');
    $('#sidebar-menu li').each(function(i){
        a.eq(i).css('height', ($(this).innerHeight()+1) +'px');
    });

});