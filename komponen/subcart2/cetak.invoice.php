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
		<td align=right valign=top><strong>Tanggal : </strong>".$tanggal."<br> </td>
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

?>