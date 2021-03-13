<h1 align="center">NSEJARAH</h1>
<hr>

<?php

#session already started at php/conn.php;
if( isset( $_SESSION['jenis'] ) )
{

?>
<b>Nama Murid: </b> <?=$_SESSION['nama']?> |
<a href="/murid/pilih_latihan.php">Laman Utama</a> |
<a href="/logout.php">Logout</a>

<hr>
<?php

}

?>