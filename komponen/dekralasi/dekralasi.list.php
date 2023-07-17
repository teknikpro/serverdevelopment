<?php
$perintah = "select nama,alias,ringkas,lengkap,gambar1 from tbl_static where alias='about'";
$hasil = sql($perintah);
$data = sql_fetch_data($hasil);
$detailnama = $data["nama"];
$alias = $data["alias"];
$detailringkas = $data["ringkas"];
$detaillengkap = $data["lengkap"];
$gambar = $data['gambar1'];

$tpl->assign("detailnama", $detailnama);
$tpl->assign("alias", $alias);
$tpl->assign("detailringkas", $detailringkas);
$tpl->assign("detaillengkap", $detaillengkap);

if (!empty($gambar)) $tpl->assign("detailgambar", "$fulldomain/gambar/$kanal/$gambar");

$jumlahsetuju = sql_get_var("SELECT COUNT(id_dekralasi) FROM tbl_dekralasi");
$tpl->assign("jumlahsetuju", $jumlahsetuju);



//Simpan Komentar
if (isset($_POST['submit'])) {
    $nama = cleanInsert($_POST['nama']);
    $email = $_POST['email'];
    $handphone = cleanInsert($_POST['handphone']);
    $pesan = cleanInsert($_POST['pesan']);
    $ip = $_SERVER['REMOTE_ADDR'];

    $judul = "percobaan";
    $date = date('d-m-y');


    $query = ("insert into tbl_dekralasi(judul,nama,create_date,pesan,ip,email,handphone) values ('$judul','$nama','$date','$pesan','$ip','$email','$handphone')");
    $hasil = sql($query);

    if ($hasil) {
        //Kirim
        $isi = "Terima Kasih Sudah Menghubungi Kami
            =========================================================================<br />
            Yth. $nama,
            Terima kasih sudah menghubungi kami. Pesan anda:

            $pesan

            Kami akan membalas pesan anda sesegera mungkin. Terima kasih
            dan sukses selalu untuk anda.

            Terima Kasih
            $owner";

        $isihtml = "<br />
            <strong>Terima Kasih Sudah Menghubungi Kami</strong>
            =========================================================================<br />
            Yth. $nama,<br />
            Terima kasih sudah menghubungi kami. Pesan anda:<br />
            <br />

            $pesan
            <br />
            <br />

            Kami akan membalas pesan anda sesegera mungkin. Terima kasih
            dan sukses selalu untuk anda.
            .
            <br />
            <br />

            Terima Kasih<br />

            $owner";

        $subject = "Terima Kasih Sudah Menghubungi Kami";

        sendmail($nama, $email, $subject, $isi, $isihtml);

        $isi1 = "Ada pesan masuk melalui kontak dari <strong>$nama</strong> dengan email <strong>$email</strong> dan nomor handphone <strong>$handphone</strong>:<br />
            <br />
            $pesan<br />
            <br />
            ";
        $judul = "Simposium Nasional";


        sendmail($judul, $support_email, "Ada Pesan Masuk Melalui Kontak", $isi1, $isi1);

        //Forward
        $sql = "select nama,useremail from tbl_forward order by nama";
        $hsl = sql($sql);
        while ($dt = sql_fetch_data($hsl)) {
            $fnama = $dt['nama'];
            $femail = $dt['useremail'];

            $subject = "Forward: Ada Pesan Masuk Melalui Kontak";
            sendmail($fnama, $femail, $subject, $isi1, emailhtml($isi1));
        }

        header("location: $fulldomain/terimakasih");
    } else {
        $tpl->assign("error", "Pesan anda gagal tersimpan kemungkinan ada beberapa kesalahan. Silahkan untuk mengulangi.<br />. ");
    }
}


$perintah = "SELECT id_dekralasi,nama,create_date,update_date,pesan FROM tbl_dekralasi ORDER BY id_dekralasi DESC";
$query = sql($perintah);
$list_post    = array();
$no    = 1;
while ($row = sql_fetch_data($query)) {
    $id_dekralasi   = $row["id_dekralasi"];
    $nama           = $row["nama"];
    $create_date    = tanggaltok($row["create_date"]);
    $update_date    = tanggaltok($row["update_date"]);
    $pesan          = $row["pesan"];

    $list_post[$id_dekralasi] = array(
        "id_dekralasi" => $id_dekralasi, "nama" => $nama, "create_date" => $create_date, "pesan" => $pesan, "update_date" => $update_date
    );
    $no++;
}
sql_free_result($query);
$tpl->assign("list_post", $list_post);
