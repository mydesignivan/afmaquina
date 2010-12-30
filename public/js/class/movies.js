var Movies = new (function(){

    /* CONSTRUCTOR
     **************************************************************************/
    $(document).ready(function(){
        $('#sortable').sortable({
            stop : function(){
                $('#sortable').sortable( "option", "disabled", true );

                var initorder = $(this).find('tr:first').attr('id').substr(2);

                var arr = $(this).sortable("toArray");

                $.post('panel/videos/ajax_order', {rows : JSON.encode(arr), initorder : initorder}, function(data){
                    if( data!="success" ) alert('ERROR AJAX:\n\n'+data);
                    else {
                        $('#sortable').sortable( "option", "disabled", false )
                    }
                });
            },
            handle : '.handle'
        }).disableSelection();
    });

    /* PUBLIC METHODS
     **************************************************************************/
     this.openwindow = function(type, id){
        $('#popup').html('<div class="ajaxloader"></div>').modal({
            opacity  : '20',
            persist  : true,
            position : ['10%',],
            onOpen : function(dialog){
                dialog.container.show();
                dialog.data.show();
                $.get(get_url('panel/videos/ajax_loadwin/'+(id ? type+'/'+id : type)), function(data){
                    dialog.data.html(data);
                    var f = $('#form-movie');
                    f.validate($.extend({}, jQueryValidatorOptDef, {
                        rules : {
                            txtTitle  : 'required',
                            txtCode : 'required'
                        },
                        submitHandler : function(form){
                            var a = $($('#txtCode').val());
                            if( a.is('object') ){
                                $('#txtCode').val(a.eq(0).find('param[name|="movie"]').eq(0).attr('value').replace(/\?.*$/, ''));
                            }else{
                                alert("El código ingresado no es correcto.");
                                return false;
                            }

                            $('#ajaxloader').show();
                            $.ajax({
                                type :'post',
                                url    :  get_url('panel/videos/'+(type=="new" ? "ajax_create" : "ajax_edit")),
                                data : f.serialize(),
                                success : function(data){
                                    if( data=="ok" ){
                                        location.href = get_url('panel/videos');
                                    }else{
                                        alert(data);
                                        $('#error').show();
                                        $('#ajaxloader').hide();
                                    }
                                },
                                error : function(){
                                    $('#ajaxloader').hide();
                                }
                            });

                        }
                    }));
                });
            }
        });
     };

     this.del = function(id){
        var txt="";
        if( !id ){
            id = [];
            var a=[];
            $('#tblList tbody input:checked').each(function(){
                id.push($(this).val());
                a.push($(this).parent().parent().find('td.cell3').text());
            });
            if( id.length==0 ) {
                alert("No se ha seleccionado ningun item.");
                return false;
            }
            id = id.join('/');
            txt = a.join(', ')
        }else txt = $('#tr'+id).find('td.cell3').text();

        if( confirm('¿Confirma la eliminación?\n'+txt) ) location.href = get_url('panel/videos/delete/'+id);

        return false;
     };

     this.mark_items_all = function(me) {
         var val = $(me)[0].checked;
        $('#tblList tbody input:checkbox').each(function(){this.checked = val;});
     };


    /* PRIVATE PROPERTIES
     **************************************************************************/

    /* PRIVATE METHODS
     **************************************************************************/

})();
