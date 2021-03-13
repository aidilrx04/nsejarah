<?php

require '../php/conn.php';

accessMurid( 'Akses tanpa kebenaran!' );

$murid = getMuridById( $_SESSION['id'] );
$ting = getTingById( $murid['m_kelas'] );

$kuiz_list = getKuizByTing( $ting['kt_id'] );

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Latihan/Kuiz | Nsejarah</title>
</head>
<body>
    <main>
        <?php require '../header.php'?>


        <div id="pilih-latihan">
            <table border="1">
                <thead>
                    <tr>
                        <th>Bil</th>
                        <th>Nama Kuiz</th>
                        <th>Jenis</th>
                        <th>Bil Soalan</th>
                        <th>Skor</th>
                        <th>Peratus</th>
                        <th>Jawab</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                foreach( $kuiz_list as $bil=>$kuiz )
                {

                ?>
                <tr>
                    <td><?=$bil+1?></td>
                
                    <td><?=$kuiz['kz_nama']?></td>

                    <td><?=$kuiz['kz_jenis']?></td>

                    <td>
                    <?php
                    $soalan_list = getSoalanByKuiz( $kuiz['kz_id'] );
                    ?>
                    <?=count( $soalan_list )?>
                    </td>

                    <td>
                        <?php
                        $skor = getSkorMuridByKuiz( $murid['m_id'], $kuiz['kz_id'] );
                        /**
                         * p = ( n/t ) * 100
                         */
                        ?>
                        <?=$skor ? (round( $skor['sm_skor'] / 100 * count( $soalan_list ) ) ) : 0 ?>
                        /
                        <?=count( $soalan_list )?>
                    </td>

                    <td><?=$skor ? $skor['sm_skor'] : 0?>%</td>

                    <td>
                        <?=!$skor ? "<a href=\"jawab_kuiz.php?id_kuiz={$kuiz['kz_id']}\">Pilih</a>" : "<a href=\"ulangkaji.php?id_skor={$skor['sm_id']}\">Ulangkaji</a>"?>
                    </td>
                </tr>
                <?php

                }
                ?>
                </tbody>
            </table>
        </div>

        <?php require '../footer.php'?>

    </main>
</body>
</html>