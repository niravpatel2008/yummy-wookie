var oTable;
var hTable;
$(document).ready(function() {
	$("#file-process-form").validationEngine();
	oTable = $('#cellareaTable').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": admin_path ()+'cellarea/ajax_list/',
			"type": "POST"
		},
		aoColumnDefs: [
		  {
			 bSortable: false,
			 aTargets: [ -1 ]
		  }
		]
	} );	

	if ($("#my-awesome-dropzone").length > 0)
	{
		setTimeout(function(){
				var myDropzone = Dropzone.forElement("#my-awesome-dropzone");
				myDropzone.on("success", function(file, res) {
					if (res.indexOf("Error:") === -1)
					{
						var file = JSON.parse(res);
						$(".file-upload").hide();
						$(".file-process").show();
						$("#file-name b").html (file.filename);
						$("#filename").val(file.filename_org)
						var optionsHtml = "<option value=''>Select</option>";
						for(key in file.header)
						{
								optionsHtml+="<option value='"+key+"'>"+file.header[key]+"</option>";
						}
						$(".file-process-field").html(optionsHtml);
					}
					else
					{
						var error = error_msg_box(res);
						$(".content-header").after(error);
					}
				});
		},1000)
	}

	$("#file-cancel").on("click",function(){
		$(".file-upload").show();
		$(".file-process").hide();
		$("#file-name b").html ("");
		$("#file-process-form input,#file-process-form select").val("");
	})

	$("#file-submit").on("click",function(e){
		e.preventDefault();
		if(!$('#file-process-form').validationEngine('validate'))
			return false;
		
		var formdata =$("#file-process-form").serializeArray();
		var data = {}
		for ( key in formdata )
		{
			data[formdata[key].name] = formdata[key].value;
		}

		var url = admin_path ()+'cellarea/process_file/';
		$.post(url,data,function(res){
				if (res.indexOf("Error:") === -1)
				{
					console.log(res);
					//location.reload();
				}
				else
				{
					var error = error_msg_box(res);
					$(".content-header").after(error);
				}
		})
	})
} );