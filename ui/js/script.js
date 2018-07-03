
var interviewAPIURL = "http://interview-php:8081/api";
var userAPIURL = "http://users-php:8082/api";

     /* handle form add submition */  
	$(document).ready(function(){
		$('#addEmployeeModal').on('hidden.bs.modal', function() {
			$(':input[type="submit"]').prop('disabled', false); 
			$(':input[type="text"]', this).val(''); 
			$(':input[type="email"]', this).val('');
			$("#error").hide();

		});
	$("#addCandidateForm").submit(function( event ) {
			$(':input[type="submit"]').prop('disabled', true);
			var name = $("#name").val(); 
			var email = $("#email").val(); 
			var mobile = $("#mobile").val();
			var obj = new Object();
			obj.name = name;
			obj.email = email;
			obj.mobile = mobile;
			var data = JSON.stringify(obj);
		$.ajax({    
			type : 'POST',
			url  : userAPIURL + '/user',
			data : data,
			datatype: 'json',
			contentType : 'application/json; charset=utf-8',
			beforeSend: function() { 
			$("#error").fadeOut();
			},
			success :function(response) {
				var getRes = response;
				if(getRes.responseCode==0){
					var t = "success";
				}else{
					var t = "danger";
				}        
				$("#error").fadeIn(1000, function(){
					$("#error").html('<div class="alert alert-'+t+'">&nbsp; '+getRes.responseText+'</div>'); 
				});
				if(getRes.responseCode == 0){
					setTimeout(function() { 
					$('#addEmployeeModal').modal('hide');
					location.reload();
					},2000);
				}else{
					$(':input[type="submit"]').prop('disabled', false);
				}
			}
		});
		return false;
	});

   /* handle form edit submition */ 
$('#editEmployeeModal').on('hidden.bs.modal', function() {
			$(':input[type="submit"]').prop('disabled', false); 
			$("#error-edit").hide();
		});
$('#ratingsModal').on('hidden.bs.modal', function() {
			$("#errorRating").hide();
		});

	$("#editCandidateForm").submit(function( event ) {
			$(':input[type="submit"]').prop('disabled', true);
			var name = $("#edit_name").val();
			var cid = $("#edit_cid").val();  
			var email = $("#edit_email").val(); 
			var mobile = $("#edit_mobile").val();
			var obj = new Object();
			obj.name = name;
			obj.email = email;
			obj.mobile = mobile;
			var data = JSON.stringify(obj);
		$.ajax({    
			type : 'PUT',
			url  : userAPIURL + '/user/' + cid,
			data : data,
			datatype: 'json',
			contentType : 'application/json; charset=utf-8',
			beforeSend: function() { 
			$("#error-edit").fadeOut();
			},
			success :function(response) { 

				var getRes = response;
				if(getRes.responseCode==0){
					var showEditMsg = "success";
				}else{
					var showEditMsg = "danger";
				}        
				$("#error-edit").fadeIn(1000, function(){
					$("#error-edit").html('<div class="alert alert-'+showEditMsg+'">&nbsp; '+getRes.responseText+'</div>'); 
				});
				if(getRes.responseCode == 0){
					setTimeout(function() { 
					$('#editEmployeeModal').modal('hide');
					location.reload();
					},2000);
				}else{
					$(':input[type="submit"]').prop('disabled', false);
				}
			}
		});
		return false;
	});


	/* handle form delete submition */ 
   $('#deleteEmployeeModal').on('hidden.bs.modal', function() {
			$(':input[type="submit"]').prop('disabled', false); 
			$("#error-delete").hide();
		});

	$("#deleteCandidateForm").submit(function( event ) {
			$(':input[type="submit"]').prop('disabled', true);
			var cid = $("#delete_id").val();	
		$.ajax({    
			type : 'DELETE',
			url  : userAPIURL + '/user/' + cid,
			datatype: 'json',
			contentType : 'application/json; charset=utf-8',
			beforeSend: function() { 
			$("#error-delete").fadeOut();
			},
			success :function(response) { 

				var getRes = response;
				if(getRes.responseCode==0){
					var showDeleteMsg = "success";
				}else{
					var showDeleteMsg = "danger";
				}        
				$("#error-delete").fadeIn(1000, function(){
					$("#error-delete").html('<div class="alert alert-'+showDeleteMsg+'">&nbsp; '+getRes.responseText+'</div>'); 
				});
				if(getRes.responseCode == 0){
					setTimeout(function() { 
					$('#deleteEmployeeModal').modal('hide');
					location.reload();
					},2000);
				}else{
					$(':input[type="submit"]').prop('disabled', false);
				}
			}
		});
		return false;
	});



	/* 1. Visualizing things on Hover - See next part for action on click */
  $('#stars li').on('mouseover', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
   
    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function(e){
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });
    
  }).on('mouseout', function(){
    $(this).parent().children('li.star').each(function(e){
      $(this).removeClass('hover');
    });
  });
  
  
  /* 2. Action to perform on click */
  $('#stars li').on('click', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('li.star');
    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }
    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }
    var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
    $('#ratingsValue').val(ratingValue);
    //alert($('#ratingsValue').val());
    
  });



});  /*Document get ready end */

/* handle form get data from user */ 
function getUserDetail(cid){
	var url = userAPIURL + '/user/' + cid;
$.ajax({    
		type : 'GET',
		url  : url,
		datatype: 'json',
		contentType : 'application/json; charset=utf-8',
		beforeSend: function() { 
		$("#error-edit").fadeOut();
		},
		success :function(response) {
		var getRes = response;
        var name = getRes.Users[0].name;
        var cid = getRes.Users[0].cid;
		var email = getRes.Users[0].email;
		var mobile = getRes.Users[0].mobile;
            if(getRes.responseCode==0){
				var editMsg = "success";
			}else{
				var editMsg = "danger";
			} 
        if(getRes.responseCode==0){
		   $('#edit_name').val(name);
		   $('#edit_cid').val(cid);
		   $('#edit_email').val(email);
		   $('#edit_mobile').val(mobile);
       }else{
       		$('#edit_cid').val(cid);
		    $('#edit_name').val(name);
		    $('#edit_email').val(email);
		    $('#edit_mobile').val(mobile);
	        $("#error-edit").html('<div class="alert alert-'+editMsg+'">&nbsp; '+getRes.responseText+'</div>');
       }
		}
	});
	return false;
}

/* handle form get data for Delete User */ 
function getUserForDelete(cid){
	$('#delete_id').val(cid);  
    return false;
}

function getUserRatingDetails(id){

	var url = userAPIURL + '/user/' + id;
	//Fetching User Details
	$.ajax({    
		type : 'GET',
		url  : url,
		datatype: 'json',
		contentType : 'application/json; charset=utf-8',
		beforeSend: function() { 
			$("#error").fadeOut();
		},
		success :function(response) {
			var getRes = response;
         	if(getRes.responseCode==0){
				var name = getRes.Users[0].name;
				var cid = getRes.Users[0].cid;
				var email = getRes.Users[0].email;
				var mobile = getRes.Users[0].mobile;
				$('#ratingCid').val(cid);
			   	$('#ratingName').html(name);
			   	$('#ratingEmail').html(email);
			   	$('#ratingMobile').html(mobile);
           	}
		}
	});

	//Fetching Rating details
	var url = interviewAPIURL + '/interview/' + id;
	$.ajax({    
		type : 'GET',
		url  : url,
		datatype: 'json',
		contentType : 'json/json; charset=utf-8',
		beforeSend: function() { 
			$("#error").fadeOut();
		},
		success :function(response) {
			var stars = $('#stars li').parent().children('li.star');
		    for (i = 0; i < 6; i++) {
		    	$(stars[i]).removeClass('selected');
		    }
		    for (i = 0; i < response.rating; i++) {
		    	$(stars[i]).addClass('selected');
		   	}
		   	$('#ratingStatus').val(response.status);
			$('#ratingNotes').val(response.notes);
		}
	});
	return false;
}

function submitRating(){

	var id = $("#ratingCid").val();
	var url = interviewAPIURL + '/interview/' + id;
	var stat = $('#ratingStatus').val();
	if(stat == 1){
		var verb = "PUT";
	} else{
		var verb = "POST";
	}
	var obj = new Object();
	obj.rating = parseInt($('#stars li.selected').last().data('value'), 10);;
	obj.notes = $("#ratingNotes").val();
	var data = JSON.stringify(obj);
	$.ajax({    
		type : verb,
		url  : url,
		data : data,
		datatype: 'json',
		contentType : 'application/json; charset=utf-8',
		beforeSend: function() { 
			$("#error").fadeOut();
		},
		success :function(response) {
			if(response.responseCode==0){
				var t = "success";
			}else{
				var t = "danger";
			}        
			$("#errorRating").fadeIn(1000, function(){
				$("#errorRating").html('<div class="alert alert-'+t+'">&nbsp; '+response.responseText+'</div>'); 
			});
		}
	});

}



