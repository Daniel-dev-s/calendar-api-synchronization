import $ from 'jquery';
$(document).ready(function(){
    $('#showImportCalendarModal').on('click',function(){
        $('.import-calendar').show();
    });
    $('#close-modal').on('click',function(){
        $('.modal').hide();
    })
    $('.calendar-entity').on('click',function(){
        $('.modal').hide();
    })
});
