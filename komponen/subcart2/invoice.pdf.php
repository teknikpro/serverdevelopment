<?php

if($pengiriman == "Pickup Point")
	$ketkirim	= "Diambil Sendiri";
else
	$ketkirim	= "Jasa Kurir $namaagen";
	
$html .="<table width=100%>
	<tr>
    	<td align=left><img src='".$fulldomain."/images/img.logo.png' width=80%></td>
        <td  align=right width=70%><strong>".$namatk."</strong><br> ".$alamattk." <br>Phone : ".$telptk." / ".$gsmtk." </td>
    </tr>
    <tr>
    	<td valign=top><strong>Transaksi untuk :</strong><br /> ".$userfullname."<br>".$userphonegsm."</td>
		<td align=right valign=top><strong>Tanggal : </strong>".$tanggal."<br></td>
    </tr>

</table>";
$html .="<table width=100% >
		<thead>
		<tr style=\"background-color:#cccccc\">
			<th colspan=3 width=10%><b>Detail Peminjaman</b></th>
		</tr>
		<tr>
			<th colspan=3 width=10%>&nbsp;</th>
		</tr></thead>";
$html .= "
		<tr>
			<td width=29%><strong>Nomor Peminjaman</strong></td>
			<td width=1%>:</td>
			<td width=70%>$invoiceid</td>
		</tr>
			<tr>
			<td><strong>Metode Pembayaran</strong></td>
			<td>:</td>
			<td>$pembayaran</td>
		</tr>
		<tr>
			<td><strong>Metode Pengiriman</strong></td>
			<td>:</td>
			<td>$ketkirim</td>
		</tr>
		<tr>
			<th colspan=3 width=10%>&nbsp;</th>
		</tr>
		<tr>
			<th colspan=3 width=10%>&nbsp;</th>
		</tr>
		<tr>
			<td colspan=3>
				<table style=\"border:1px solid #ddd\" width=100%>
					<thead>
					<tr>
						<td>Nama Mainan</td>
						<td>Kode Mainan</td>
						<td>Jumlah</td>
					</tr>
					</thead>";
foreach($dt_keranjang as $datatr)
{
				
$html .= "			<tr>
						<td>".$datatr['nama']."</td>
						<td>".$datatr['kodeproduk']."</td>
						<td>".$datatr['qty']."</td>

					</tr>";
}


				
				if($pengiriman != 'Pickup Point')
				{
$html .= "			<tr>
						<td colspan=5>Ongkos Kirim($namaagen - $ongkosinfo)</td>
						<td align=right>".$ongkoskirim2."</td>
					</tr>";
				}	
$html .= "			<tr>
						<td colspan=5><strong>Total</strong></td>
						<td align=right><strong>".$totaltagihanakhir2."</strong></td>
					</tr>
				</table>
			</td>
		</tr>";
$html .="</table><br clear=all /><br clear=all />";

			
$html .="<table border=\"0\" width=\"100%\">";
		if($pengiriman != 'Pickup Point')
		{
			$html .="
			<tr>
				<th width=\"100%\" colspan=\"2\" style=\"background-color:#cccccc\">Informasi Pengiriman</th>
			</tr>

			";
            if (!empty($no_resi))
			{
				$html .="<tr>
					<td class=\"info_spec\">Nomor Resi Pengiriman</td>
					<td class=\"sub_spec\">$no_resi</td>
				</tr>";
            }
             $html .="<tr>
				<td class=\"info_spec\">Nama Lengkap</td>
				<td class=\"sub_spec\">$userfullname</td>
			</tr>
			<tr>
				<td class=\"info_spec\">Alamat Pengiriman</td>
				<td class=\"sub_spec\">$alamat_kirim</td>
			</tr>";
		}
		else
		{
			$html .="
			<tr>
				<th width=\"100%\" colspan=\"2\" style=\"background-color:#cccccc\">Informasi Pengambilan Barang</th>
			</tr>
			<tr>
				<td width=\"30%\" class=\"info_spec\">Nama Toko</td>
				<td class=\"sub_spec\">$warehouse</td>
			</tr>
			<tr>
				<td class=\"info_spec\">Alamat</td>
				<td class=\"sub_spec\">$alamattk<br>$telptk</td>
			</tr>";
		}
		$html .="</table><br clear=all /><br clear=all />";

if($pembayaran == "Transfer")
{
	$html .="<br>Anda dapat melakukan transfer pembayaran melalui salah satu rekening berikut : <br><ol>";
	foreach($rekening as $data)
	{
		$html .= "<li>".$data['bank']." (".$data['akun']. "a.n ".$data['namaak'].")</li>";
	}
	$html .="</ol><br>Setelah melakukan pembayaran transfer atau pembayaran tunai Anda, kami harapkan dapat melakukan konfirmasi pembayaran melalui URL :<br> $urlconfirm  <br><br>
		Harap melakukan pembayaran sebelum tanggal $datetransfer. Apabila sampai waktu yang telah ditentukan Kami belum menerima pembayaran Anda maka secara otomatis Invoice Anda Dibatalkan.  <br><br>
		";
}
elseif($pembayaran != "Transfer")
{
	if($statusorder=="INVOICED")
	{
	
		$html .="<br>Saat ini status transaksi anda masih PENDING atau belum melakukan pembayaran, silahkan lakukan pembayaran dengan menggunakan metode pembayaran
		yang telah anda pilih sebelumnya. Jika tidak dilakukan pembayaran hingga tanggal $batastransfer maka kami akan melakukan pembatalan transaksi anda secara otomatis.<br><br>
		";
	}
	if($statusorder=="CANCEL")
	{
	
		$html .="<br>Transaksi ini telah dibatalkan atas pemintaan anda atau waktu pembayaran telah habis, untuk membeli produk baru silahkan
		lakukan transaksi kembali.<br><br>
		";
	}
}

include($lokasiweb."librari/mpdf/mpdf.php");

$mpdf=new mPDF('P'); 

$mpdf->WriteHTML($html);
$mpdf->Output($lokasiweb."/gambar/pdf/$invoiceid.pdf");
?>