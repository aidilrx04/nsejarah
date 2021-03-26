<?php

/**
 * Ulangkaji
 */

require '../php/conn.php';

// public view ?
// accessMurid( 'Akses tanpa kebenaran!' );

_assert( isset( $_GET['id_skor'] ), alert( 'Sila masukkan ID Skor' ) . back(), 1 );

_assert( $skor_murid = getSkorMurid( $_GET['id_skor'] ), alert( 'ID Skor tidak sah!' ) . back(), 1);

$kuiz = getKuizById( $skor_murid['sm_kuiz'] );
$murid = getMuridById( $skor_murid['sm_murid'] );
$soalan_list = getSoalanByKuiz( $kuiz['kz_id'] );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ulangkaji</title>

    <link rel="stylesheet" href="/base.css">

    <style>
        /** Custom style */
        .soalan
        {
            padding: 10px 5px;
            margin-bottom: 10px;
        }
        .soalan p
        {

            padding: 10px 0;
            background-color: #ddd;

        }

        .soalan .jawapan
        {

            margin: 5px 0;
            padding: 10px 0;

        }

        .soalan .jawapan .input-container
        {

            display: block;

        }
    </style>
</head>
<body>
    <div class="container">
        <div id="navigasi"><?php require '../header.php'?></div>

        <main>
            <div id="ulangkaji">
                <h2>Bahagian Ulangkaji</h2>

                <h3>Anda telah selesai menjawab kuiz ini</h3>

                <p>
                    <b>Nama Kuiz: </b><?=$kuiz['kz_nama']?>
                    <br>
                    <b>Skor: </b><?=round($skor_murid['sm_skor'] / 100 * count( $soalan_list ))?> / <?=count( $soalan_list )?>
                    <br>
                    <b>Peratus: </b><?=$skor_murid['sm_skor']?>% 
                </p>

                <hr>

                <div id="soalan">
                    <?php
                    foreach( $soalan_list as $bil=>$soalan )
                    {

                    ?>
                    <div class="soalan">
                        <b>No Soalan: </b><?=++$bil?>
                        <br>
                        <p>
                        <?=$soalan['s_teks']?>
                        <br>
                        <?=$soalan['s_gambar'] ? "<img src=\"{$soalan['s_gambar']}\" style=\"max-width:300px;\">" : ""?>
                        </p>

                        <div class="jawapan">
                            <?php
                            $jawapan_list = getJawapanBySoalan( $soalan['s_id'] );

                            foreach( $jawapan_list as $jawapan )
                            {

                            ?>
                            <li><?=$jawapan['j_teks']?></li>
                            <?php

                            }
                            ?>
                        </div>

                        <div class="status-jawapan">
                            <b class="betul">Jawapan Sebenar: </b><?=getJawapanById( getJawapanToSoalan( $soalan['s_id'] )['sj_jawapan'] )['j_teks']?>
                            <br>

                            <b>Jawapan anda: </b><?php

                            
                            
                            $jawapan_murid = array_filter( getJawapanMurid( $murid['m_id'], $kuiz['kz_id'] ), function( $j ) 
                            {
                                global $soalan, $murid;
                                return $j['jm_soalan'] == $soalan['s_id'] && $j['jm_murid'] == $murid['m_id'];
                            } );

                            $jm = $jawapan_murid[array_key_first( $jawapan_murid )];

                            if( $jm['jm_jawapan'] == NULL ) echo 'Tidak dijawab';

                            // foreach( $jawapan_murid as $j ) echo getJawapanById( $j['jm_jawapan'] )['j_teks'];
                            else echo getJawapanById( $jm['jm_jawapan'] )['j_teks'];

                            
                            $jm_status = $jm['jm_status'];

                            $status = $jm_status === NULL ? "Tidak Dijawab" : ( $jm_status == 1 ? "BETUL <b style=\"color: green\">&check;</b>" : "SALAH <b style=\"color: red;\">&times;</b>" );
                            ?>
                            <br>

                            <b>Status: </b><?=$status?>

                        </div>

                        <hr>
                    
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </main>

        <?php require '../footer.php'?>
    </div>
    
</body>
</html>