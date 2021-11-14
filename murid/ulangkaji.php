<base href="../">
<?php

/**
 * Ulangkaji
 * 
 * ! IMPORTANT CHANGES
 * images url save in db is now filename only.
 * rather than full images path in previous version
 */

require '../php/conn.php';


// public view ?
// accessMurid( 'Akses tanpa kebenaran!' );

_assert(isset($_GET['id_murid']) && isset($_GET['id_kuiz']), alert('Sila masukkan ID Murid dan ID Kuiz') . back(), 1);

// _assert( $skor_murid = getSkorMurid( $_GET['id_skor'] ), alert( 'ID Skor tidak sah!' ) . back(), 1);

$kuiz = getKuizById($_GET['id_kuiz']);
$murid = getMuridById($_GET['id_murid']);

_assert($jm = getJawapanMurid($murid['m_id'], $kuiz['kz_id']), alert('Murid tidak menjawab kuiz lagi!') . back(), 1);

$skor = countSkorMurid($jm, $kuiz['kz_id']);
$soalan_list = getSoalanByKuiz($kuiz['kz_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ulangkaji</title>

    <link rel="stylesheet" href="base.css">

    <style>
        /** Custom style */
        /* .soalan {
            padding: 10px 5px;
            margin-bottom: 10px;
        }
 */
        /* 
        .soalan p {

            padding: 10px 0;
            background-color: #ddd;

        } */

        /* .soalan .jawapan {

            margin: 5px 0;
            padding: 10px 0;

        }

        .soalan .jawapan .input-container {

            display: block;

        } */
    </style>
</head>

<body>
    <div class="container">
        <div id="navigasi"><?php require '../header.php' ?></div>

        <main>

            <h2>Skor dan Ulangkaji</h2>

            <div id="ulangkaji">

                <!-- <h3>Anda telah selesai menjawab kuiz ini</h3> -->

                <div class="skor">
                    <h3>Skor</h3>
                    <div class="informasi-kuiz">
                        <div>
                            <b>Nama Kuiz: </b><?= $kuiz['kz_nama'] ?>
                        </div>
                        <div>
                            <b>Skor: </b><?= $skor['betul'] ?> / <?= count($soalan_list) ?>
                        </div>
                        <div>
                            <b>Peratus: </b><?= $skor['peratus'] ?>%
                        </div>
                    </div>
                </div>


                <div id="soalan">
                    <h3>Ulangkaji</h3>


                    <?php
                    foreach ($soalan_list as $bil => $soalan) {

                        $jawapan_murid = array_filter(getJawapanMurid($murid['m_id'], $kuiz['kz_id']), function ($j) {
                            global $soalan, $murid;
                            return $j['jm_soalan'] == $soalan['s_id'] && $j['jm_murid'] == $murid['m_id'];
                        });

                        $jm = $jawapan_murid[array_key_first($jawapan_murid)] ?? NULL;
                        $jawapan_murid_teks = 'Tidak Dijawab';


                        if ($jm === NULL || $jm['jm_jawapan'] === NULL) $jawapan_murid_teks =  'Tidak dijawab';

                        // foreach( $jawapan_murid as $j ) echo getJawapanById( $j['jm_jawapan'] )['j_teks'];
                        else $jawapan_murid_teks = getJawapanById($jm['jm_jawapan'])['j_teks'];


                        $jm_status =  $jm === NULL || $jm['jm_jawapan'] === NULL ? NULL : (bool)isJawapanToSoalan($jm['jm_jawapan'], $soalan['s_id']);

                        $status = $jm_status === NULL ? "Tidak Dijawab" : ($jm_status == 1 ? "BETUL <b style=\"color: green\">&check;</b>" : "SALAH <b style=\"color: red;\">&times;</b>");

                    ?>
                        <div class="soalan <?= $jm_status === NULL ? 'tidak-dijawab' : ($jm_status == 1 ? 'betul' : 'salah') ?>">
                            <div class="no-soalan">
                                <b>No Soalan: </b><?= ++$bil ?>
                            </div>
                            <p class="soalan-info">
                                <span class="s-teks"><?= $soalan['s_teks'] ?></span>
                                <?= $soalan['s_gambar'] ? "<img class=\"s-gambar\" src=\"{$IMAGE_DIR}{$soalan['s_gambar']}\" style=\"max-width:300px;\">" : "" ?>
                            </p>

                            <div class="jawapan">
                                <b>Senarai Jawapan:</b>
                                <br>
                                <?php
                                $jawapan_list = getJawapanBySoalan($soalan['s_id']);

                                foreach ($jawapan_list as $jawapan) {

                                ?>
                                    <li><?= $jawapan['j_teks'] ?></li>
                                <?php

                                }
                                ?>
                            </div>

                            <div class="status-jawapan">
                                <b class="betul">Jawapan Sebenar: </b><?= getJawapanById(getJawapanToSoalan($soalan['s_id'])['sj_jawapan'])['j_teks'] ?>
                                <br>

                                <b>Jawapan anda: </b> <span><?= $jawapan_murid_teks ?></span>

                                <br>

                                <b>Status: </b><?= $status ?>

                            </div>


                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </main>

        <?php require '../footer.php' ?>
    </div>

</body>

</html>