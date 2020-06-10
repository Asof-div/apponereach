function ajaxCall(url, formData, success, failed){

	$.ajax({
        url : url,
        type: "POST",
        data : formData,
        processData: false,
		contentType: false,
        success: function(data)
        {
            if($.isEmptyObject(data.error)){
                
                alertify.success(data.success);
                success(data);

            }else{

                printErrorMsg(data.error);
                failed(data);
            }	
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Oops !!!');
        }

    });
}