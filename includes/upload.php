<?php
$target_path = "../uploads/";

$target_path = $target_path . basename( $_FILES['photo-0']['name']); 

if(move_uploaded_file($_FILES['photo-0']['tmp_name'], $target_path)) {
    echo basename( $_FILES['photo-0']['name']);
} else{
    echo "There was an error uploading the file, please try again!";
}
?>
