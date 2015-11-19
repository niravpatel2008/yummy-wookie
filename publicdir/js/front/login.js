function getLoginForm()
{
	$('#divConsLogin').modal();
	$("#txtConsusername,#txtConspassword,#txtConsForgotUsername").val("");
}
function getCreateAccountForm()
{
//	$('body').addClass('noscroll');
	$("#divCreateAccountForm").modal();
	$("#frmSignUp").validationEngine();
}
function clearCreateAccountForm()
{
	$("#consFirstName").val("");
	$("#consLastName").val("");
	$("#consEmail").val("");
	$("#consPassword").val("");
	$("#consPassword2").val("");
	$("#consPhone").val("");
}

function submitForgotPassword()
{
	clearPlaceHolder("frmConsForgot");
	var retFlag = $('#frmConsForgot').validationEngine('validate');
	if(!retFlag)
		return false;
	else
	{
		$("#divforPassError").html("Please wait.....");
		var url = "/app/login/handler.php";
		var data= {};
		data["op"]="forgotPassword";
		data["uname"]=$.trim($("#txtConsForgotUsername").val());
		$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(r)
				{
					data = $.trim(r);
					if(data == 1)
					{
						$("#divforPassError").html("We have emailed you a new, temporary password.");
						clearForm("frmConsForgot");
						//closeDialog('divConsLogin');
					}
					else if(data == "Update Failed" || data == "User Email Failed")
					{
						$("#divforPassError").html("Operation Failed !!! Please try again later !!!");
					}
					else
					{
						$("#divforPassError").html("Your account is inactive or not registered with system.");	
					}
				}
			});
	}	
}

function validateConsumerLogin()
{	
	clearPlaceHolder("frmConsLogin");	
	var retFlag = $('#frmConsLogin').validationEngine('validate');
	if(!retFlag)
		return false;
	else
	{
		var please_wait = lang['PLEASE_WAIT'];
		$("#divConsumerError").html(please_wait);
		var url = '/app/login/handler.php';
		var data = {};
		data['uname'] = $("#txtConsusername").val();
		data['password'] = $("#txtConspassword").val();
		data["op"] ="login";
			
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(r)
			{
				data = $.trim(r);
				//console.log(data);
				if(data == "0")
				{
					$("#divConsumerError").html("Incorrect Sign In Information");
					$('#divConsumerErrorBtn').hide();
					return false;
				}
				else if(data == "inactive")
				{
					$("#divConsumerError").html("Sorry Your Account has NOT been activated.<br/>Please Activate your account by clicking on the link provided to you in the email sent on registration.");
					$('#divConsumerErrorBtn').show();
					//return false;
				}
				else if(data == "1")
				{
					$.cookie("fromLogin","true");
					location.reload();
				}
			} 
		});
	}
}

function registerUser()
{
	var retFlag = $('#frmSignUp').validationEngine('validate');
	$("#divConsumerError1").html("");
	if(!retFlag)
		return false;
	else
	{
				var url = "/app/login/handler.php";
				var data= {};
				data["op"]="register";
				var fname = $("#consFirstName").val().split(" ");
				data["fname"]=fname[0];
				if (fname.length > 1)	
					data["lname"]=$("#consLastName").val();

				data["email"]=$("#consEmail").val();
				data["phone"]=$("#consPhone").val();
				data["password"]=$("#consPassword").val();
				data["comments"]=$("#consComment").val();

				if($('#language').length > 0)
					var please_wait = lang['PLEASE_WAIT'];
				else
					var please_wait = "Please wait.....";

				$("#divConsumerError1").html(please_wait);
				
				$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(r)
				{
					resp = $.trim(r);
					dataArr = resp.split("=^=");
					data = dataArr[0];
					if(data > 0)
					{
						alert("Thank you for registering!  We have sent a confirmation message to your email account.");
						location.reload();
					}
					else if(data == "User Already Exist")
					{
						$("#divConsumerError1").html("There is already an account under this email address.");	
						window.scroll(100,100);
					}
					else
					{
						$("#divConsumerError1").html("Operation Failed !!! Please try again later !!!");	
						window.scroll(100,100);
					}
					
				} 
			});

	}	
}
function rememberUserIdPassword(uname,password)
{
	strCookie = uname+";"+password;
	if($('input[name=chkConsRemember]').is(':checked'))
	{
		$.cookie("rememberUserIdPassword",strCookie,{ expires: 365});
	}
	else
	{
		$.cookie("rememberUserIdPassword","");
	}
}

/* GET FACEBOOK USER DETAILS */
function getFBUserInfo(FbUSerDetails)
{
	//alert(FbUSerDetails['name']);	
	var url = '/app/login/handler.php';
	FbUSerDetails["op"] ="getFBUserInfo";	
	$.ajax({
		url: url,
		type: 'POST',
		data: FbUSerDetails,
		success: function(r)
		{				
			data = $.trim(r);
			if(data == 0)
			{
				$("#divConsumerError").html("Incorrect Sign In Information");
				return false;
			}
			else if(data == "inactive")
			{
				$("#divConsumerError").html("Sorry Your Account has NOT been activated.<br/>Please Activate your account by clicking on the link provided to you in the email sent on registration.");
				return false;
			}
			else if(data == 1)
			{
				$.cookie("fromLogin","true");
				if($.trim($.cookie("currentUrl")) != "")
				{
					// this cookie is set when user click on save favorite link
					gblReferer = $.trim($.cookie("currentUrl"));
					location.reload();
				}
				else		
					location.reload();
			}			
		}
	});
}

var gblReferer=location.href;
$(document).ready(function() {
	
	$("#btnConsLogin").click(function(){ validateConsumerLogin();});
	$('#txtConsusername,#txtConspassword').keypress(function(event) {
	  if (event.keyCode == '13') {	validateConsumerLogin();	} 
	});

	//forgot password link
	$("#achConsForgot").click(function(){ 
		$("#divConsumerError").html("");
		$("#divforPassError").html("");
		$('#divConsLogin').modal('hide');
		$('#forgotPasswordDiv').modal();
	});
	
	//forgot password's cancel button
	$("#btnConsForgotcancel").click(function(){ 
		$("#divConsumerError").html("");
		$("#divforPassError").html("");
		$('#forgotPasswordDiv').modal('hide');
		$('#divConsLogin').modal();
	});

	$('#txtConsForgotUsername').keypress(function(event) {
	  if (event.keyCode == '13') {	submitForgotPassword();	} 
	});

	//submit forgot password
	$("#btnConsForgot").click(function() { submitForgotPassword(); });
		
	//register user
	$("#btnRegisterUser").click(function() { registerUser(); });

});