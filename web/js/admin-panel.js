$(document).ready(function(){
  if ($("#newsaddform-type input[value='gaming']").prop('checked')) {
      $(".field-newsaddform-game").removeClass('hidden'); };
  $("#newsaddform-type").click(function() {
    if ($("#newsaddform-type input[value='gaming']").prop('checked')) {
      $(".field-newsaddform-game").removeClass('hidden');
    } else {
      $(".field-newsaddform-game").addClass('hidden');
      $("#newsaddform-game").val('');
    };
  });
});