$(function(){
  $("#book-by-phone").click(function(){
    var $this = $(this);
    $this.next('ol').show();
    $this.hide();
  });
});
