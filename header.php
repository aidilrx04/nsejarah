<?php

require_once 'php/conn.php';

?>

<div id="nav">
    <h1>NSejarah</h1>


    <?php if (isset($_SESSION['jenis']) && $jenis = $_SESSION['jenis']) { ?>
        <span class="nama">
            <b>Nama <?= $_SESSION['jenis'] == 'murid' ? "Murid" : "Guru" ?>
            </b>
            <span><?= $_SESSION['nama'] ?></span>
        </span>
    <?php } ?>

    <ul>
        <li>
            <span style="text-decoration: none;" class="resize nav-link">
                <span>Saiz Teks</span>
                <button id="teks-kurang" value="-1">&minus;</button>
                <button utton id="teks-reset" value="2">Reset</button>
                <button id="teks-tambah" value="1">&plus;</button>
            </span>
        </li>
        <?php if (isset($_SESSION['jenis'])) { ?>
            <li>
                <a href="<?= $jenis == 'murid' ? 'murid/pilih_latihan.php' : 'guru/' ?>">Laman Utama</a>
            </li>

            <li>
                <a href="logout.php">Logout</a>
            </li>

        <?php } else { ?>

            <li>
                <a href=".">Login</a>
            </li>

        <?php } ?>
    </ul>
</div>

<script src="saiz_teks.js"></script>