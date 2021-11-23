<base href="../">
<?php

/**
 * GURU
 */

require '../php/conn.php';

accessGuru('Akses tanpa kebenaran!');


$guru = getGuru($_SESSION['id']);
$ting =  getTingByGuru($guru['g_id']);
$kuiz = getKuizList($guru['g_id']);

//query

// query handler
$qk = $_GET['qk'] ?? NULL; // query kuiz



if ($qk) {
    $kuiz = array_filter($kuiz, function ($k) {
        global $qk;
        return strpos(strtolower($k['kz_nama']), strtolower($qk)) !== false;
    });
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | NSejarah</title>

    <link rel="stylesheet" href='base.css'>
</head>

<body>

    <div class="container">
        <div id="navigasi"><?php require 'header_guru.php' ?></div>

        <main>

            <h2>Laman Guru</h2>

            <div id="maklumat-guru">
                <?php


                $kelas_data = array_map(function ($t) {

                    return ['kt_id' => $t['kt_id'], 'kt_ting' => $t['kt_ting'], 'kelas' => getKelasById($t['kt_kelas'])];
                }, $ting);

                ?>

                <div id="guru">
                    <h3>Maklumat Guru</h3>

                    <span><b>Nama: </b><?= $guru['g_nama'] ?></span>
                    <br>

                    <span><b>No. Kad Pengenalan: </b><?= $guru['g_nokp'] ?></span>
                    <br>

                    <span><b>Status: </b><?= $guru['g_jenis'] ?></span>
                    <br>

                    <span>
                        <b>Kelas: </b>
                        <?php
                        foreach ($kelas_data as $k) {

                            $has_comma = count($kelas_data) != 1 && end($kelas_data) != $k ? ', ' : '';
                            echo $k['kt_ting'] . ' ' . $k['kelas']['k_nama'] . $has_comma;
                        }
                        ?>
                    </span>
                </div>
                <br>

                <div id="kelas">
                    <h3>Kelas Guru</h3>



                    <table border="1">
                        <thead>
                            <tr>
                                <th>Nama Kelas</th>

                                <th>Jumlah Murid</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($kelas_data as $kt) {

                                $jumlah = getKelasJumlah($kt['kt_id']);

                            ?>
                                <tr>
                                    <td><?= $kt['kt_ting'] ?> <?= $kt['kelas']['k_nama'] ?></td>

                                    <td><?= $jumlah ?></td>
                                </tr>
                            <?php

                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <br>

                <div id="kuiz">
                    <h3>Senarai Kuiz</h3>

                    <!-- QUERY -->
                    <div class="search-box">
                        <form action="guru/#kuiz">
                            <input type="text" name="qk" value="<?= $qk ?>" placeholder="Cari" <?= $qk ? 'autofocus' : '' ?>>
                            <button> <i class="fas fa-search"></i> Cari</button>
                        </form>
                    </div>

                    <table border="1">
                        <thead>
                            <tr>
                                <th>Nama Kuiz</th>

                                <th>Jenis</th>

                                <th>Masa</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($kuiz as $kz) {

                                $masa  = $kz['kz_masa'] ? $kz['kz_masa'] : 'Tiada';

                            ?>
                                <tr>
                                    <td><?= $kz['kz_nama'] ?></td>

                                    <td><?= $kz['kz_jenis'] ?></td>

                                    <td><?= $masa ?></td>
                                </tr>
                            <?php

                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        <?php require '../footer.php'; ?>
    </div>

</body>

</html>