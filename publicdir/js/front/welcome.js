var qryString;
var resultSet = {};
var qryStrHash = {};


function setCookies()
{
	$.cookie("tags",qryStrHash["tags"]);
	$.cookie("category",qryStrHash["category"]);

	//$.cookie("searchFor",qryStrHash["searchFor"]);
}

function getParamValue()
{
	qryString	= "";
	var selectedTags = $("#search_tags").val();

	//For Tags Type
	if(typeof(selectedTags) != "undefined")
	{
		qryStrHash["tags"] = $.trim(selectedTags);
		qryString += "tags_"+selectedTags+"/";
	}

	var selectedCategory = $("#category").attr('catid');

	//For Tags Type
	if(typeof(selectedCategory) != "undefined")
	{
		qryStrHash["category"] = $.trim(selectedCategory);
		qryString += "category_"+selectedCategory+"/";
	}

	//For Price Range
	/*if($(".fiterByTypeBx:visible").val() != "")
	{
		qryStrHash["sortType"] = $(".fiterByTypeBx:visible").val();
	}

	if($('#searchFor').length > 0)
		qryStrHash["searchFor"] = $('#searchFor').val();
	else
		qryStrHash["searchFor"] = "";*/
}

function getDealList(action)
{
	$("#loaderLogin").show();
	//setTopOfPage("hide"); // default Hide
	$("#noResultDiv").hide();

	if(action=='new'){
		$('#page').val('1');
		$('#search_results').html('');
		salvattore.init();
	}

	dealData = {};
	getParamValue();

	setCookies();

	/*********** Get Data Of Property *********/
	//var url = '/getSearchResult.php';
	var url = base_url()+'deals/search/';
	var data = {};

	//Set property hash into query srting
	$.each(qryStrHash, function(key, value) {
		data[key] = value;
	});

	data['page']=$('#page').val();

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		dataType: 'json',
		async: false,
		success: function(result)
		{
			$("#loaderLogin").hide();
			var page = $('#page').val();
			resultSet[page] = result;
			if($.trim(result) != "") {
				$("#noResultDiv").hide();
				displayDealsData(result);
			}
			else if($.trim(result) == "") {
				$("#noResultDiv").show();
				$('#totalRecordsCount').val("");
			}
		},
		error: function(xhr, textStatus, errorThrown){
			//console.log("Server error");
		}
	});
}

function setStartUp(){
	var tags = $.cookie("tags");
	if (typeof(tags) != 'undefined')
		$('#search_tags').val(tags);

	var category = $.cookie("category");
	if (typeof(category) != 'undefined')
	{
		category = (category == null)?"":category;
		$('#category').html($('.menu-browse a[catid="'+category+'"]').html());
		$('#category').attr('catid',category);
		$('.category_select img[data-catid='+category+']').addClass('active');
	}
}

$(function() {
	setStartUp();
	
	$('.menu-browse a').on('click',function(event){
		event.preventDefault();
		var selected = $(this).html().trim();
		$('#category').html(selected);
		var catid = $(this).attr('catid');
		$('#category').attr('catid',catid);
		$('.category_select img').removeClass('active');
		$('.category_select img[data-catid='+catid+']').addClass('active');
		getDealList('new');
	});

	var extparam = {};
	// CODE FOR MULTIPLE TAG SEARCH START HERE
	var options =
	{
		bitsOptions:
		{
			editable:
			{
				stopEnter: true,
				addOnBlur: true,
			}
		},
		plugins:
		{
			autocomplete:
			{
				minLength: 1,
				onlyFromValues:false,
				queryRemote: true,
				placeholder: "Type Keyword and select for search",
				maxResults:1000,
				remote:
				{
					url: './welcome/autosuggest',
					param: 'keyword',
					loadPlaceholder: 'Please wait...'
				}
			}
		},
		unique:true
	};

	searchBox = new $.TextboxList('#search_tags', options);

	// Add/Remove Bits
	var addBoxEvt = function(addedBox) {
			//searchBox.fromClear = false;
			//getPropList('new');

			// For placeholder
			/*if($('.textboxlist-bit-editable-input').length > 0)
			{
				$('.textboxlist-bit-editable-input').attr("placeholder","");

				if ($('#search_tags').val() != "")
					$('.textboxlist-bit-editable-input').css("width","30px");
				else
					$('.textboxlist-bit-editable-input').css("width","385px");
			}*/
			//setTextContainerMoreIcn();
			getDealList('new');
	};

	var removeBoxEvt = function(removedBox) {
			$.cookie("tags","");
			//if($("#search_tags").val() == "")
				//setAutoPlaceholder();
			//if (flag)
			//	getPropList('new');
			getDealList('new');
		//setTextContainerMoreIcn();
	};

	var bitBoxFocusEvt = function(removedBox) {
		removedBox.remove();
	};
	searchBox.addEvent("bitAdd", addBoxEvt);
	searchBox.addEvent("bitRemove", removeBoxEvt);
	searchBox.addEvent("bitBoxFocus", bitBoxFocusEvt);
	searchBox.addEvent("focus", function(){
			$('.textboxlist-autocomplete-results').show().scrollTop(0).hide();
	});

	$('.category_select img').on('click',function(){
		$('.category_select img').removeClass('active');
		$(this).addClass('active');
		catid = $(this).data("catid");
		$('#category').html($('.menu-browse a[catid="'+catid+'"]').html());
		$('#category').attr('catid',catid);
		getDealList('new');
	});

	getDealList('new');
});
