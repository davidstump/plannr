$(document).ready(function() {
       
    $("#fakeFB").click(function() {
        $(".fb_button").click();
    })
    
    $("#friendlist").change(function() {
           $("#friends").submit();
    })
    
    $("#time-start").timepicker({});
    $("#time-end").timepicker({});
    
    $("#toggleBirthdays").click(function() {
        $(".birthday").fadeToggle();
        $(this).toggleClass("faded");
    });
    $("#toggleEvents").click(function() {
        $(".event").fadeToggle();
        $(this).toggleClass("faded");
    });
    $("#toggleConfirmed").click(function() {
        $(".confirmed-event").fadeToggle();
        $(this).toggleClass("faded");
    });
});
