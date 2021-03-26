<?php

require '../php/conn.php';

// public access or not idk
// accessMurid( 'Akses tanpa kebenaran' );

// check jika parameter id_skor wujud
_assert( isset( $_GET['id_skor'] ) && !empty( $_GET['id_skor'] ), alert( 'Sila masukkan ID Skor' ) . back(), 1 );

_assert( $skor_murid = getSkorMurid( $_GET['id_skor'] ), alert( 'ID Skor tidak sah!' ) . back(), 1 );

$murid = getMuridById( $skor_murid['sm_murid'] );
$kuiz  = getKuizById( $skor_murid['sm_kuiz'] );
$skor  = $skor_murid['sm_skor'];
$soalan_list = getSoalanByKuiz( $kuiz['kz_id'] );
$jawapan_murid_raw = getJawapanMurid( $murid['m_id'], $kuiz['kz_id'] );
$jawapan_murid = [];


foreach( $jawapan_murid_raw as $j )
{

    $jawapan_murid[$j['jm_soalan']] = $j['jm_jawapan'];

}

// var_dump( $jawapan_murid );

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Semak Jawapan</title>

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
            <div id="keputusan">
                <h2>Keputusan</h2>
                Jumlah markah: <?=round( $skor/100 * count( $soalan_list ) )?> / <?=count( $soalan_list )?>
                <br>
                Peratus: <?=$skor?>%
                <hr>

            </div>

            <div id="semak-jawapan">
                <?php
                
                foreach( $soalan_list as $bil=>$soalan )
                {

                    $jawapan_list = getJawapanBySoalan( $soalan['s_id'] );
                    $jawapan_soalan = $jawapan_murid[$soalan['s_id']];
                    $jawapan_betul = getJawapanToSoalan( $soalan['s_id'] )['sj_jawapan'];

                ?>
                <div class="soalan" style="background-color: <?=$jawapan_soalan == NULL ? "#FFFF0066" : ( $jawapan_soalan == $jawapan_betul ? "#00ff0066" : "#ff000066" )?>" >
                    <p>
                    <b><?=++$bil?>. </b>

                        <?=$soalan['s_teks']?>
                        <br>
                        <?=$soalan['s_gambar'] ? "<img src=\"{$soalan['s_gambar']}\" style=\"max-width: 300px;\">" : ""?>
                    </p>
                    <div class="jawapan">
                        <b>Jawapan</b>
                        <br>
                        <?php

                        foreach( $jawapan_list as $jawapan )
                        {
                            //check samada jawapan ialah jawapan murid atau jawapan sebenar
                            $isJawapan = isJawapanToSoalan( $jawapan['j_id'], $soalan['s_id'] );
                            $jawapan_ = ( $jawapan['j_id'] == $jawapan_soalan ) || ( $isJawapan );

                        ?>
                        <label>
                            <input type="checkbox"<?=$jawapan_ ? "checked" : ""?>
                            disabled>

                            <span style="color: <?=$isJawapan ? "green" : ($jawapan_ ? "red" : "black")?>"><?=$jawapan['j_teks']?>&nbsp;<?=$isJawapan ? "&check;" : ($jawapan_ ? "&times;" : "")?></span>
                        </label>

                        <br>
                        <?php

                        }
                        ?>
                    </div>
                </div>
                <?php

                }

                ?>
                <hr>
            </div>

        </main>

        <?php require '../footer.php'?>
    </div>
    
</body>
</html>