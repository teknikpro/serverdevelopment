<?php

if($pengiriman == "Pickup Point")
	$ketkirim	= "Diambil Sendiri";
else
	$ketkirim	= "Jasa Kurir";
	
$html .="<table width=100%>
	<tr>
    	<td colspan=4 align=left><img src='".$fulldomain."/template/dfunstation/images/img.logo.png' width=100%></td>
        <td colspan=2 align=right><strong>".$namatk."</strong><br> ".$alamattk." <br>Phone : ".$telptk." / ".$gsmtk." </td>
    </tr>
	<tr>
    	<td colspan=6>&nbsp;</td>
    </tr>
    <tr>
    	<td colspan=5 valign=top><strong>Invoice ke :</strong><br /> ".$userfullname."<br>".$userhandphone."</td>
		<td align=right valign=top><strong>Tanggal : </strong>".$tanggal."<br> <font color=\"$warnafont\" style=\"font-size:14px\" ><strong>$statusorder</strong></font></td>
    </tr>
    <tr>
    	<td colspan=6>&nbsp;</td>
    </tr>
	<tr>
    	<td colspan=6>&nbsp;</td>
    </tr>
</table>";
$html .="<table width=100% >
		<thead>
		<tr style=\"background-color:#cccccc\">
			<th colspan=3 width=10%><b>Detail Transaksi</b></th>
		</tr>
		<tr>
			<th colspan=3 width=10%>&nbsp;</th>
		</tr></thead>";
$html .= "
		<tr>
			<td><strong>Kode Order</strong></td>
			<td>:</td>
			<td>$orderid</td>
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
						<td width=45%>Nama Produk</td>
						<td width=10%>Kode Produk</td>
						<td width=5%>Jumlah</td>
						<td width=10%>Harga</td>
						<td width=20%>Diskon</td>
						<td width=10%>Subtotal</td>
					</tr>
					</thead>";
foreach($dt_toko as $datatk)
{
$resellerid=$datatk['resellerid'];
$html .= "<tr>
						<th colspan=6>Nama Toko : ".$datatk['namatoko']."</th>
						
					</tr>";
foreach($dt_keranjang[$resellerid] as $datatr)
{
				
$html .= "			<tr>
						<td>".$datatr['nama']."</td>
						<td>".$datatr['kodeproduk']."</td>
						<td>".$datatr['qty']."</td>
						<td>".$datatr['harga_asli']."</td>
						<td>".$datatr['ketdiskon']." ".$datatr['hargadiskon']."</td>
						<td align=right>".$datatr['totalharga']."</td>
					</tr>";
}
$html .= "<tr>
						<td colspan=5 align=right>Ongkos kirim (".$datatk['namaagen']." - ".$datatk['ongkosinfo'].")</td>
						<td align=right>".$datatk['ongkoskirim']."</td>
						
					</tr>";
}
$html .= "			<tr>
						<td colspan=5>Jumlah yang harus dibayarkan</td>
						<td align=right>".$totaltagihan2."</td>
					</tr>";
				if($totaldiskon != '0')
				{
$html .= "			<tr>
						<td colspan=5>Diskon Voucher ($kodevoucher)</td>
						<td align=right>(-)".$totaldiskon2."</td>
					</tr>";
				}
				
				if($pengiriman != 'Pickup Point')
				{
$html .= "			<tr>
						<td colspan=5>Total Ongkos Kirim</td>
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
	if($statusorder=="INVOICED" || $statusorder=="BILLED")
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