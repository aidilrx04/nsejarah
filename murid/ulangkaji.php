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
</head>
<body>
    <main>
        <div id="ulangkaji">
            <h2>Bahagian Ulangkaji</h2>

            <h3>Anda telah selesai menjawab kuiz ini</h3>

            <p>
                <b>Nama Kuiz: </b><?=$kuiz['kz_nama']?>
                <br>
                <b>Skor: </b><?=floor($skor_murid['sm_skor'] / 100 * count( $soalan_list ))?> / <?=count( $soalan_list )?>
                <br>
                <b>Peratus: </b><?=$skor_murid['sm_skor']?>% 
            </p>

            <div id="soalan">
                <?php
                foreach( $soalan_list as $bil=>$soalan )
                {

                ?>
                <hr>
                <div class="soalan">
                    <b>No Soalan: </b><?=++$bil?>
                    <br>
                    <?=$soalan['s_teks']?>
                    <br>
                    <?=$soalan['s_gambar'] ? "<img src=\"{$soalan['s_gambar']}\" style=\"max-width:300px;\">" : ""?>

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

                <b>Jawapan Sebenar: </b><?=getJawapanById( getJawapanToSoalan( $soalan['s_id'] )['sj_jawapan'] )['j_teks']?>
                <br>
                <b>Jawapan anda: </b><?php
                
                $jawapan_murid = array_filter( getJawapanMurid( $murid['m_id'], $kuiz['kz_id'] ), function( $j ) 
                {
                    global $soalan, $murid;
                    return $j['jm_soalan'] == $soalan['s_id'] && $j['jm_murid'] == $murid['m_id'];
                } );

                foreach( $jawapan_murid as $j ) echo getJawapanById( $j['jm_jawapan'] )['j_teks'];
                ?>
                <br>

                <b>Status: </b><?php foreach( $jawapan_murid as $j ) echo $j['jm_status'] ? "BETUL" : "SALAH"?>
                <?php
                }
                ?>
            </div>
        </div>
    </main>
</body>
</html>