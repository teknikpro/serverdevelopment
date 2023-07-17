<?php


// Konfigurasi
$targetDir = "$lokasiwebmember/percobaan/"; // Folder tempat menyimpan file yang diunggah
$targetFile = $targetDir . basename($_FILES["file"]["name"]); // Nama file yang akan diunggah
$uploadOk = 1; // Status upload, 1 berarti berhasil

// Cek apakah file yang diunggah adalah gambar
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        echo "File adalah gambar - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }
}

// Cek ukuran file
if ($_FILES["file"]["size"] > 500000) {
    echo "File terlalu besar.";
    $uploadOk = 0;
}

// Cek jenis file yang diperbolehkan
$allowedTypes = array("jpg", "jpeg", "png", "gif");
if(!in_array($imageFileType, $allowedTypes)) {
    echo "Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
    $uploadOk = 0;
}

// Jika upload gagal
if ($uploadOk == 0) {
    echo "File tidak berhasil diunggah.";
// Jika upload berhasil
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        echo "File ". basename( $_FILES["file"]["name"]). " berhasil diunggah.";
    } else {
        echo "Terjadi kesalahan saat mengunggah file.";
    }
}


?>