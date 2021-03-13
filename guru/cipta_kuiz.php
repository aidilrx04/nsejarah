<?php
/**
 * Cipta Kuiz
 */

require '../php/conn.php';

accessGuru( 'Akses tanpa kebenaran!' );

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'tambah-kuiz')
{

    $nama = $_POST['nama'];
    $guru = $_SESSION['id'];
    $ting = $_POST['ting'];
    $tarikh = $_POST['tarikh'];
    $jenis = $_POST['jenis'];
    $masa = isset( $_POST['masa'] ) ? $_POST['masa'] : null;

    if( $id_kuiz = registerKuiz( $nama, $guru, $ting, $tarikh, $jenis, $masa ) )
    // if( true )
    {
        
        $soalan_list = $_POST['s']['b'];
        $sBerjaya = 0;
        $sGagal = 0;
        $sJumlah = count( $soalan_list );

        foreach( $soalan_list as $id=>$soalan )
        {

            $sTeks = $soalan[0];

            if( $id_soalan = registerSoalan( $id_kuiz, $sTeks, $_FILES[$id] ) )
            {

                $jawapan_list = $soalan['j'];
                $jBetul = $soalan[1];
                $sBerjaya++;

                foreach( $jawapan_list as $count=>$jawapan )
                {

                    $jTeks = $jawapan[0];

                    if( $id_jawapan = registerJawapan( $id_soalan, $jTeks ) )
                    {

                        if( $count == $jBetul )
                        {

                            registerSoalanJawapan( $id_soalan, $id_jawapan );

                        }

                    }

                }

            }
            else 
            {

                $sGagal++;

            }

        }

        echo alert( "Berjaya: {$sBerjaya}, Gagal: {$sGagal}, Jumlah: {$sJumlah}" );

    }
    else die( alert( 'Kuiz gagal dimuatnaik!' ) );

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cipta Kuiz</title>
</head>
<body>
    <main>
        <?php require 'header_guru.php';?>

        <div id="cipta-kuiz">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="maklumat-kuiz">
                    <h3>Maklumat kuiz</h3>

                    <label for="nama" class="input-container">
                        <span>Nama Kuiz</span>

                        <input type="text" name="nama" id="nama" class="input-file" maxlength="255" required="required">
                    </label>

                    <label for="ting" class="input-container">
                        <select name="ting" id="ting">
                            <?php
                            $ting_list = getTingByGuru( $_SESSION['id'] );

                            foreach( $ting_list as $ting )
                            {

                                $kelas = getKelasById( $ting['kt_kelas'] );

                            ?>
                            <option value="<?=$ting['kt_id']?>"><?=$ting['kt_ting']?> <?=$kelas['k_nama']?></option>
                            <?php

                            }
                            ?>
                        </select>
                    </label>

                    <div>
                        <label for="tarikh" class="input-container">
                            <span>Tarikh</span>

                            <input type="date" name="tarikh" id="tarikh" class="input-field" value="<?=date('Y-m-d')?>">
                        </label>

                        <label for="jenis" class="input-container">
                            <span>Jenis</span>

                            <select name="jenis" id="jenis" class="input-field">
                                <option value="latihan">Latihan</option>

                                <option value="kuiz">Kuiz</option>
                            </select>
                        </label>

                        <label for="masa" class="input-container">
                            <span>Masa(minit)</span>

                            <input type="number" name="masa" id="masa" class="input-field" disabled required>
                        </label>
                    </div>
                </div>

                <hr>

                <div id="soalan">
                    <h3>Soalan</h3>

                    <div id="soalan-list"></div>

                    <button id="tambah-soalan" class="btn btn-success" type="button">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Soalan</span>
                    </button>
                </div>

                <button type="submit" id="submit" name="submit" value="tambah-kuiz">Simpan</button>
            </form>
        </div>

        <?php require '../footer.php';?>

    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

    <script src="kuiz.js"></script>
</body>
</html>