<?php 
header("Content-Type: application/xml; charset=ISO-8859-1"); 
echo"<?php xml version=\"1.0\" encoding=\"UTF-8\"?>";
echo "
<rss version=\"2.0\"
	xmlns:content=\"http://purl.org/rss/1.0/modules/content/\"
	xmlns:wfw=\"http://wellformedweb.org/CommentAPI/\"
	xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
	xmlns:atom=\"http://www.w3.org/2005/Atom\"
	xmlns:media=\"http://search.yahoo.com/mrss\"
	>
<channel>
<title>$title | $rubrik</title>
<atom:link href=\"$fulldomain/$kanal/rss\" rel=\"self\" type=\"application/rss+xml\" />
<link>$fulldomain/$kanal/rss</link>
<description>[25 $rubrik terbaru $title]</description>
<copyright>Copyright by $nama_perusahaan! - All rights reserved.</copyright>
<language>id</language>
";


		//Cari Data
		$mysql = "select * from tbl_$kanal where published='1' order by create_date desc limit 25";
		$hasil = sql( $mysql);
		while($data =  sql_fetch_data($hasil))
			{
			$tanggal = $data['create_date'];
			$nama = $data['nama'];
			$alias = $data['alias'];
			$id = $data['id'];
			$creator = $data['creator'];
			$ringkas = $data['ringkas'];
			$secid = $data['secid'];
			
			$perintah1 = "select alias,secid from tbl_berita_sec where secid='$secid'";
			$hasil1 = sql( $perintah1);
			$data1 =  sql_fetch_data($hasil1);
			$secalias = $data1['alias'];
			$secid	= $data1['secid'];
			sql_free_result($hasil1);
		
			
			$date1 = strtotime($tanggal);
			$tanggal = date("D, d M Y H:i:s O",$date1);
			$content = $data['ringkas'];
			$content2 = $data['lengkap'];
			
			$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
                 "'<[\/\!]*?[^<>]*?>'si",           // Strip out HTML tags
                 "'([\r\n])[\s]+'",                 // Strip out white space
                 "'&(quot|#34);'i",                 // Replace HTML entities
                 "'&(amp|#38);'i",
                 "'&(lt|#60);'i",
                 "'&(gt|#62);'i",
                 "'&(nbsp|#160);'i",
                 "'&(iexcl|#161);'i",
                 "'&(cent|#162);'i",
                 "'&(pound|#163);'i",
                 "'&(copy|#169);'i",
                 "'&#(\d+);'e");                    // evaluate as php

			$replace = array ("",
							  "",
							  "\\1",
							  "\"",
							  "&",
							  "<",
							  ">",
							  " ",
							  chr(161),
							  chr(162),
							  chr(163),
							  chr(169),
							  "chr(\\1)");
			
			$content = preg_replace($search, $replace, $content);
			$content = htmlspecialchars($content);
			
			$datacontent ="$content [...]";
			$contentURL = "$fulldomain/$kanal/read/$secalias/$id/$alias";
						
			echo"
			<item>
			  <title>$nama</title>
			  <link>$contentURL</link>
			  <comments>$contentURL</comments>
   			  <pubDate>$tanggal</pubDate>
			  <author>$title";
			  if(!empty($creator)) echo" - $creator"; 
			echo"</author>
			  <guid isPermaLink=\"false\">$contentURL</guid>
			  <description>$datacontent</description>
			  <content:encoded><![CDATA[ $content2 ]]></content:encoded>
			</item>";
			  
		
		}
		sql_free_result($hasil);

echo"</channel></rss> ";

exit();

?>