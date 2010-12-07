function MessageShowHide(parent, status, t){
    if( status && status!='' ){
        if( !t ) t=5000;
        if( status!='' ){
            var div = $(parent).find(status=="success" ? "div.success" : "div.error");
            if( div.is(':visible') ){
                setTimeout(function(){
                    div.slideUp('slow');
                }, t);
            }else{
                div.slideDown('slow', function(){
                    setTimeout(function(){
                        div.slideUp('slow');
                    }, t);
                });
            }
        }
    }
}

function ShowHide(sel){
    var div = $($(sel).attr('href'));
    if( div.is(':hidden') ) div.stop().slideDown('slow');
    else div.stop().slideUp('slow');
}

function get_url(url){return baseURI+url+url_suffix}

function clear_input(e, isPass){
    if (!e) e = window.event;
    if (e.target) var el = e.target;
    else if (e.srcElement) var el = e.srcElement;
    if( el.nodeType == 3 )  // defeat Safari bug
        el = el.parentNode;

    if( el && (el.getAttribute("attrInputClear")==null || el.getAttribute("attrInputClear")=="1") ){
        el.value = "";

        if( isPass && el.getAttribute("type").toLowerCase()!="password" ) {
            if( document.all ){
                var input = document.createElement("input");
                    input.setAttribute("type", "password");
                    if( el.name ) input.name = el.name;
                    if( el.id ) input.id = el.id;
                    if( el.className ) input.className = el.className;
                    input.value = el.value;
                    input.onfocus = el.onfocus;
                    input.onblur = el.onblur;
                    if( el.getAttribute("attrInputClear")!=null ) input.setAttribute("attrInputClear", el.getAttribute("attrInputClear"));

                el.parentNode.replaceChild(input, el);
                setTimeout(function(){input.focus();}, 800);

            }else el.setAttribute("type", "password");
        }
    }
    return false;
}

function set_input(e, text, isPass){
    if (!e) e = window.event;
    if (e.target) var el = e.target;
    else if (e.srcElement) var el = e.srcElement;
    if(	el.nodeType == 3 )  // defeat Safari bug
        el = el.parentNode;

    if( el ){
        if( el.value.length==0 ){
            if( isPass && el.getAttribute("type").toLowerCase()!="text" ) {
                var input = document.createElement("input");
                    input.setAttribute("type", "text");
                    if( el.name ) input.name = el.name;
                    if( el.id ) input.id = el.id;
                    if( el.className ) input.className = el.className;
                    input.value = el.value;
                    input.onfocus = el.onfocus;
                    input.onblur = el.onblur;

                el.parentNode.replaceChild(input, el);
                input.value = text;
                input.setAttribute("attrInputClear", "1");

            }else {
                el.setAttribute("type", "text");
                el.value = text;
                el.setAttribute("attrInputClear", "1");
            }

        }else el.setAttribute("attrInputClear", "0");
    }
    return false;
}
