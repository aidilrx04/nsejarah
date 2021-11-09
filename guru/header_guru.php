<!-- <?php
        ?>

<h1 align="center">Nsejarah: Guru</h1>
<hr>
<a href="/guru/index.php">Laman Utama</a> |
<?php
if (isAdmin()) {

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
<hr> -->

<div id="nav">
    <h1>NSejarah<br>Guru</h1>

    <hr>

    <?php if (isset($_SESSION['jenis']) && $jenis = $_SESSION['jenis']) { ?>
        <span class="nama"><b>Nama Guru: </b> <?= $_SESSION['nama'] ?></span>
    <?php } ?>

    <ul>
        <li>
            <span style="text-decoration: none;" class="resize nav-link">
                Saiz Teks:
                <button id="teks-kurang" value="-1">&minus;</button>
                <button utton id="teks-reset" value="2">Reset</button>
                <button id="teks-tambah" value="1">&plus;</button>
            </span>
        </li>
        <li>
            <a href="guru">Laman Utama</a>
        </li>
        <?php if (isAdmin()) { ?>

            <li>
                <a href="guru/senarai_guru.php">Maklumat Guru</a>
            </li>

            <li>
                <a href="guru/senarai_murid.php">Pengurusan Murid</a>
            </li>

            <li>
                <a href="guru/senarai_ting.php">Pengurusan Tingkatan</a>
            </li>



        <?php } ?>
        <li>
            <a href="guru/senarai_kuiz.php">Pengurusan Kuiz</a>
        </li>

        <li>
            <a href="guru/analisis.php">Analisis Prestasi</a>
        </li>

        <li>
            <a href="logout.php">Logout</a>
        </li>
    </ul>
</div>
<script src="saiz_teks.js"></script>