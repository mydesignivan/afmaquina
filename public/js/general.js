$(function(){
    var a;
    if( (a = $('#banner')).length>0 ){
        a.cycle({
            fx: "fade",
            slideExpr: "img"
        });
    }
});