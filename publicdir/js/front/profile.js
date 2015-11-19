function loadFavDeals()
{
	$('#search_results').html('');
	salvattore.init();
	var url = base_url()+'profile/getmyfav';
	$.post(url,{},function(result){
		displayDealsData(JSON.parse(result));
	});
}

$(document).ready(function(){
	if($('.fav_result').length > 0)
		loadFavDeals();

	$('.print-offer').on('click',function(){
		var uid = $(this).data('uid');
		var dealuniqueid = $(this).data('dealuniqueid');
		window.open(base_url()+'deals/getprint/'+uid+'/'+dealuniqueid,'_blank');
	})

	/*Pagination*/
	$('.deal-page').hide();
	$('.page-1').show();
	$('.deal-pager .previous').on("click",function(){
		var currentpage = $('.deal-pager').data('currentpage');
		if (currentpage > 1)
		{
			currentpage=currentpage-1;
			$('.deal-page').hide();
			$('.page-'+currentpage).show();
			$('.deal-pager').data('currentpage',currentpage);
		}
	});
	$('.deal-pager .next').on("click",function(){
		var totalpage = $('.deal-pager').data('totalpage');
		var currentpage = $('.deal-pager').data('currentpage');
		if (currentpage < totalpage)
		{
			currentpage=currentpage+1;
			$('.deal-page').hide();
			$('.page-'+currentpage).show();
			$('.deal-pager').data('currentpage',currentpage);
		}
	});
});
