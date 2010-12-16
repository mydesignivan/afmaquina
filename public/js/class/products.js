var
Products = new (function(){

    /* PRIVATE PROPERTIES
     **************************************************************************/
     var _working=false;
     var _parent_id=0;
     var _categorie_name='';
     var _formchange=false;
     var _j=0;     

    /* PUBLIC METHODS
     **************************************************************************/
    $(document).ready(function(){
        _refresh_treeview();
    });

    /* PUBLIC METHODS
     **************************************************************************/
     this.categorie_new = function(a){
         if( _working ) return false;
         var fn = 'ajax_form_categorie';
         if( a ) fn+='/'
         _show_form(fn, 'Nueva Categor&iacute;a', _callback_categories);
         return false;
     };

     this.categorie_edit = function(){
         if( _working ) return false;
         _show_form('ajax_form_categorie/'+_parent_id, 'Modificar Categor&iacute;a', _callback_categories);
         return false;
     };

     this.categorie_delete = function(){
         if( _parent_id==0 ){
             alert("Seleccione una categoría.")
             return false;
         }

        if( confirm('¿Confirma la eliminación?\n'+_get_title_name()) ){
            _Loader.show();
            $.post(get_url('panel/products/ajax_categories_del/'+_parent_id), function(data){
                if( data!="ok" ) alert("ERROR AJAX:\n\n"+data);
                else {
                    _show_treeview();
                    _clear();
                }
            });
        }
        return false;
     };

     this.products_new = function(){
         if( _working ) return false;
         _show_form('ajax_form_products/'+_parent_id, 'Nuevo Producto', _callback_product);
         return false;
     };

     this.products_edit = function(id){
         if( _working ) return false;
         _show_form('ajax_form_products/'+_parent_id+'/'+id, 'Modificar Producto', _callback_product);
         return false;
     };

     this.products_del = function(id){
        var txt="";
        if( !id ){
            id = [];
            var a=[];
            $('#tblList tbody input:checked').each(function(){
                id.push($(this).val());
                a.push($(this).parent().parent().find('td.cell2').text());
            });
            if( id.length==0 ) return false;
            id = id.join('/');
            txt = a.join(', ')
        }else txt = $('#tr'+id).find('td.cell2').text();

        if( confirm('¿Confirma la eliminación?\n'+txt) ){
            $.post(get_url('panel/products/ajax_products_del/'+id), function(data){
                if( data!="ok" ) $('#error').show();
                else _show_list();
            });
        }
        return false;
     }

     this.mark_items_all = function() {
        $('#tblList tbody input:checkbox').each(function(){
            this.checked = !this.checked;
        });
     };

     this.prueba = function() {
     };



    /* PRIVATE METHODS
     **************************************************************************/
    var _mark_item = function(){
        if( _working ) return false;

        var t = $(this);
        _parent_id = t.attr('id').substr(2);
        _categorie_name = $('#id'+_parent_id).text();

        if( _parent_id>0 ){
            if( _formchange ){
                if( confirm('¿Desea guardar las modificaciones?') ){
                    $("#form1").submit();
                    return false;
                }else _formchange=false;
            }

            $('#treeview span').removeClass('hover');
            t.addClass('hover');
            $('#linkCatEdit, #linkCatDel').show();
            _show_list();

        }else{
            _clear();
        }


        return false;
    };

    var _show_list = function(){
       if( _parent_id>0 ){
            _show_form('ajax_list_products/'+_parent_id, 'Listado de productos de: '+ _get_title_name(), function(){
                $('#cont-btn').hide();
                $('#sortable').sortable({
                    stop : function(){
                        _working = true;
                        $('#sortable').sortable( "option", "disabled", true );

                        var initorder = $(this).find('tr:first').attr('id').substr(2);

                        var arr = $(this).sortable("toArray");

                        _set_order('ajax_products_order', arr, initorder, function(){$('#sortable').sortable( "option", "disabled", false )});
                    },
                    handle : '.handle'
                }).disableSelection();
            });
       }
    };

    var _callback_product = function(){
        //Ejecuta el TinyMCE
        _exec_tiny();

        // Configura el Validador
        var rules = {
            txtName         : 'required',
            txtContent      : 'tinymce_required'
        };

        if( $('#products_id').val()=='' ) rules.txtThumb = 'required';

        var o = $.extend({}, jQueryValidatorOptDef, {
            rules : rules,
            submitHandler : _on_submit_products,
            invalidHandler : function(){}
        });
        $('#form1').validate(o);

        // Esto es para la galeria de imagen
        PictureGallery.initializer({
            sel_input      : '#txtUploadFile',
            sel_button     : '#btnUpload',
            sel_ajaxloader : '#ajax-loader1',
            sel_gallery    : '#gallery-image',
            sel_msgerror   : '#pg-msgerror',
            sel_inputitle  : '#pg-fields .pg-title',
            action         : get_url('panel/products/ajax_upload_gallery'),
            href_remove    : get_url('panel/products/ajax_upload_delete'),
            defined_size   : {
                width  : 130,
                height : 90
            },
            callback       : function(){
                $('a.jq-image').fancybox();
            }
        });
        $("#gallery-image").sortable({
            stop : function(){
                $('a.jq-image').fancybox();
            },
            revert: true,
            handle : '.handle'
        }).disableSelection();

        // Set FancyBox
        $('a.jq-image').fancybox();

        // Muestra la categoria padre
        $('#txtParentCat').html('<u>'+_get_title_name()+'</u>');

        _set_params();
    };

    var _callback_categories = function(){
        _exec_tiny();

        _validoptions = $.extend({}, jQueryValidatorOptDef, {
            rules : {
                txtCategorie  : 'required'
            },
            submitHandler : _on_submit_categories,
            invalidHandler : function(){}
        });
        $('#form1').validate(_validoptions);
        $('#txtParentCat').html('<u>'+_get_title_name()+'</u>');
        
        _set_params();
    };

    var _on_submit_categories = function() {
        var f = $('#form1');

        _Loader.show();

        var params = _get_params(f)+'&parent_id='+_parent_id;

        var a = _parent_id;
        var b = _categorie_name;
        $.post(f.attr('action'), params, function(data){
            _Loader.hide();
            if( data=="ok" ){
                _show_treeview(function(){
                    $('#error').hide();$('#success').show();
                    if( !$('#categories_id').val() ){
                        _categorie_name = $('#txtCategorie').val();
                        _parent_id = a;
                        $('#form1 input:text, #form1 textarea').val('');
                        tinyMCE.get('txtContent').setContent('');
                    }else{
                        _categorie_name = b;
                        var img = $('#cont-image-1 .ajaxupload-thumb');
                        img.attr('src', img.attr('src').replace('.tmp/', ''));
                        $('#image_thumb_old').attr('value', img.attr('src'));
                    }
                });
            }else{
                $('#success').hide();$('#error').show();
                alert("ERROR AJAX:\n\n"+data);
                _working=false;
            }
            $(document).scrollTop(0);
        });

        return false;
    };

    var _on_submit_products = function() {
        var f = $('#form1');

        if( $('#gallery-image li:visible').length==0 ){
            $('#pg-msgerror').html('Este campo es obligatorio.').show();
            return false;
        }
        
        _Loader.show();

        var params = _get_params(f);
        var data={gallery : {}};
        data.gallery.images_new = PictureGallery.get_images_new();
        if( $('#products_id').val() ) {
            data.gallery.images_del = PictureGallery.get_images_del();
            data.gallery.images_order = PictureGallery.get_orders();
            data.gallery.images_edit = PictureGallery.get_images_edit();
        }
        params+='&json='+JSON.encode(data);

        $.post(f.attr('action'), params, function(data){
            _Loader.hide();
            if( data=="ok" ){
                _formchange=false;
                _show_list();
            }else {
                var html = 'Se produjo un error en el servidor. ';
                if( data.indexOf('Error Nº')==-1 ){
                    html+= '<a href="javascript:void($(\'#divError\').slideDown())" class="link1">Mas detalle</a><div id="divError" class="clear hide">'+data+'</div>';
                }else html+=data;
                $('#error').html(html).show();
            }
            $(document).scrollTop(0);
        });

        return false;
    };

    /* PRIVATE FUNCTIONS (HELPERS)
     **************************************************************************/
    var _Loader={
        show : function() {
            $('#cont-products').css('opacity', '0.5');
            $('#busy').show();
            _working=true;

        },
        hide : function() {
            $('#cont-products').css('opacity', '1');
            $('#busy').hide();
            _working=false;
        }
    };

    var _refresh_treeview = function(){
        var tree = $("#treeview").treeview({
            collapsed: true
        });
        tree.find("span.file, span.folder").css('cursor', 'pointer').click(_mark_item);
        tree.find('.hitarea:first').trigger('click');

        _parent_id=0;
        _categorie_name = tree.find('span:first').text();

        var a = $('#treeview ul');
        a.sortable({
            stop : function(){
                _working = true;
                a.sortable( "option", "disabled", true );

                var initorder = $(this).find('li:first').attr('id').substr(2);

                var arr = $(this).sortable("toArray");

                _set_order('ajax_categories_order', arr, initorder, function(){
                    a.sortable( "option", "disabled", false );
                });
            }
        }).disableSelection();

    };

    var _get_params = function(f){
        var a = f.serialize().split('&');
        for( var i=0; i<=a.length-1; i++ ){
            if( /^txtContent=/.test(a[i])) {
                a[i] = "txtContent="+escape(tinyMCE.get('txtContent').getContent());
                break;
            }
        }
        return a.join('&');
    };

    var _exec_tiny = function(j){
        TinyMCE_init.width = '98%';
        TinyMCE_init.height = '200px';
        TinyMCE_init.mode = 'exact';
        TinyMCE_init.elements = 'txtContent';
        TinyMCE_init.handle_node_change_callback = function(){
            _j++;
            if( _j>1 ) _formchange=true;
        }
        tinyMCE.init(TinyMCE_init);
    };

    var _show_treeview = function(callback){
        $('#treeview ul:first').remove();

        $.get(get_url('panel/products/ajax_show_treeview'), function(data){
            $('#treeview li').append(data);

            _refresh_treeview();
            
            if( typeof(callback)=="function" ) callback();

            _Loader.hide();
            _formchange=false;
        });

    };

    var _show_form = function(segm, title, callback) {
         _Loader.show();
         $('#fieldset-form legend').html(title);         
         $('#cont-products').load(get_url('panel/products/'+segm), function(a){
             $(document).scrollTop(0);
              callback();
              _Loader.hide();
         });
    };

    var _clear = function(){
        $('#cont-products').empty();
        $('#fieldset-form legend').html('Productos');
        $('#cont-btn').hide();
    };

    var _set_order = function(func, arr, initorder, callback){
        $.post('panel/products/'+func, {rows : JSON.encode(arr), initorder : initorder}, function(data){
            _working = false;
            if( data!="success" ) alert('ERROR AJAX:\n\n'+data);
            else {
                if( typeof(callback)=="function" ) callback();
            }
        });
    };

    var _get_title_name = function(){
        return _categorie_name.replace(/\s\(\d\)$/, '');
    };

    var _set_params = function(){
        _j=0;
        $('#form1').find('input:text, input:file, textarea').bind('keyup', function(){_formchange=true});
        $('#cont-btn').show();
    };

})();
