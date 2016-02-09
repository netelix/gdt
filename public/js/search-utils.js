$(function(){
	current_page;
	num_page;
	baselink;
	encryptedlink;
	$(".page-loader").click(function(){
		current_page++;
		num_page;
		encryptedlink;
		$(".next-page").load(baselink+"/"+current_page+ " .element-singleton", function() {
		  $(".next-page").addClass("page-"+current_page)
		  	.removeClass("next-page")
		  	.after("<div class='next-page'></div>");
		  	encryptedlink.init(window.baseclassename);
		  	FB.XFBML.parse();
		  	on_serp_page_loaded();
		  if(current_page == num_page){
			  $(".separator").remove();
		  }
		});
		return false;
	});
	
	$(".filtres input:checkbox").change(function(){
		window.location = $(this).parents("a").eq(0).attr("href");
	});
});

// Filter selector
