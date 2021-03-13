<?php
?>

<h1 align="center">Nsejarah: Guru</h1>
<hr>
<a href="/guru/index.php">Laman Utama</a> |
<?php
    if( isAdmin() )
    {

    ?>
    <a href="senarai_guru.php">Maklumat Guru</a> |
    <a href="senarai_murid.php">Pengurusan Murid</a> |
    <a href="senarai_ting.php">Pengurusan Tingkatan</a> |
    <?php
    }
?>
<a href="senarai_kuiz.php">Pengurusan Kuiz</a> |
<a href="analisis.php">Analisis Prestasi</a> |
<a href="../logout.php">Logout</a>
<hr>