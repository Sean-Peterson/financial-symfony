$(document).ready(function(){
    $('.sub_form').change(function(){
        var hat = $('.subject_text').val();
        var something = $(this).find(":selected").val();
        $('.subject_text').val(hat + something);
    });
    $('.mes_form').change(function(){
        var cow = $('.message_text').val();
        var dog = $(this).find(":selected").val();
        $('.message_text').val(cow + dog);
    });
    $('.status').change(function(){
         $(this).closest('form').trigger('submit');
    });
    $(".leslie").click(function(){
        console.log("click being triggered");
    $(".leslie-bio").show();
    });
});
