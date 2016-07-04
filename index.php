<?php
session_start ();
require ("config/config.php");

// increase number of view only once in one session
if (! isset ( $_SESSION ['views'] )) {
	$data = array (
			"views_number" => $db->inc ( 1 ) 
	);
	$db->update ( "views", $data );
	// create session variable with any value.
	$_SESSION ['views'] = "anything";
}
//

$targetDir = "images/";
$result = ""; // error message
$success = ""; // success message
               
// upload file form submit
if (isset ( $_POST ["submit"] ) && ! empty ( $_FILES ["fileUpload"] ["name"] )) {
	$targetFile = $targetDir . basename ( $_FILES ["fileUpload"] ["name"] );
	
	// get file extension
	$imageFileType = strtolower ( pathinfo ( $targetFile, PATHINFO_EXTENSION ) );
	
	// Check if file already exists
	if (file_exists ( $targetFile )) {
		$result = "Sorry, file already exists.";
	} // Check if file size more than 20MB
elseif ($_FILES ["fileUpload"] ["size"] > 167772160) {
		$result = "Sorry, your file is too large.";
	} // Allow certain file formats
elseif ($imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
		$result = "Sorry, only JPEG,PNG & GIF files are allowed.";
	}
	// Check if there is no error
	if (empty ( $result )) {
		if (move_uploaded_file ( $_FILES ["fileUpload"] ["tmp_name"], $targetFile )) {
			$success = "The image " . basename ( $_FILES ["fileUpload"] ["name"] ) . " has been uploaded.";
			// insert information into table
			$data = Array (
					"image_name" => basename ( $_FILES ["fileUpload"] ["name"] ),
					"image_title" => $_POST ["imageTitle"] 
			);
			$id = $db->insert ( 'images', $data );
			//
		} else {
			$result = "Sorry, there was an error uploading your file.";
		}
	}
} //
  
// get number of views and posts
$views = 0; // numbers of views
$posts = 0; // numbers of posts

$dbResult = $db->get ( "views" );
$views = $dbResult [0] ['views_number'];

$dbResult = $db->getOne ( "images", "count(*) as num" );
$posts = $dbResult ['num'];
//

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Images</title>
<link rel="stylesheet" href="css/bootstrap.min.css">

</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-4">
				Posts: <span class="badge" id="posts"><?php echo $posts;?></span>
				&nbsp;&nbsp; <a href="download/csv.php">download csv file</a>
				&nbsp;&nbsp; Views: <span class="badge" id="views"><?php echo $views;?></span>
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a
					href="download/excel.php">download Excel file</a> <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a
					href="download/zip.php">download zip file</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<form action="" method="post" enctype="multipart/form-data">
					<input type="text" class="form-control" placeholder="Image Title"
						name="imageTitle"> Select image to upload: <input type="file"
						name="fileUpload" id="fileUpload"> <input type="submit"
						value="Upload Image" name="submit"
						class="btn btn-lg btn-primary btn-block"> <span
						class="label label-danger"><?php echo $result ;?></span> <span
						class="label label-success""><?php echo $success ;?></span>
				</form>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<table class="table">
					<thead>
						<tr>
						</tr>
					</thead>
					<tbody>
			<?php
			// display all images in a table.
			$db->orderBy ( "id", "desc" );
			$images = $db->get ( "images" );
			if ($db->count > 0)
				foreach ( $images as $image ) {
					echo "<tr>";
					echo "<td>";
					echo "<h3><span class=\"label label-default\">" . $image ['image_title'] . "</span></h3>";
					echo "</td>";
					echo "</tr>";
					
					echo "<tr>";
					echo "<td>";
					echo "<img src=\"" . $targetDir . $image ['image_name'] . "\" class=\"img-rounded\"  width=\"700\" height=\"500\"  >";
					echo "</td>";
					echo "</tr>";
				}
			?>
			</tbody>
				</table>
			</div>
		</div>
	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/mine.js"></script>
</body>
</html>