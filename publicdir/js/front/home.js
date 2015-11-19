var propertyData;
var page;
var qryString;
var qryStrHash={};
var xhr;
var resultSet = {};
var clickedPropId;
var propCnt = 1;
var limit = 15;
var hideHeader = false;
var polygon = "";
var polygonArrs = Array();
var polygonBounds;
var wayPointsArr = [];
var contactAgentFlag = '';
var drawPloygonCoords = [];
var polygonDrawn = false;
var searchOptChange = false;

function clearForm(id)
{
     $("#"+id).find(':input').each(function() 
	 {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });
}

function loadFirstTime()
{
    if(window.location.hash)
	{				
		get_current_query_string(window.location.hash);
	}
}

function exploreEnter(searchType,event)
{
	if(event && event.keyCode == 13)
	{
		//$("#cszType").val("CS");
		if (autoCityCompResult.length != 0)
		{
			result = autoCityCompResult[0];
			$("#csz").val(result.value);	
		}
		if(searchType == "advanceSearch")
			callSearchPage();
		else
			$("#btnSearch").trigger("click");	
	}
}

function getPropList(action)
{
	if(searchOptChange == true)
	{
		searchOptChange = false;
	}
	$("#loaderLogin").show();
	setTopOfPage("hide"); // default Hide
	$("#noResultDiv").hide();

	if(action=='fromSearchBtn'){
		$('#page').val('1');
		$('#v_results').html('');
	}

	propertyData = {};	
	getParamValue();

	if(action=='fromSearchBtn')
		setCookies();
	else
		getCookies();
	
	/*********** Get Data Of Property *********/
	//var url = '/getSearchResult.php';
	var url = public_path ()+'js/front/response.js';
	var data = {};

	//Set property hash into query srting
	$.each(qryStrHash, function(key, value) { 
		data[key] = value;
	});

	data['limit']=limit;
	data['page']=$('#page').val();

	currentLocFlag = false;
	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		dataType: 'json',
		async: false,
		success: function(result)
		{
			$("#loaderLogin").hide();
			$("#loaderLogin_Bottom").hide();
			var page = $('#page').val();
			resultSet[page] = result;
			if($.trim(result) != "") {	
				hideHeader = true;
				$("#noResultDiv").hide();
		
				displayPropData(result);
			}
			else if($.trim(result) == "" && $('#GetTopProp-1').length <= 0) {
				callFrom = '';
				$("#noResultDiv").show();
				setTopOfPage("hide");
				$('#totalRecordsCount').val("");
			}
			$("#noRecTbl").hide();	
			$('#noRecTblMsg').html('');
			$('#gettingMoreResultsFlag').val('false');			
		},
		error: function(xhr, textStatus, errorThrown){
			//console.log("Server error");
		}
	});	
}
//Create Hash Of Property Search Parameter
function getParamValue()
{
	qryString	= "";	
	var selectedTags = $("#topick_tags").val();

	//For Tags Type
	if(typeof(selectedTags) != "undefined" && selectedTags != "")
	{
		qryStrHash["tags"] = $.trim(selectedTags);
		qryString += "tags_"+selectedTags+"/";
	}

	//For Price Range
	if($(".fiterByTypeBx:visible").val() != "")
	{
		qryStrHash["sortType"] = $(".fiterByTypeBx:visible").val();
	}
	
	if($('#searchFor').length > 0)
		qryStrHash["searchFor"] = $('#searchFor').val();
	else
		qryStrHash["searchFor"] = "";
}

function displayPropData(result)
{
	var article = "";
	var htmlStr = "";
	if(result.length>0 && result != ""){
		$("#noRecTbl").hide();	
	}
	$('#totalRecordsCount').val(result.totalRecordsCount);
	var flag = false;
	$.each(result, function(index,element)
	{
		if(index!='totalRecordsCount'){
			var priceLbl='';
			propertyData[index] = {};
			propertyData[index]['SRNO']=element.SRNO;
			propertyData[index]['MAIN_PHOTO']=element.MAIN_PHOTO;
			propertyData[index]['TAGS']=element.TAGS;
			propTagsArr = propertyData[index]['TAGS'].split(",");
			//alert("tags = "+propTagsArr[0]);
			propertyData[index]['MLS_NUMBER']=element.MLS_NUMBER;
			propertyData[index]['FULLADDRESS']=element.FULLADDRESS;
			propertyData[index]['ADDRESS']=element.ADDRESS;
			propertyData[index]['CITY']=element.CITY;
			propertyData[index]['STATE']=element.STATE;
			propertyData[index]['ZIP']=element.ZIP;
			propertyData[index]['LATITUDE']=element.LATITUDE;
			propertyData[index]['LONGITUDE']=element.LONGITUDE;			
		
			var mlsNo=element.MLS_NUMBER;
			
			article = "<div class='prop_div'>";
			if (!flag)
			{
				article += "<span class='GetTopProp' id='GetTopProp-"+$('#page').val()+"'></span>";
				flag = true;
			}
			
			article += "<article id=\"v_element_"+ element.SRNO +"\" data-id=\"" + element.SRNO + "\" class=\"item posrel\">";
			article += "<div class=\"imghov\">";
			recArray = {};
			recArray['DATASOURCE'] = element.DATASOURCE;
			recArray['MLS_NUMBER_TITLE'] = element.MLS_NUMBER_TITLE;
			recArray['FULLADDRESS'] = element.FULLADDRESS;
			recArray = JSON.stringify(recArray);

			article += "<div class=\"rescntr_corner\">";
						article += '<span title="Vote ThumbsUp" class="th_up nonactive" id="thumbsUp'+element.TERABITZ_ID+'" onclick="javascript:propertyLike('+ element.TERABITZ_ID +');"></span>';
						article += '<span class="th_down nonactive" style="display:none;" title="Vote ThumbsDown" id="thumbsDown'+element.TERABITZ_ID+'" onclick="javascript:propertyDisLike('+ element.TERABITZ_ID +');"></span>';
						article += '<span style="display:none;" id="thumbsInfo'+element.TERABITZ_ID+'">'+recArray+'</span>';
			article += "</div>";
			article += '<div class="imghov">';
				article += '<div class="left_rescntr_corner">';
						article += "<span>"+element.SRNO+"</span>";
					article += '</div>';
			article += '</div>';

			// Get direction corner
			//if($.cookie("directionTid") != "" && $.cookie("directionTid") != null)
			if(jQuery.inArray(element.TERABITZ_ID, wayPointsArr) >= 0)
			{
				var carClass = "hidecar";
			}
			else
			{
				var carClass = "showcar";
			}
			
			article += "<img class=\"thumb\" src=\"" + element.MAIN_PHOTO + "\" alt=\"Property Photo\">";
			article += "</div>";
				
			article += "<div class='viewallphotos'>";
			article += "<a onclick='javascript:requestShowingForm(\""+$('#page').val()+"\",\""+index+"\");' href='javascript:void(0);' class='l'>Request Showing</a>";

			article += "<a onclick='javascript:contactAgentForm(\""+$('#page').val()+"\",\""+index+"\");' href='javascript:void(0);' class='r'>Ask Question</a>";
		
			article += "</div>";
			
			article += "<br/><div class='viewallphotos clearboth'>";
			article += "<a onclick='javascript:getPropertyPhotos(\""+ element.TERABITZ_ID +"\");' href='javascript:void(0);'>View Details</a>";
			article += "</div><br/>";

			article += "<span id=\"v_itemtitle\" class=\"item_title pricetag\"><a href='javascript:void(0);' onclick='javascript:searchBox.add(\"$"+element.ORIGINALPRICE+"\");' class='pricetag'>"+$.trim(element.PRICE)+"</a></span></br>";

			element.STREET_NAME = $.trim(element.STREET_NAME.replace("'","&apos;"));
			article += "<span id=\"v_itemtitle\" class=\"item_title\"><a href='javascript:void(0);' onclick='javascript:searchBox.add(\""+element.STREET_NAME+"\");' class='propadd'>"+$.trim(element.ADDRESS)+"</a></span></br>";

			article += "<span id=\"v_itemtitle\" class=\"item_title\">";
				article += "<a href='javascript:void(0);' onclick='javascript:searchBox.add(\""+element.CITY+"\");' class='propadd'>"+element.CITY+", "+element.STATE+"</a>";
				article += " <a href='javascript:void(0);' onclick='javascript:searchBox.add(\""+element.ZIP+"\");' class='propadd'>"+element.ZIP+"</a>";
			article += "</span>";

			articleSpanHtml = "";
			articleDivHtml = "";

			if(propTagsArr.length)
			{
				var selectedTags = $("#topick_tags").val().split(",");
				for(var k=0;k<propTagsArr.length;k++)
				{
					if($.trim(propTagsArr[k]) == ("$"+$.trim(element.ORIGINALPRICE)) || propTagsArr[k].toLowerCase() == element.CITY.toLowerCase() || propTagsArr[k].toLowerCase() == element.ZIP.toLowerCase() ) {
					//console.log(propTagsArr[k] +"==="+element.ORIGINALPRICE);					
					}
					else {
						// Highlight selected tags
						var selClass="";
						var onClickFlag=true;

							if(selectedTags != "") {											
								var tag = $.trim(propTagsArr[k].toLowerCase());					
								for(var m=0;m<selectedTags.length;m++)
								{
									var tempTag = $.trim(selectedTags[m].toLowerCase());
									//console.log(tempTag+" ==== "+tag+" ==== "+tag.indexOf(tempTag));
									//if(tag.indexOf(tempTag) >= 0)
									tag = $.trim(tag.replace("\\'","'"));
									tag = tag.replace(/\ /g,"");
									//if($.trim(tag.toLowerCase()) == (tempTag))
									if(tag.indexOf(tempTag.replace(/\ /g,"")) >= 0)
									{
										selClass="dynamicFont";
										if (tag == tempTag.replace(/\ /g,""))
											onClickFlag=false;
										break;
									}
								}
							}

							tempTag = $.trim(propTagsArr[k].replace("\\'","&apos;"));
							if(onClickFlag)
								tagHtml = "<a href='javascript:void(0);' onclick='javascript:searchBox.add(\""+tempTag+"\");' class='txtPadding "+selClass+"'>"+tempTag+"</a>";
							else
								tagHtml = "<a href='javascript:void(0);' class='txtPadding "+selClass+"'>"+tempTag+"</a>";		
						
							match1 = /^[0-9.]+ Area Sq.ft./.test(tempTag);
							match2 = /^[0-9.]+ Beds/.test(tempTag);
							match3 = /^[0-9.]+ Baths/.test(tempTag);

							if (match1 || match2 || match3)
								articleSpanHtml += tagHtml;
							else
								articleDivHtml += tagHtml;
						}
				}
			}

			if (articleSpanHtml != "")
			{
				article += "<br><span id=\"v_itemtitle\" class=\"item_title\">";
				article += articleSpanHtml;
				article += "</span>";
			}

			if (articleDivHtml != "")
			{
				article += "<div id=\"v_itemtitle\" class=\"lbl_home_top\">";
				article += articleDivHtml;
				article += "</div>";
			}
			
			article+="</article></div>";
						
			if (typeof(salvattore) != 'undefined' && $('#page').val() != "1")
			{
				var grid = $('#v_results');
				salvattore['append_elements'](grid[0], [$(article)[0]]);	
			}
			else
				$('#v_results').append(article);

			propCnt++;
		}		
	});
	
	if (typeof(salvattore) != 'undefined' && $('#v_results article').length > 0 && $('#page').val() == "1" && $('#totalRecordsCount').val() != "")
		salvattore.init();

	if($('#totalRecordsCount').val() == (propCnt-1) && $('#GetTopProp-1').length > 0)
	{
			setTopOfPage("show");		
	}
}

function changeColumnUI()
{
	if (typeof(salvattore) != 'undefined' && $('#v_results article').length > 0 && $('#totalRecordsCount').val() != "")
		salvattore.render_layout();
}

function setCookies()
{
	$.cookie("tags",qryStrHash["tags"]);

	if(qryStrHash["sortType"] != "")
		$.cookie("sortType",qryStrHash["sortType"]);

	$.cookie("searchFor",qryStrHash["searchFor"]);
}

function getCookies()
{
	if($.cookie("loginUserTags")!= null && $.cookie("loginUserTags") != "" && ($.cookie("loginUserTags").indexOf("Your Recommendations") >= 0 || $.cookie("loginUserTags").indexOf("Your Thumbs Up") >= 0) )
	{
		qryStrHash["tags"] = $.cookie("loginUserTags");
	}
	else if($.cookie("tags") != "" && $.cookie("tags") != null && $.cookie("tags") != "null") {
		qryStrHash["tags"] = $.cookie("tags");
	}

	if($.cookie("sortType") != "" && $.cookie("sortType") != null) {
		qryStrHash["sortType"] = $.cookie("sortType");
		$(".fiterByTypeBx").val(qryStrHash["sortType"]);
	}

	if($.cookie("priceRange") != null) {
		qryStrHash["priceRange"] = $.cookie("priceRange");
		$(".priceRangeBx").val(qryStrHash["priceRange"]);
	}

	if($.cookie("searchFor") != null) {
		qryStrHash["searchFor"] = $.cookie("searchFor");		
		$("#searchFor").val(qryStrHash["searchFor"]);
	}
}

function clearCookies()
{
	$.cookie("tags","");
	$.cookie("priceRange","");
	$.cookie("loginUserTags","");	
	$.cookie("tid","");	
	$.cookie("searchFor","");
}

function setTopOfPage(flag)
{
	if($('.page_home_top').length == 0 && flag == "show")
	{	
		var article = '<div class="page_home_top">';
			article += '<article class=""><div class="imghov"><span class="noresulttxt">No more results<span><br/>';
			article += '<a href="#topOfPage">Top of Page</a></div></article>';
			article += '</div>';
		setTimeout(function(){
			if (typeof(salvattore) != 'undefined')
			{
				var grid = $('#v_results');
				salvattore['append_elements'](grid[0], [$(article)[0]]);	
			}
		},0);
	}
	if(flag == "show" && $('.page_home_top').length > 0)
		$(".page_home_top").show();
	else if(flag == "hide" && $('.page_home_top').length > 0)
		$(".page_home_top").hide();	
}

function setAutoPlaceholder()
{
	var placeholder = "Search by keywords";
	$('.textboxlist-bit-editable-input').attr("placeholder",placeholder);
	$('.textboxlist-bit-editable-input').css("font-size","12px");
	
	setTimeout(function(){
		if ($('#topick_tags').val() != "")
			$('.textboxlist-bit-editable-input').css("width","30px");
		else
			$('.textboxlist-bit-editable-input').css("width","470px");
	},0);
}

function closeAllDialog()
{
	$('#forgotPasswordDiv').modal('hide');
	$('#divAlertDlg').modal('hide');
	$('#divConsLogin').modal('hide');
}

function setAllTagsfromCookies()
{
	getCookies();
	if($.cookie("loginUserTags") != "" && $.cookie("loginUserTags") != null) {
		var tags = $.cookie("loginUserTags").split(",");
		//$.cookie("tags","");
		for(var m=0;m<tags.length;m++){
			if(tags[m] != "") {
				searchBox.add(tags[m]);
			}
		}
	}
	else if($.cookie("tags") != "" && $.cookie("tags") != null) {
		var tags = $.cookie("tags").split(",");
		$.cookie("loginUserTags","");
		for(var m=0;m<tags.length;m++){
			if(tags[m] != "") {
				searchBox.add(tags[m]);
			}
		}
	}
	else {
		getPropList('fromSearchBtn');
	}
}

function setTextContainerMoreIcn()
{
		$('.textboxlist-moreicn').removeClass('textboxlist-moreicn-up').addClass('textboxlist-moreicn-down');
		flag = false;
		//t_h = $('.textboxlist').offset().top + $('.textboxlist').height() - $(window).scrollTop() - 5;
		
		h = $('.textboxlist-bits').height('auto').height();
		$('.textboxlist-bits').height(30);

		if (h > 35)
			flag = true;
		
		if (flag == true)
		{
			if ($('.textboxlist-moreicn').length == 0)
			{
				$span = $('<span class="textboxlist-moreicn textboxlist-moreicn-down" open="false"></span>');
				$('.textboxlist').append($span);

				$('.textboxlist-moreicn').on('click',function(e){
					if ($('.textboxlist-bits').height() == 30)
					{
						$('.textboxlist-bits').height('auto');
						$(this).removeClass('textboxlist-moreicn-down').addClass('textboxlist-moreicn-up');
						$(this).attr('open',true);
					}
					else
					{
						$('.textboxlist-bits').height(30);
						$(this).removeClass('textboxlist-moreicn-up').addClass('textboxlist-moreicn-down');
						$(this).attr('open',false);
					}
					e.preventDefault();
					e.stopPropagation();
				});
			}
		}
		else
		{
			$('.textboxlist-moreicn').remove();
			$('.textboxlist-bits').height(30);
		}
}

function build_query_string(queryString)
{
	query_elements = [];
	$.each(queryString, function(key, value) {
		if(value != "" && value != "undefined")
			query_elements.push(key+"="+encodeURIComponent(value));
	});

	if($.cookie("tid") != "" && $.cookie("tid") != null)
		query_elements.push("tid" + "=" + encodeURIComponent($.cookie("tid")));

	if(query_elements.length > 0) {
		return "?" + query_elements.join("&");
	}
	else {
		return "";
	}
}

function get_current_query_string(queryStringHas)
{
	queryStringHas = queryStringHas.replace("#?","");	
	var queryStringArr = queryStringHas.split("&");
	
	$.each(queryStringArr, function(key, value) {		
		var paramArr = value.split("=");
		paramArr[1] = decodeURIComponent(paramArr[1]);
		if(paramArr[0] == "tid" && paramArr[1] !="null") {		
			$.cookie("tid",paramArr[1]);
		}
		else
			qryStrHash[paramArr[0]] = paramArr[1];
	});

	$(".priceRangeBx").val(qryStrHash['priceRange']);
	$("#fiterByTypeBx").val(qryStrHash['sortType']);
	$.cookie("tags",qryStrHash["tags"]);
	
	setAllTagsfromCookies()
}

function showAlert(msg)
{
	$('body').addClass('noscroll');
	$('#alertmsg').html(msg);
	$("#divAlertDlg").dialog("open");

	$("#divAlertDlg").show();
	$("#mainOverlay2").show();
}

function doSortBy()
{
	if($(".fiterByTypeBx:visible").val() == "odh" || $(".fiterByTypeBx:visible").val() == "odl") 
	{
		var tag = $('#topick_tags').val();
		if(tag.indexOf("Open Homes") == -1) {
			searchBox.add('Open Homes');
			return false;
		}
	}
	getPropList('fromSearchBtn');
}

function setupUifor(device)
{
	if(device == 'mobile')
	{
		$('.w8').hide();
		$('.w8mobile').show();
		$('.rescount').hide();
		$('.headerslide').hide();
		$('.mobileSlide').show();
	}else if (device == 'pc')
	{
		$('.w8').show();
		$('.w8mobile').hide();
		$('.rescount').show();
		$('.headerslide').hide();
		$('.pcSlide').show();
	}

	var mobilDevice = window.matchMedia("screen and (max-width: 480px)")
	if (!mobilDevice.matches){
		$('.searchOption').addClass('fixed');
		$('.srchMid').css('margin-top',$('.searchOption').height()+5);
		noFixedHeader = false;
	}
	else
	{
		$('.srchMid').css('margin-top',"15px");
		$('.searchOption').removeClass('fixed');
		noFixedHeader = true;
	}
}

$(document).ready(function(){
	//$('#rsDate').datepicker( {"dateFormat":'mm/dd/yy',minDate: new Date()});

	var deviceMql = window.matchMedia("screen and (max-width: 767px)");
	if(deviceMql.matches)
		setupUifor("mobile")
	else
		setupUifor("pc")

	deviceMql.addListener(function(m) {
		if(m.matches)
			setupUifor("mobile")
		else
			setupUifor("pc")
	});
})