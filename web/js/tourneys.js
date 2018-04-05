$(document).ready(function(){
  $(".location-choice #allT").click(function(){
    $('.hidden-code-type input[value="all"]').prop('checked', true);
  });
  $(".location-choice #virtualT").click(function(){
    $('.hidden-code-type input[value="virtual"]').prop('checked', true);
  });
  $(".location-choice #localT").click(function(){
    $('.hidden-code-type input[value="local"]').prop('checked', true);
  });

  if ($(".hidden-code-type input[value='all']").prop('checked'))
    $(".location-choice #allT").addClass("active");

  if ($(".hidden-code-type input[value='virtual']").prop('checked')) 
    $(".location-choice #virtualT").addClass("active");

  if ($(".hidden-code-type input[value='local']").prop('checked')) 
    $(".location-choice #localT").addClass("active");

  var id = $(".hidden-code-type input#tourneyoptions-game").val();
  $("#"+id).addClass('active');
});