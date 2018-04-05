$(document).ready(function(){
  $(".show-pinfo").hover(function () {
    $(this).parent().next().toggle();
  });

  $(".location-choice ul li").click(function () {
    $(this).parent().children().removeClass("active");
    $(this).addClass("active");
  });

  $("#show-filters").click(function () {
    $(".filters").toggle(300);
  });
});