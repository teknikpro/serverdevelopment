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
    	<td valign=top><strong>Invoice ke :</strong><br /> ".$userfullname."<br>".$userphonegsm."</td>
		<td align=right valign=top><strong>Tanggal : </strong>".$tanggal."<br> <font color=\"$warnafont\" style=\"font-size:14px\" ><strong>$statusorder</strong></font></td>
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
			<td width=29%><strong>Nomor Invoice</strong></td>
			<td width=1%>:</td>
			<td width=70%>#$invoiceid</td>
		</tr>
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
						<td>Nama Produk</td>
						<td>Kode Produk</td>
						<td>Jumlah</td>
						<td>Harga</td>
					</tr>
					</thead>";
foreach($dt_keranjang as $datatr)
{
				
$html .= "			<tr>
						<td>".$datatr['nama']."</td>
						<td>".$datatr['kodeproduk']."</td>
						<td>".$datatr['qty']."</td>
						<td align=right>".$datatr['totalharga']."</td>
					</tr>";
}
$html .= "			<tr>
						<td colspan=3>Jumlah yang harus dibayarkan</td>
						<td align=right>".$totaltagihan2."</td>
					</tr>";
				if($totaldiskon != '0')
				{
$html .= "			<tr>
						<td colspan=3>Diskon ($kodevoucher)</td>
						<td align=right>(-)".$totaldiskon2."</td>
					</tr>";
				}	
				if($pengiriman != 'Pickup Point')
				{
$html .= "			<tr>
						<td colspan=3>Ongkos Kirim($namaagen - $ongkosinfo)</td>
						<td align=right>".$ongkoskirim2."</td>
					</tr>";
				}	
$html .= "			<tr>
						<td colspan=3><strong>Total</strong></td>
						<td align=right><strong>".$totaltagihanakhir2."</strong></td>
					</tr>
				</table>
			</td>
		</tr>";
$html .="</table><br clear=all /><br clear=all />";

$html .="<table border=\"0\" width=\"100%\">
			<tbody align=\"top\">
				<tr>
					<th width=\"50%\" style=\"background-color:#cccccc\">Informasi Pembayaran</th>
					<th width=\"50%\" colspan=\"2\" style=\"background-color:#cccccc\">Informasi Tagihan</th>
				</tr>
			</tbody>

			<tbody align=\"top\">
				<tr>
					<td rowspan=\"4\" scope=\"row\" class=\"sub_spec\">
						Pembayaran dilakukan secara <strong>$pembayaran</strong><br />";
						if($pembayaran == 'Transfer')
						{
								$html .="Untuk menyelesaikan pembayaran transaksi ini, silahkan transfer ke : <br /><br>
								<ol>";
								foreach($rekening as $data)
								{
									$html .= "<li><b>".$data['bank']." (".$data['akun']. "a.n ".$data['namaak'].")</b></li>";
								}
								$html .="</ol><br>Setelah melakukan pembayaran kami harapkan untuk melakukan konfirmasi pembayaran.
								<br>Batas Transfer : $datetransfer.";
						}
				$html .="</td>
						<td class=\"info_spec\" width=\"20%\">Nama</td>
						<td class=\"sub_spec\">$userfullname</td>
					</tr>
					<tr>
						<td class=\"info_spec\">Email</td>
						<td class=\"sub_spec\">$email</td>
					</tr>
					<tr>
						<td class=\"info_spec\">Alamat</td>
						<td class=\"sub_spec\">$billingalamat</td>
					</tr>
				</tbody>
			</table><br clear=all /><br clear=all />";
			
$html .="<table border=\"0\" width=\"100%\">";
		if($pengiriman != 'Pickup Point')
		{
			$html .="
			<tr>
				<th width=\"100%\" colspan=\"2\" style=\"background-color:#cccccc\">Informasi Pengiriman</th>
			</tr>

			<tr>
				<td width=\"30%\" class=\"info_spec\">Agen Pengiriman</td>
				<td class=\"sub_spec\">$namaagen - $ongkosinfo</td>
			</tr>";
            if (!empty($no_resi))
			{
				$html .="<tr>
					<td class=\"info_spec\">Nomor Resi Pengiriman</td>
					<td class=\"sub_spec\">$no_resi</td>
				</tr>";
            }
             $html .="<tr>
				<td class=\"info_spec\">Nama Lengkap</td>
				<td class=\"sub_spec\">$namalengkap</td>
			</tr>
			<tr>
				<td class=\"info_spec\">Alamat Pengiriman</td>
				<td class=\"sub_spec\">$alamatpengiriman</td>
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

$tpl->assign("html",$html);

/*include($lokasiweb."librari/mpdf/mpdf.php");

$mpdf=new mPDF();
$mpdf->WriteHTML($html);
$mpdf->Output();*/
?>