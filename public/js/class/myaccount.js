var Account = new (function(){

    /* PRIVATE PROPERTIES
     **************************************************************************/
     var _optval={};

    /* PUBLIC METHODS
     **************************************************************************/
    $(document).ready(function(){
        _optval = $.extend({}, jQueryValidatorOptDef, {
            rules : {
                txtEmailContact : 'required'
            }
        });
        $('#form1').validate(_optval);
    });

    /* PUBLIC METHODS
     **************************************************************************/
    this.showcontapass = function(el){
        $(el).parent().hide();

        $('#contPass').fadeIn('slow', function(){
            $('#txtPassOld, #txtPassNew, #txtConfirmPass').val('');
            $('#txtPassOld').focus();

            _optval.rules.txtPassOld = {
                required : true,
                remote : {
                   url  : get_url('panel/myaccount/ajax_check_pass/'),
                   type : "post"
                }
            };
            _optval.rules.txtPassNew = {
                required : true,
                password : true
            };
            _optval.rules.txtConfirmPass = {
                required : true,
                equalTo  : '#txtPassNew'
            };
            $('#form1').validate(_optval);
        });
    };

    /* PRIVATE METHODS
     **************************************************************************/

})();
