/*      
	Begin Default Route Destination
*/

var delay_option = ['5', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55', '60', '65', '70', '75', '80', '85', '90', '95', '100', '105', '110', '115', '120', '125', '130', '135', '140', '145', '150', '155', '160', '165', '170', '175', '180', '185', '190', '195', '200']; 	


function deleteDefaultAttempt(formData, url){
	
	$.ajax({
		url : url,
		type: "POST",
		data : formData,
		enctype: 'multipart/form-data',
		success: function(data)
		{

			if($.isEmptyObject(data.error)){

                repaintAttempt(data.default);
								
			}else{
				printErrorMsg(data.error);
			}	

		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('error');
		}
	});

}

function storeDefaultAttempt(formData, url){

	$.ajax({
		url : url,
		type: "POST",
		data : formData,
		//async: false,
		enctype: 'multipart/form-data',
		success: function(data)
		{
			//var data = JSON.parse(data);

			if($.isEmptyObject(data.error)){

				repaintAttempt(data.default);
				$('#transfer_route').modal('hide');
				$("#phone-container").html('');

				
			}else{

				printErrorMsg(data.error);

			}	

		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('error');
		}
	});

}

function updateDefaultAttempt(formData, url){
	
	$.ajax({
		url : url,
		type: "POST",
		data : formData,
		//async: false,
		enctype: 'multipart/form-data',
		success: function(data)
		{
			//var data = JSON.parse(data);

			if($.isEmptyObject(data.error)){

				repaintAttempt(data.default);
				$('#edit_transfer_route').modal('hide');

				
			}else{

				printErrorMsg(data.error);

			}	

		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('error');
		}
	});

}


function saveDefault(formData, url){
	
	
	$.ajax({
		url : url,
		type: "POST",
		data : formData,
		//async: false,
		enctype: 'multipart/form-data',
		success: function(data)
		{
			//var data = JSON.parse(data);

			if($.isEmptyObject(data.error)){

				printFlashMsg(data.success);                        
										
			}else{

				printErrorMsg(data.error);

			}	

		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('error');
		}
	});

}


function appendPhone(target, source){

	var span = "<i class='fa fa-phone'></i> <span style='padding:5px;'> "+source.val() ;
	span += "</span> <input value='"+source.val() +"' name='phone[]' type='hidden' /> ";
	span += "<button class='btn btn-xs btn-danger btn-phone-close'> <i class='fa fa-close'></i> </button>";
	var list = document.createElement('li');
	list.innerHTML = span;
	list.style = 'padding: 7px; background:#f2f2f2; border-radius: 4px; display: inline-block; margin:7px;';
	source.val('');
	target.append(list);

}

function autoSetDisplayNone(target, delay = 4000){
	target.delay(delay).slideUp(200, function() {
		$(this).css('display','none');
	});
}


// function printErrorMsg (msg) {

//     $(".print-error-msg").find("ul").html('');

//     $(".print-flash-msg").css('display','none');

//     $(".print-error-msg").css('display','block');

//     $.each( msg, function( key, value ) {

//         $(".print-error-msg").find("ul").append('<li>'+value+'</li>');

// 	});
	
// 	$(".print-error-msg").delay(4000).slideUp(200, function() {
// 		$(this).css('display','none');
// 	});

// }

// function printFlashMsg (msg) {

//     $(".print-flash-msg").find("ul").html('');

//     $(".print-error-msg").css('display','none');

// 	$(".print-flash-msg").css('display','block');
	

// 	$(".print-flash-msg").find("ul").append('<li>'+msg+'</li>');

// 	$(".print-flash-msg").delay(4000).slideUp(200, function() {
// 		$(this).css('display','none');
// 	});
	

// }
	
function repaintAttempt(routes){
	
	var table_body = $('#default_attempt_table>tbody');
	table_body.html('');
	var result = [];

	var routes = JSON.parse(routes);

	var keys = Object.keys(routes);
	keys.forEach(function(key){
		result.push(routes[key]);
	});


	for(var i in result){
		var tr = "<tr>";

		var x = i;
		
		tr += "<td> #"+ ++x +"</td>";
		tr += "<td id='td-phone-route'> <ul style='padding: 0; list-style:none'> ";
		for(var j in result[i].routes){
			tr += '<li class="phone-route">  <i class="fa fa-phone"></i>'
			tr += "<span style='padding:5px;'> "+ result[i].routes[j] +" </span> ";
			tr += "<input value='"+ result[i].routes[j] +"' name='phone[]' type='hidden' />  </li>";
		}

		tr += '</ul></td>';
		tr += "<td>"+ result[i].delay + " Seconds </td> ";
		tr += "<td> <button type='button' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#edit_transfer_route' data-attempt='"+result[i].attempt +"' data-delay='" + result[i].delay +"' >Edit</button> | <button type='button' class='btn btn-warning btn-xs delete_default' data-attempt='"+result[i].attempt +"' >Delete</button> </td> </tr>";

		table_body.append(tr);

	}

	$(".phone-route>span").mask("(000) 9999-9999");
		

}
	

$(".phone-input-btn").on("click", function(evt){
	var q = $("#phone-input").val() ;
	
	if(q.length < 15 || q.length > 15) {
		$('#errormessage').find('div').html('You have entered an invalid phone number ');
		$("#errormessage").css('display','block');
		autoSetDisplayNone( $("#errormessage"), 4000);
		return;
	}
	$("#errormessage").css('display','none');

	appendPhone($("#phone-container"), $("#phone-input") );
	

});


$("#phone-container").on("click", '.btn-phone-close', function(evt){

	$(this).parent('li').remove();
});

$("#phone-container-edit").on("click", '.btn-phone-close', function(evt){

	$(this).parent('li').remove();
});

	  


$("#sidebar").find("li").removeClass('active');
$("#sidebar").find("li.pilot-nav").addClass('active');
$("li.has-children.ms-nav").addClass('active');        



select =document.getElementById('transfer_delay');
for (var prop in delay_option) {
	var option = document.createElement('option');
	option.innerHTML = delay_option[prop]+ " Seconds";
	option.value = delay_option[prop];
	select.append(option)
}



/****
 * 
 * End Default Route Destination
 * 
 */


 

















 function toggleContWithAll(source, target, all=null){

	source.on('click', function(e){

		if($(this).prop("checked") == true){

			if(all){
				all.fadeOut();
			}

			target.fadeIn();
			target.removeClass('hide');

		}
		else{
			target.fadeOut();
		}

	});
		
 }

 function toggleCont(source, target){
	
		source.on('click', function(e){
	
			if($(this).prop("checked") == true){
	
				target.fadeIn();
				target.removeClass('hide');
				console.log('source ' + source);
				console.log('target ' + target);
	
			}
			else{
				target.fadeOut();
			}
	
		});
			
	 }