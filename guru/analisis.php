<base href="../">
<?php

/**
 * Analisis kuiz
 */

require '../php/conn.php';

accessGuru('Akses tanpa kebenaran');

_assert(!!($kuiz_list = getKuizByGuru($_SESSION['id'])), alert('Tiada topik untuk dianalisis') . back(), 1);

/**
 * Check if parameter tajuk is set
 */
_assert((isset($_GET['tajuk'])), redirect("guru/analisis.php?tajuk={$kuiz_list[0]['kz_id']}"), 1);

/**
 * Check if the kuiz id exist in db
 */
_assert(($kuiz = getKuizById($_GET['tajuk'])), alert('ID Kuiz tidak sah!') . back(), 1);


$guru = getGuru($kuiz['kz_guru']);
$ting = getTingById($kuiz['kz_ting']);
$kelas = getKelasById($ting['kt_kelas']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Analisis</title>

    <link rel="stylesheet" href="base.css">
</head>

<body>
    <div class="container">
        <div id="navigasi">
            <?php require 'header_guru.php'; ?>
        </div>

        <main>

            <h2>Analisis Prestasi</h2>

            <div id="analisis">
                <form action="" method="get">
                    <label for="tajuk" class="input-container">
                        <span>Tajuk</span>

                        <select name="tajuk" id="tajuk">
                            <?php

                            foreach ($kuiz_list as $kz) {

                            ?>
                                <option value="<?= $kz['kz_id'] ?>" <?= $kz['kz_id'] == $kuiz['kz_id'] ? 'selected' : '' ?>><?= $kz['kz_nama'] ?></option>
                            <?php

                            }

                            ?>
                        </select>
                    </label>

                    <button type="submit">Papar</button>
                </form>

                <br>
                <hr>
                <br>

                <div><b>Nama Guru:</b> <?= $guru['g_nama'] ?></div>

                <div><b>Kelas:</b> <?= $ting['kt_ting'] ?> <?= $kelas['k_nama'] ?></div>
                <div><b>Kuiz:</b> <?= $kuiz['kz_nama'] ?></div>

                <table border="1">
                    <thead>
                        <tr>
                            <th>Nama Murid</th>

                            <th>No Kad Pengenalan</th>

                            <th>Skor</th>

                            <th>Markah</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $murid_list = getMuridByTing($ting['kt_id']);

                        foreach ($murid_list as $murid) {

                            $jawapan_murid = getJawapanMurid($murid['m_id'], $kuiz['kz_id']);
                            $skor_murid = $jawapan_murid ? countSkorMurid($jawapan_murid, $kuiz['kz_id']) : false;
                        ?>
                            <tr>
                                <td><?= $murid['m_nama'] ?></td>

                                <td><?= $murid['m_nokp'] ?></td>

                                <td><?= $skor_murid ? $skor_murid['betul'] . '/' . $skor_murid['jumlah'] : '----' ?></td>

                                <td><?= $skor_murid ? $skor_murid['peratus'] . '%' : 'Belum dijawab' ?></td>
                            </tr>
                        <?php

                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>

        <?php require '../footer.php'; ?>
    </div>

</body>

</html>