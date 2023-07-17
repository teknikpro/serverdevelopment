
<?php
if(isset($_FILES['file'])) {
  $errors = array();
  $file_name = $_FILES['file']['name'];
  $file_size = $_FILES['file']['size'];
  $file_tmp = $_FILES['file']['tmp_name'];
  $file_type = $_FILES['file']['type'];
  $file_ext = strtolower(end(explode('.',$_FILES['file']['name'])));

  $extensions = array("jpeg","jpg","png");

  if(in_array($file_ext,$extensions) === false){
    $errors[] = "Ekstensi file tidak diizinkan, pilih file dengan ekstensi JPEG, JPG, atau PNG.";
  }

  if($file_size > 2097152) {
    $errors[] = 'Ukuran file harus lebih kecil dari 2 MB';
  }

  if(empty($errors) == true) {
    move_uploaded_file($file_tmp, "https://www.dfunstation.com/uploads/cobajax/".$file_name);
    echo "File berhasil diunggah";
  } else {
    echo implode(", ", $errors);
  }
}
?>

