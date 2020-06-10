
function printErrorMsg (msg, timer=15000) {

    $(".print-error-msg").find("ul").html('');

    $(".print-flash-msg").css('display','none');

    $(".print-error-msg").css('display','block');

    $.each( msg, function( key, value ) {

        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');

    });

    autoSetDisplayNone( $(".print-error-msg"), timer); 
    new Noty({
        layout: 'topRight',
        theme: 'relax', 
        closeWith: ['click'],
        type: 'error',
        text: msg,
      }).show().setTimeout(timer);
}

function printFlashMsg (msg, timer=4000) {

    $(".print-flash-msg").find("ul").html('');

    $(".print-error-msg").css('display','none');

    $(".print-flash-msg").css('display','block');

    $(".print-flash-msg").find("ul").append('<li>'+msg+'</li>');

    autoSetDisplayNone( $(".print-flash-msg"), timer); 
    
    new Noty({
        layout: 'topRight',
        theme: 'sunset', 
        closeWith: ['click'],
        type: 'success',
        text: msg,
      }).show().setTimeout(timer);
    
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function ajaxCall(url, formData, success, failed){
  $('#system_overlay').removeClass('hidden');

  $.ajax({
        url : url,
        type: "POST",
        data : formData,
        processData: false,
        contentType: false,
        success: function(data)
        {
            if($.isEmptyObject(data.error)){
                
                success(data);

                $('#system_overlay').addClass('hidden');

            }else{

                // printErrorMsg(data.error);
                failed(data);
                $('#system_overlay').addClass('hidden');
            
            } 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          
            if (jqXHR.status === 422) { // 422 status is a validation error
            
                printErrorMsg(JSON.parse(jqXHR.responseText).error, 5000);

            }else if (jqXHR.status === 400) { // 422 status is a validation error
            
                printErrorMsg(JSON.parse(jqXHR.responseText).error, 5000);

            } else {
                printErrorMsg(['An error occured. Please try again later.']);
              
            }

            $('#system_overlay').addClass('hidden');

        }

    });
}


function matchPass(firstpassword, secondpassword){  
    
  if(firstpassword==secondpassword){  
      return true;  
  }  
  else{  
      alert("password must be same!");  
      return false;  
  }  
}  

function validate(num, target){  
  
  if (isNaN(num)){  
        document.getElementById(target).innerHTML="Enter Numeric value only";  
        return false;  
  }
  else{  
        return true;  
  }  
}  


function validateEmail(email)  
{  

  var atposition=email.indexOf("@");  
  var dotposition=email.lastIndxOf(".");

  if (atposition<1 || dotposition<atposition+2 || dotposition+2>=email.length){  
        alert("Please enter a valid e-mail address \n atpostion:"+atposition+"\n dotposition:"+dotposition);  
        return false;  
    }  
}  

function validateFile(file){
  
  if (!$(file).hasExtension(['.jpg', '.png', '.gif'])) {
      
      return false;
  }
  else{
      return true;
  }
}

function validateFile(file, size){

  var ext = file.substring(file.lastIndexOf('.') + 1);
  ext = ext.toLowerCase();

  if ( (ext == "gif" || ext == "jpeg" || ext == "jpg" ||  ext == 'png' ||  ext == 'pdf' || ext == 'doc' || ext == 'docx' || ext == 'xls' || ext == 'xlsx'  || ext == 'csv') &&  size <= 3200) {
    
    return true;
  }
  else{
    return false;
  }
}


function autoSetDisplayNone(target, delay = 4000){
	target.delay(delay).slideUp(200, function() {
		$(this).css('display','none');
	});
}



function countdowntTimer(element)
{
    let timer;
    let countdownElement = $(element);
    let deadline = countdownElement.attr('data-time');
    deadline = new Date(deadline * 1000);

    timer = setInterval(function() {
        var now = new Date();
        var difference = deadline.getTime() - now.getTime();

        if (difference <= 0) {
            $(element).addClass('text-warning');
            clearInterval(timer);
            $(element).parent().find('button.remove-reserved').click();
        } else {
            var seconds = Math.floor(difference / 1000);
            var minutes = Math.floor(seconds / 60);
            var hours = Math.floor(minutes / 60);
            var days = Math.floor(hours / 24);

            hours %= 24;
            minutes %= 60;
            seconds %= 60;



            $(element).text(`Countdown: ${hours}h ${minutes}m  ${seconds}s `);
            if( hours <= 0 && minutes <= 0 && seconds <= 0){
                $(element).parent().find('button.remove-reserved').click();
                
            }
            
        }
    }, 1000);
}

function initiateCountdown(){        
    let countdownElements = document.getElementsByClassName('release-countdown');
    for (let i=0; i < countdownElements.length; i++) {
        countdowntTimer(countdownElements[i]);
    }
}

function addresource(contain){
  count = $('#'+contain).find('.file-attachment').length;
  if( count < 5){
    count++;

    let container = document.createElement('span');
    container.setAttribute('class', 'file-attachment');
    let span = "<button type='button' class='btn text-danger file-attachment-remove btn-xs pull-right btn-link'> <i class='fa fa-times'></i> </button>";
    span += "<i class='fa fa-paperclip file-clip fa-2x'></i>  <span class='file-resource'>";
    span += "<input required='required' name='resources[]' type='file' class='file-resource-input' > </span>";
    span += "<span class='file-name'></span><span class='file-size'></span>";
    container.innerHTML = span;
    
    document.querySelector('#'+contain).appendChild(container);
  }
}

function loadChatContent(url){
  $('.chat-area').load( encodeURI(url) );
}

/***** File Attachment  JS Begins *****/
$("body").on('click', '.file-clip',function () {
    
  $(this).next().find('.file-resource-input').trigger('click') ;
});

$('body').on('change', '.file-resource-input',function(e){

  var size = parseFloat(this.files[0].size/1000).toFixed(1);

  if( validateFile($(this).val(), size ) ) {

      $(this).parent().next().text(this.files[0].name);
      $(this).parent().next().next().text("( "+size + " kb)");

    }


});

$('body').on("click",".file-attachment-remove", function(e){ //user click on remove text
    e.preventDefault(); $(this).parent('span').remove(); 
    count--;
});


$('body').on('click', '.show-sidebox-right-btn', function(e){
  $('.sidebox-container').hide();
  $('.sidebox-container').removeClass("hide");
  $('.sidebox-container').show( "slide", {direction: "right" }, 1000 );

});

$('body').on('click', '.hide-sidebox-btn', function(e){
  $('.sidebox-container').hide( "slide", {direction: "right" }, 1000 );
});

jQuery.fn.exists = function(){return ($(this).length > 0);}
