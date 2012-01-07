$(document).ready(function() {
    $("#friendlist").change(function() {
           $("#friends").submit();
    })
    
    $("#time-start").timepicker({});
    $("#time-end").timepicker({});
});
