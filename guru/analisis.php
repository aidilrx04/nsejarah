<?php
/**
 * Analisis kuiz
 */

require '../php/conn.php';

accessGuru( 'Akses tanpa kebenaran' );

_assert( !!( $kuiz_list = getKuizByGuru( $_SESSION['id'] ) ), alert( 'Tiada topik untuk dianalisis' ) . back(), 1 );

/**
 * Check if parameter tajuk is set
 */
_assert( ( isset( $_GET['tajuk'] ) ), redirect( "?tajuk={$kuiz_list[0]['kz_id']}" ), 1 );

/**
 * Check if the kuiz id exist in db
 */
_assert( ( $kuiz = getKuizById( $_GET['tajuk'] ) ), alert( 'ID Kuiz tidak sah!' ) . back(), 1 );


$guru = getGuru( $kuiz['kz_guru'] );
$ting = getTingById( $kuiz['kz_ting'] );
$kelas = getKelasById( $ting['kt_kelas'] );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Analisis</title>
</head>
<body>
    <main>
        <?php require 'header_guru.php';?>

        <div id="analisis">
            <form action="" method="get">
                <label for="tajuk" class="input-container">
                    <span>Tajuk</span>

                    <select name="tajuk" id="tajuk">
                        <?php

                        foreach( $kuiz_list as $kz )
                        {

                        ?>
                        <option value="<?=$kz['kz_id']?>"<?=$kz['kz_id'] == $kuiz['kz_id'] ? 'selected' : ''?>><?=$kz['kz_nama']?></option>
                        <?php

                        }

                        ?>
                    </select>
                </label>

                <button type="submit">Papar</button>
            </form>

            <hr>

            <h3>Nama Guru: <?=$guru['g_nama']?></h3>

            <h3>Kelas: <?=$ting['kt_ting']?> <?=$kelas['k_nama']?></h3>

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
                    $murid_list = getMuridByTing( $ting['kt_id'] );

                    foreach( $murid_list as $murid )
                    {

                        $skor_murid = getSkorByMurid( $murid['m_id'], $kuiz['kz_id'] );

                        if ( $skor_murid )
                        {
                            // echo $kuiz['kz_id'];
                            $jm_list = getJawapanMurid( $murid['m_id'], $skor_murid['sm_kuiz'] );
                            $jumlah = count( $jm_list );
                            $bil_betul = 0;

                            foreach( $jm_list as $jm ) if( $jm['jm_status'] ) $bil_betul++;
                        }

                    ?>
                    <tr>
                        <td><?=$murid['m_nama']?></td>

                        <td><?=$murid['m_nokp']?></td>

                        <td><?=$skor_murid ? $bil_betul . '/' . $jumlah : ''?></td>
                        
                        <td><?=$skor_murid ? $skor_murid['sm_skor'] . '%' : 'Belum dijawab'?></td>
                    </tr>
                    <?php

                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php require '../footer.php';?>

    </main>
</body>
</html>