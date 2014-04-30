/* Czech translation for the jQuery Timepicker Addon */
/* Written by Ondřej Vodáček */
$(function() {
    $.timepicker.regional['cs'] = {
        timeOnlyTitle: 'Vyberte čas',
        timeText: 'Čas',
        hourText: 'Hodiny',
        minuteText: 'Minuty',
        secondText: 'Vteřiny',
        millisecText: 'Milisekundy',
        timezoneText: 'Časové pásmo',
        currentText: 'Nyní',
        closeText: 'Zavřít', 
        timeFormat: 'hh:mm',
        amNames: ['dop.', 'AM', 'A'],
        pmNames: ['odp.', 'PM', 'P'],
        ampm: false,
        showSecond: true,
        timeFormat: 'hh:mm:ss',
        stepMinute: 5,
        stepSecond: 30
    };
    $.datepicker.regional['cs'] = {
        closeText: 'Cerrar',
        prevText: 'Předchozí',
        nextText: 'Další',
        currentText: 'Hoy',
        monthNames: ['Leden','Únor','Březen','Duben','Květen','Červen', 'Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
        monthNamesShort: ['Leden','Únor','Březen','Duben','Květen','Červen', 'Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
        //monthNamesShort: ['Le','Ún','Bř','Du','Kv','Čn', 'Čc','Sr','Zá','Ří','Li','Pr'],
        dayNames: ['Neděle','Pondělí','Úterý','Středa','Čtvrtek','Pátek','Sobota'],
        dayNamesShort: ['Ne','Po','Út','St','Čt','Pá','So',],
        dayNamesMin: ['Ne','Po','Út','St','Čt','Pá','So'],
        weekHeader: 'Sm',
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        showWeek: true,
        changeMonth: true,
        changeYear: true,               
        yearSuffix: ''
    };  
 
    $.datepicker.setDefaults($.datepicker.regional['cs']);
    $.timepicker.setDefaults($.timepicker.regional['cs']);


});
function getTimepickerFromTo(){
    $('.timepicker_from_1').timepicker({
        onSelect: function( selectedDate ) {
            var to = $( ".timepicker_to_1" );
            if(selectedDate >= to.val() && to.val() != ''){
                $(this).css('background','red');
                to.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                to.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
    $('.timepicker_to_1').timepicker({
        onSelect: function( selectedDate ) {
            var from = $( ".timepicker_from_1" );
            if(selectedDate <= from.val() && from.val()!=''){
                $(this).css('background','red');
                from.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                from.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
    $('.timepicker_from_2').timepicker({
        onSelect: function( selectedDate ) {
            var to = $( ".timepicker_to_2" );
            if(selectedDate >= to.val() && to.val() != ''){
                $(this).css('background','red');
                to.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                to.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
    $('.timepicker_to_2').timepicker({
        onSelect: function( selectedDate ) {
            var from = $( ".timepicker_from_2" );
            if(selectedDate <= from.val() && from.val()!=''){
                $(this).css('background','red');
                from.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                from.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
    $('.timepicker_from_3').timepicker({
        onSelect: function( selectedDate ) {
            var to = $( ".timepicker_to_3" );
            if(selectedDate >= to.val() && to.val() != ''){
                $(this).css('background','red');
                to.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                to.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
    $('.timepicker_to_3').timepicker({
        onSelect: function( selectedDate ) {
            var from = $( ".timepicker_from_3" );
            if(selectedDate <= from.val() && from.val()!=''){
                $(this).css('background','red');
                from.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                from.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
    $('.timepicker_from_4').timepicker({
        onSelect: function( selectedDate ) {
            var to = $( ".timepicker_to_4" );
            if(selectedDate >= to.val() && to.val() != ''){
                $(this).css('background','red');
                to.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                to.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
    $('.timepicker_to_4').timepicker({
        onSelect: function( selectedDate ) {
            var from = $( ".timepicker_from_4" );
            if(selectedDate <= from.val() && from.val()!=''){
                $(this).css('background','red');
                from.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                from.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
    $('.timepicker_from_5').timepicker({
        onSelect: function( selectedDate ) {
            var to = $( ".timepicker_to_5" );
            if(selectedDate >= to.val() && to.val() != ''){
                $(this).css('background','red');
                to.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                to.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
    $('.timepicker_to_5').timepicker({
        onSelect: function( selectedDate ) {
            var from = $( ".timepicker_from_5" );
            if(selectedDate <= from.val() && from.val()!=''){
                $(this).css('background','red');
                from.css('background','red');
                $('.savebutton').attr('disabled','disabled');
            }else{
                $(this).css('background','white');
                from.css('background','white');
                $('.savebutton').removeAttr('disabled');
            }
        }
    });
       
    $('.timepicker').timepicker({
        timeFormat: 'hh:mm'
    });
}
function GetDatePicker(){
    
    
    $('.datepicker').datepicker();
    
    $('.datepicker_from_1').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_to_1" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $('.datepicker_to_1').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_from_1" ).datepicker( "option", "maxDate", selectedDate );
        }
    });   
    $('.datepicker_from_2').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_to_2" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $('.datepicker_to_2').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_from_2" ).datepicker( "option", "maxDate", selectedDate );
        }
    });
    $('.datepicker_from_3').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_to_3" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $('.datepicker_to_3').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_from_3" ).datepicker( "option", "maxDate", selectedDate );
        }
    });
    $('.datepicker_from_4').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_to_4" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $('.datepicker_to_4').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_from_4" ).datepicker( "option", "maxDate", selectedDate );
        }
    });
    $('.datepicker_from_5').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_to_5" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $('.datepicker_to_5').datepicker({
        onSelect: function( selectedDate ) {
            $( ".datepicker_from_5" ).datepicker( "option", "maxDate", selectedDate );
        }
    });  
      
}
function removeAllPicker(count){
    var i =0;
    while(i<=count){
        $('.datepicker_from_'+count).datepicker("destroy");
        $('.datepicker_from_'+count).attr("id", null);
        $('.datepicker_to_'+count).datepicker("destroy");
        $('.datepicker_to_'+count).attr("id", null);
        $('.timepicker_from_'+count).timepicker("destroy");
        $('.timepicker_from_'+count).attr("id", null); 
        $('.timepicker_to_'+count).timepicker("destroy");
        $('.timepicker_to_'+count).attr("id", null); 
        i++;
    }
    $('.datepicker').datepicker("destroy");
    $('.datepicker').attr("id", null);
    
}
$(document).ready(function () {
    GetDatePicker(1);
    getTimepickerFromTo(1);  
    
    $('.datetimepicker').datetimepicker();
    
    if($('#smtp_changer').val()!='2'){
        $('.only_smtp').css('display','none'); 
    }
    $('#smtp_changer').change(function(){    
        if($('#smtp_changer').val()=='1'){
            $('.only_smtp').css('display','none'); 
        }else{
            if($('#smtp_changer').val()!='2'){
                $('.only_smtp').css('display','none'); 
            }else{
                $('.only_smtp').css('display','block'); 
            }
        }
    });
    $(".check_all_mass").live('click',function(){
       
        var satisfied = $(".check_all_mass:checked").val();
        if (satisfied != undefined) $(".for_mass_delete").attr('checked', 'checked');
        else  $(".for_mass_delete").removeAttr('checked');
    
    
    });
    $("input[class='addRow']").live('click',function() {
        var rep_count = $('.repeatdiv').length;
        var next_rep = rep_count+1;
        if( rep_count <=5){
            removeAllPicker(rep_count);
            $('.repeatdiv:first').clone(false).insertAfter('.repeatdiv:last');
            $('.datepicker_to_'+rep_count+':last').attr("class", 'datepicker_to_'+next_rep);
            $('.datepicker_from_'+rep_count+':last').attr("class", 'datepicker_from_'+next_rep);
            $('.timepicker_to_'+rep_count+':last').attr("class", 'timepicker_to_'+next_rep);
            $('.timepicker_from_'+rep_count+':last').attr("class", 'timepicker_from_'+next_rep);
            GetDatePicker();
            getTimepickerFromTo(); 
        }else{
            alert('Více záznamů není z technických důvodů povoleno');
        }
    });
    $("input[class='delRow']").click(function() {
        if( $('.repeatdiv').length > 1){
            $('.repeatdiv:last').remove();
        }
    });
//$("input[class='savebutton']").attr('disabled','disabled');
});