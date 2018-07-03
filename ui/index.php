<?php
error_reporting(0);
$url = 'http://users-php:8082/api/users';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$output = curl_exec($ch);
curl_close($ch);
$arr = json_decode($output, true);
// echo "<pre>CURL output: "; print_r($output); echo "</pre>";
// echo "<pre>Output Array: "; print_r($arr); echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Microservices Demo</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="assest/jquery.min.js"></script>
<script src="assest/bootstrap.min.js"></script>
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen">
<script src="js/script.js"></script>
</head>
<body>
    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2>Manage Candidates</h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
						<i class="material-icons">&#xE147;</i> <span>Add New</span></a>				
					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
				<?php foreach($arr['Users'] as $getUserList ){ ?>
                    <tr>						
                        <td><?php echo $getUserList['name']; ?></td>
                        <td><?php echo $getUserList['email']; ?></td>
                        <td><?php echo $getUserList['mobile']; ?></td>
                        <td>
                            <a onclick = "getUserDetail('<?php echo $getUserList['cid']; ?>')" href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a onclick = "getUserForDelete('<?php echo $getUserList['cid']; ?>')" href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                            <a onclick = "getUserRatingDetails('<?php echo $getUserList['cid']; ?>')" href="#ratingsModal" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Ratings">&#x2605;</i></a>
                        </td>
                    </tr>
				<?php } ?>
                </tbody>
            </table>
			<div class="clearfix">
        </div>
    </div>
	<!-- Edit Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="addCandidateForm" name = "addCandidateForm">
					<div class="modal-header">						
						<h4 class="modal-title">Add Candidate</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div id="error"></div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" id = "name" required>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" class="form-control"  id = "email" required>
						</div>
						<div class="form-group">
							<label>Phone</label>
							<input type="text" class="form-control" id = "mobile" required>
						</div>					
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-success" id="btn-submit" value="Add">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="editCandidateForm" name="editCandidateForm">
					<div class="modal-header">						
						<h4 class="modal-title">Edit Candidate</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
                    <div id="error-edit"></div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Name</label>
							<input type="hidden" id="edit_cid" name="edit_cid">
							<input type="text" id="edit_name" name="edit_name" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" id="edit_email" name="edit_email"  class="form-control" required>
						</div>
						<div class="form-group">
							<label>Phone</label>
							<input type="text" id="edit_mobile" name="edit_mobile"  class="form-control" required>
						</div>					
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-info" id="btn-edit-submit" value="Save">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Delete Modal HTML -->
	<div id="deleteEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="deleteCandidateForm" name="deleteCandidateForm">
					<div class="modal-header">						
						<h4 class="modal-title">Delete Candidate</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div id="error-delete"></div>
					<div class="modal-body">					
						<p>Are you sure you want to delete these Records?</p>
						<p class="text-warning"><small>This action cannot be undone.</small></p>
					</div>
					<div class="modal-footer">
					    <input type="hidden" id="delete_id" name="delete_id">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-danger" value="Delete">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="ratingsModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="editCandidateForm">
					<div class="modal-header">						
						<h4 class="modal-title">Edit Candidate</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div id="errorRating"></div>
					<div class="modal-body">
						<div class="form-group">
							<label>Name: </label>
							<label id = "ratingName"></label>
							<input type="hidden" class="form-control" id = "ratingCid">
							<input type="hidden" class="form-control" id = "ratingStatus">
						</div>
						<div class="form-group">
							<label>Email: </label>
							<label id = "ratingEmail"></label>
						</div>
						<div class="form-group">
							<label>Mobile: </label>
							<label id = "ratingMobile"></label>
						</div>					
						<div class="form-group">
							<label>Ratings:</label>
							<section class='rating-widget'>
					          <!-- Rating Stars Box -->
					          <div class='rating-stars'>
					            <ul id='stars'>
					              <li class='star' title='Poor' data-value='1'>
					                <i class='fa fa-star fa-fw'></i>
					              </li>
					              <li class='star' title='Fair' data-value='2'>
					                <i class='fa fa-star fa-fw'></i>
					              </li>
					              <li class='star' title='Good' data-value='3'>
					                <i class='fa fa-star fa-fw'></i>
					              </li>
					              <li class='star' title='Excellent' data-value='4'>
					                <i class='fa fa-star fa-fw'></i>
					              </li>
					              <li class='star' title='WOW!!!' data-value='5'>
					                <i class='fa fa-star fa-fw'></i>
					              </li>
					            </ul>
					          </div>
					        </section>
						</div>
						<div class="form-group">
							<label>Notes</label>
							<input type="text" class="form-control" id = "ratingNotes">
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<a onclick = "submitRating()" class="btn btn-info" >Save</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>                                		                            
