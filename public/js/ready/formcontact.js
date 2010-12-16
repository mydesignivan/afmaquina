$(document).ready(function(){
    var o = $.extend({}, jQueryValidatorOptDef, {
        rules : {
            txtName     : 'required',
            txtPhoneNum : 'required',
            txtEmail    : 'required'
        }
    });
    $('#form1').validate(o);
    formatNumber.init('#txtPhoneNum, #txtPhoneCode, #txtCelCode, #txtCelNum');
});