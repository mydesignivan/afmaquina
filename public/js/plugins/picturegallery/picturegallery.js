var PictureGallery = new (function(){

   /* CONSTRUCTOR
    **************************************************************************/
   this.initializer = function(_params){
       params = $.extend({}, {
           sel_input      : '',
           sel_button     : '',
           sel_ajaxloader : '',
           sel_gallery    : '',
           sel_msgerror   : '',
           sel_inputitle  : '',
           action         : '',
           href_remove    : '',
           defined_size   : false,
           callback       : Function()
       }, _params);

       //Crea el form
       _form = $('<form action="'+params.action+'" method="post" enctype="multipart/form-data" target="picgalifr" style="border:1px solid red"></form>').hide();
       _iframe = $('<iframe id="picgalifr" name="picgalifr" src="about:blank"></iframe>').bind('load', _iframe_load);
       _form.append(_iframe);

       $('body').append(_form);

       $(params.sel_gallery+' li a.jq-removeimg').bind('click', _remove_image);
       $(params.sel_gallery+' li input.pg-title').keyup(function(){$(this).data('edit', true)});

  };

  /* PUBLIC METHODS
   **************************************************************************/
   this.upluad = function(){
        var input = $(params.sel_input);
        var parent = input.parent();
        if( input.val() ){
            var ext = input.val().replace(/^([\W\w]*)\./gi, '').toLowerCase();

            if( !(ext && /^(jpg|png|jpeg|gif)$/.test(ext)) ){
                alert('Error: Solo se permiten imagenes');
                return false;
            }

            $(params.sel_button)[0].disabled=true;
            $(params.sel_ajaxloader).show();

            var inputclone = input.clone(true);

            _form.find(':file').remove();
            input.prependTo(_form);
            parent.prepend(inputclone);
            _inputitle = $(params.sel_inputitle);
            _form.submit();
        }
        return false;
   };

   this.get_images_new = function(){
       var data = new Array();
        $(params.sel_gallery + ' li').each(function(){
            var li = $(this);
            var tagA = li.find('a.jq-image');
            var tagImg = tagA.find('img');

            if( li.data('au-newimg') ){
                var a=li.data('au-data');
                
                data.push({
                    image_full      : _get_filename(tagA.attr('href')),
                    image_thumb     : _get_filename(tagImg.attr('src')),
                    width           : a.width,
                    height          : a.height,
                    width_complete  : a.width_complete,
                    height_complete : a.height_complete,
                    title           : a.title
                });
            }
        });

        return data;
   };

   this.get_images_del = function(){
       return array_images_del;
   };

   this.get_orders = function(){
       var data = new Array();
       var n = 0;
        $(params.sel_gallery + ' li').each(function(){
            n++;
            var li = $(this);
            data.push({
                image_full : _get_filename(li.find('a.jq-image').attr('href')),
                order      : n
            });
        });
        return data;
   };

   this.get_images_edit = function(){
       var data = new Array();
       $(params.sel_gallery+' li').each(function(){
           var t=$(this);
           var i=t.find('input.pg-title');
           if( i.data('edit') ){
               data.push({
                   title      : i.val(),
                   image_full : _get_filename(t.find('a.jq-image').attr('href'))
               })
           }
       });
       return data;
   };

   this.reset = function(){
       array_images_del = new Array();
       $(params.sel_gallery + ' li').each(function(){
           $(this).data('au-newimg', false);
       });
   };


   /* PRIVATE PROPERTIES
    **************************************************************************/
    var params;
    var array_images_del = new Array();
    var _form=false;
    var _iframe=false;
    var _inputitle=false;

   /* PRIVATE METHODS
    **************************************************************************/
    var _iframe_load = function(){
        var content = this.contentDocument || this.contentWindow.document;
            content = content.body.innerHTML;

        if(content=='') return false;

        $(params.sel_button).show();
        $(params.sel_ajaxloader).hide();

        var data;
        try{
            eval('data = '+content);

        }catch(err){
            alert(content);
            return false;
        }

        if( !data ){
            $(params.sel_msgerror).html('El archivo no pudo llegar al servidor.').show();
        }else{
            if( data['status']=="success" ){
                $(params.sel_msgerror).hide();
                var ul = $(params.sel_gallery);
                var li = ul.find('li:first');

                if( ul.is(':visible') ) li = li.clone();

                var output = data['output'][0];

                li.find('a.jq-image').attr('href', output['href_image_full']).attr('title', _inputitle.val());
                var img = li.find('img:first');
                    img.attr('src', output['href_image_thumb']);

                if( !params.defined_size ){
                    img.attr('width', output['thumb_width']).attr('height', output['thumb_height']);
                }else{
                    img.attr('width', params.defined_size.width).attr('height', params.defined_size.height);
                }

                var audata = {
                    width           : output['thumb_width'],
                    height          : output['thumb_height'],
                    width_complete  : output['thumb_width_complete'],
                    height_complete : output['thumb_height_complete'],
                    title           : _inputitle.val()
                };

                if( !ul.is(':visible') ){
                    //li.find('a.jq-removeimg').bind('click', _remove_image);
                    li.data('au-data', audata);
                    li.data('au-newimg', true);
                    ul.show();
                }else{
                    ul.find('li:last').after('<li>'+li.html()+'</li>');
                    ul.find('li:last').find('a.jq-removeimg').bind('click', _remove_image);
                    ul.find('li:last').data('au-data', audata);
                    ul.find('li:last').data('au-newimg', true);
                }
                ul.find('li:last input.pg-title').val(_inputitle.val());
                _inputitle.val('');

                $(params.sel_input).val('');
                $(params.sel_button)[0].disabled=false;
                $(params.sel_ajaxloader).hide();
                params.callback();
                var a=$(params.sel_gallery).parent('div');
                a.scrollTop(a[0].scrollHeight);

            }else {
                var d=$(params.sel_msgerror);
                if( d.length>0 ) d.html(data['error'][0]['message']).show();
                else alert(data['error'][0]['message']);
            }
        }

        $(params.sel_button)[0].disabled=false;

        return false;
    };

    var _get_filename = function(str){
        var arr = str.split('/');
        return arr[arr.length-1].toLowerCase();
    };

    var _remove_image = function(e){
        e.preventDefault();

        if( confirm('¿Está seguro de quitar la imágen?') ){
            var li = $(e.target).parent().parent();

            var remove = function(){
                var ul = $(params.sel_gallery);
                if( ul.find('li').length==1 ){
                    ul.hide();
                }else li.remove();
            };

            var tagA = li.find('a.jq-image');
            var tagImg = tagA.find('img');
            var image_full = tagA.attr('href');
            var image_thumb = tagImg.attr('src');

            if( li.data('au-newimg') ){

                $.post(params.href_remove, {au_filename_image : image_full, au_filename_thumb : image_thumb}, function(data){
                    if( data=="ok" ){
                        remove();

                    }else alert("ERROR DELETE:\n"+data);
                });
            }else{
                array_images_del.push({
                    image_full  : _get_filename(image_full),
                    image_thumb : _get_filename(image_thumb)
                });
                remove();
            }
        }
    };


})();