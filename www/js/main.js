$(document).ready(function(){
    $(".select-search").select2({
        //allowClear: true,
        width: '100%'
    });
    
    $(".select-search2").select2({
        //allowClear: true,
        width: '100%'
    });
    
    $('.datepicker').datepicker({
        language: 'cs'
    });
    
    $('.checkbox input').iCheck({
        checkboxClass: 'icheckbox_square-green'
    });
});