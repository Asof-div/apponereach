

function businessAjax(formData, url){

	$.ajax({
		url : url,
		type: "POST",
		data : formData,
		//async: false,
        enctype: 'multipart/form-data',
		success: function(data)
		{

			if($.isEmptyObject(data.error)){

            	printFlashMsg(data.success);
				setTenantText(data.tenant, JSON.parse(data.official) );
				
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

function userAjax(formData, url){
	
	$.ajax({
		url : url,
		type: "POST",
		data : formData,
		enctype: 'multipart/form-data',
		success: function(data)
		{

			if($.isEmptyObject(data.error)){
				printFlashMsg(data.success);
				$("#users-container").load("{{url($tenant->domain, 'user-list')}}");
				$(".load-list-user").addClass('hidden');
				$(".load-create-user").removeClass('hidden');				
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

function logoAjax(formdata, url){


	// $.ajaxSetup({
    //     headers: {
	// 		'X-CSRF-Token': $('meta[name=_token]').attr('content'),
	// 		'contentType': 'multipart/form-data',
		
    //     }
    // });

    if (formdata) {

		$.ajax({
		  url: url,
		  type: "POST",
		  data: formdata,
		  processData: false,
		  contentType: false,
		  
		  success: function (data) {
			  
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

}


$(".close-alert").on('click', function(event){
    
    $(this).parent('div').hide();
    //console.log($(".cancel_info"));

});


function setTenantText(tenant, official){
	$('#tenant_name_field > span.tenant_field_text').html( tenant.name );
	$('#tenant_address_field > span.tenant_field_text').html( tenant.info.address );
	$('#tenant_phone_field > span.tenant_field_text').html( tenant.info.phone );
	$('#tenant_state_field > span.tenant_field_text').html( tenant.info.state );
	$('#tenant_country_field > span.tenant_field_text').html( tenant.info.country );
	$('#tenant_contact_person_field > span.tenant_field_text').html( tenant.info.contact_person );
	$('#tenant_role_field > span.tenant_field_text').html( tenant.info.contact_person_role );
	$('#tenant_official_email_field > span.tenant_field_text').html( official.email != null || official.email != "" ? official.email : "N/A" );
	$('#tenant_official_phone_field > span.tenant_field_text').html( official.phone != null || official.phone != "" ? official.phone : "N/A" );

	$(".edit_info").show();
	$(".tenant_field_text").show();
	$(".tenant_field_input").hide();
	$('.cancel_info').hide();
	$('.save_info').hide();
	//$('#isOfficial').val();
}