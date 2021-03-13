<?php

require '../php/conn.php';

accessMurid( 'Akses tanpa kebenaran!' );

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'submit_jawapan' )
{

    # Jika murid sudah menjawab, paparan ralat akan dikeluarkan
    _assert( ( $sm =  getSkorByMurid( $_SESSION['id'], $_POST['kuiz']['id'] ) ), alert( 'Anda telah menjawab kuiz. Tidak boleh mencuba lagi!' ), 0 );

    // var_dump( $sm );

    // echo '<br>';


    // print_r( $_POST );

    $kuiz = getKuizById( $_POST['kuiz']['id'] );
    $soalan_list = $_POST['s'];
    $jumlah = count( $soalan_list );
    $bil_berjaya = 0;

    foreach( $soalan_list as $iid=>$soalan )
    {

        if( $betul = registerJawapanMurid( $_SESSION['id'], $soalan['id'], $soalan['j'] ) )
        {

            if( $betul ) $bil_berjaya++;

        }

    }

    // register skor_murid
    $skor = ( $bil_berjaya / $jumlah ) * 100;
    if( $id_skor = registerSkorMurid( $_SESSION['id'], $kuiz['kz_id'], $skor ) )
    {

        echo alert( 'Jawapan berjaya dimuatnaik!' ) . redirect( "jawab_semak.php?id_skor={$id_skor}" );

    }
    else die( alert( 'Jawapan gagal dimuatnaik!' ) . back() );
    
}

_assert( isset( $_GET['id_kuiz'] ) && !empty( $_GET['id_kuiz'] ), alert( 'Sila masukkan ID Kuiz' ) . back(), 1 );

// jika murid sudah jawab, pindah lokasi ke jawab_semak.php
_assert( !( $sm = getSkorMuridByKuiz( $_SESSION['id'], $_GET['id_kuiz'] ) ), redirect( "jawab_semak.php?id_skor=" . ($sm ? $sm['sm_id'] : "") ), 1 );
// var_dump( getSkorMuridByKuiz( $_SESSION['id'], $_GET['id_kuiz']) );

$murid = getMuridById( $_SESSION['id'] );
$kuiz = getKuizById( $_GET['id_kuiz'] );


_assert( $murid['m_kelas'] == $kuiz['kz_ting'], alert( 'Akses tanpa kebenaran!' ) . back(), 1 );

$soalan_list = getSoalanByKuiz( $kuiz['kz_id'] );

_assert( count( $soalan_list ) > 0, alert( 'Kuiz tiada soalan!' ) . back(), 1 );

$mula = isset( $_GET['m'] ) ? $_GET['m'] : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$kuiz['kz_nama']?> | Nsejarah</title>
</head>
<body>
    <main>
        <h2><?=$kuiz['kz_nama']?></h2>
    
        <?php
        if( !$mula )
        {

        ?>
        <form action="">
            <input type="hidden" name="id_kuiz" value="<?=$kuiz['kz_id']?>">
            <button type="submit" name="m" value="1">Mula</button>
        </form>
        <?php

        }
        else
        {

        ?>
        <div id="jawab-kuiz">
        
            <form action="" method="post" id="jawab-form">
                <input type="hidden" name="kuiz[id]" value="<?=$kuiz['kz_id']?>">

                <?php

                foreach( $soalan_list as $bil=>$soalan )
                {

                    $soalan_id = uniqid();

                ?>
                <div class="soalan">
                    <input type="hidden" name="s[<?=$soalan_id?>][id]" value="<?=$soalan['s_id']?>">
                    <hr>
                    <p>
                        <b><?=$bil++?>.</b>
                        <?=$soalan['s_teks']?>

                        <div style="max-width: 300px;"><?=$soalan['s_gambar'] ? "<img style=\"max-width: 100%;\" src=\"{$soalan['s_gambar']}\">" : ''?></div>
                    </p>

                    <div class="jawapan">
                        <?php
                        // randomize jawapan position
                        $jawapan_list = getJawapanBySoalan( $soalan['s_id'] );
                        shuffle( $jawapan_list );

                        foreach( $jawapan_list as $jawapan )
                        {

                            $jawapan_id = uniqid();

                        ?>
                        <label for="<?=$jawapan_id?>" class="input-container">
                            <input type="radio" name="s[<?=$soalan_id?>][j]" value="<?=$jawapan['j_id']?>" id="<?=$jawapan_id?>" required>
                            <span>
                                <?=$jawapan['j_teks']?>
                            </span>
                        </label>
                        <?php

                        }
                        ?>
                    </div>
                </div>
                <?php

                }
                ?>
            
                <button type="submit" name="submit" value="submit_jawapan">Hantar</button>
            
            </form>

            <?php
            if( $kuiz['kz_jenis'] == 'kuiz' )
            {
            ?>
            <script>
            const form = document.querySelector( "#jawab-form" );

            </script>
            <?php
            }
            ?>
        </div>
        <?php

        }
        ?>
    </main>
</body>
</html>