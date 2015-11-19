$(document).ready(function(){
	//alert("test");
	$('#search_results').html('');
	salvattore.init();
	var url = base_url()+'deals/search/';
	var tags = $('#tags').val();
	var category = $('#category').val();
	var limit  = ($('#limit').length > 0)?$('#limit').val():4;
	$.post(url,{tags:tags,category:category,limit:limit,or:"1"},function(result){
		displayDealsData(JSON.parse(result));
	});
	$('.btn-buy').on('click',function(){
		var qty = $(this).closest("tr").find(".qtyItem").val();
		buy_deal($(this).data('dealid'),$(this).data('offerid'),qty);
	});

	var lat = $("#lat").val();
	var lng = $("#long").val();
	if (lat != "" && lng != "")
	{
		var latlng = new google.maps.LatLng(lat,lng);
		var myOptions = {
			zoom: 20,
			center: latlng,
			panControl: true,
			zoomControl: true,
			mapTypeControl: true,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
			},
			streetViewControl: true,
			overviewMapControl: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById('map'), myOptions);
		var marker = new google.maps.Marker({
			position: latlng,
			map: map,
			icon: 'https://chart.googleapis.com/chart?chst=d_map_spin&chld=0.7|0|FF0000|13|b|',
		});
	}

	/*Pagination*/
	$('.offer-page').hide();
	$('.page-1').show();
	$('.offer-pager .previous').on("click",function(){
		var currentpage = $('.offer-pager').data('currentpage');
		if (currentpage > 1)
		{
			currentpage=currentpage-1;
			$('.offer-page').hide();
			$('.page-'+currentpage).show();
			$('.offer-pager').data('currentpage',currentpage);
		}
	});
	$('.offer-pager .next').on("click",function(){
		var totalpage = $('.offer-pager').data('totalpage');
		var currentpage = $('.offer-pager').data('currentpage');
		if (currentpage < totalpage)
		{
			currentpage=currentpage+1;
			$('.offer-page').hide();
			$('.page-'+currentpage).show();
			$('.offer-pager').data('currentpage',currentpage);
		}
	});
});


function buy_deal (deal_id,offerid,qty) {
	if(!isLogin) {$("#buyofferpopup").modal('hide'); openLoginForm(); return; }
	url = base_url()+'buy/check_login';
	data = {deal_id:deal_id,offerid:offerid,qty:qty}
	$.post(url,data,function(data){
			if (data.indexOf("error") != -1) {
                alert("An error occured while processing, please try again later.");
                return false;
            }

			if (data == "login") {
				$("#buyofferpopup").modal('hide');
                openLoginForm();
                return false;
            }

			if (data == "address")
			{
				$("#buyofferpopup").modal('hide');
				$('#buyofferaddress').modal();
				return false;
			}

            if (data == "success") {
				$.post(base_url()+'buy/', {}, function (data) {
					if (data.indexOf("http") >= 0){
						location.href = data;
					}else{
						alert(data);
						return false;
					}
				});
            }
	});
}


$('#buy_step2').on('click',function(){
	$.post(base_url()+'buy/', $("#buystep2frm").serialize(), function (data) {

		if (data.indexOf("http") >= 0){
			location.href = data;
		}else{
			alert(data);
			return false;
		}
	});
});
