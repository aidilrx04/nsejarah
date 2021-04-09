<?php
/**
 * * Kemaskini kuiz
 */

require '../php/conn.php';

accessGuru( 'Akses tanpa kebenaran' );

if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{

    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $tarikh = $_POST['tarikh'];
    $jenis = $_POST['jenis'];
    $masa = isset( $_POST['masa'] ) ? $_POST['masa'] : null;

    if( $id_kuiz = updateKuiz( $id, $nama, $tarikh, $jenis, $masa ) )
    {

        $soalan = $_POST['s'];
        $update = $soalan['u'];
        $baru = isset( $soalan['b'] ) ? $soalan['b'] : [];
        $padam = isset( $soalan['d'] ) ? $soalan['d'] : [];
        $padam_gambar = isset( $soalan['dg'] ) ? $soalan['dg'] : [];
        // print_r( $_POST );

        //padam gambar terlebih dahulu
        //kerana gambar akan dikemaskini(masuk baru)
        //selepas foreach ini
        foreach( $padam_gambar as $pg )
        {

            $soalan = getSoalanById( $pg );

            deleteImage( $soalan['s_id'] );
            removeImage( $soalan['s_gambar'] );
        }

        foreach( $update as $uid=>$us )
        {

            $sId = $us['id'];
            $sTeks = $us['teks'];
            $sImage = $_FILES[$uid]['size'] > 0 ? $_FILES[$uid] : NULL;
            $jBetul = $us['jBetul'];
            $jawapan_list = $us['j'];
            

            if( $uid_soalan = updateSoalan( $sId, $sTeks ) )
            {

                //update image
                if( $sImage !== NULL )
                {

                    removeImage( getSoalanById( $sId )['s_gambar'] );
                    updateImage( $sId, $sImage );

                }

                foreach( $jawapan_list as $jawapan )
                {

                    $jId = $jawapan['id'];
                    $jTeks = $jawapan['teks'];

                    if( $id_jawapan = updateJawapan( $jId, $jTeks ) )
                    {

                        if( $jBetul == $id_jawapan )
                        {

                            updateSoalanJawapan( $uid_soalan, $id_jawapan );

                        }

                    }

                }

            }

        }

        foreach( $baru as $bid=>$b )
        {

            $sbTeks = $b[0];
            $sbImage = $_FILES[$bid];
            $jBetul = $b[1];

            if( $id_soalan = registerSoalan( $id_kuiz, $sbTeks, $sbImage ) )
            {

                $jawapan_list = $b['j'];

                foreach( $jawapan_list as $count=>$jawapan )
                {

                    $jTeks = $jawapan[0];

                    if( $id_jawapan = registerJawapan( $id_soalan, $jTeks ) )
                    {

                        if( $count == $jBetul ) registerSoalanJawapan( $id_soalan, $id_jawapan );

                    }

                }

            }

        }

        foreach( $padam as $p )
        {

            if( deleteSoalan( $p ) )
            {}

        }

    }

}

_assert( isset( $_GET['id_kuiz'] ), alert( 'Sila masukkan ID Kuiz' ) . back(), 1 );
#mendapatkan data kuiz dripada query
_assert( $kuiz = getKuizById( $_GET['id_kuiz'] ), alert( 'ID Kuiz tidak sah!' ) . back(), 1 );
# halang daripada guru lain mengubah isi kandungan kuiz ini tanpa kebenaran guru pencipta
# admin automatik mendapat kebenaran mengubah :)
_assert( isAdmin() || $kuiz['kz_guru'] == $_SESSION['id'], alert( 'Akses tanpa kebenaran!' ) .back(), 1 );


/**
 * Data soalan berdasarkan ID
 * @param int $id_soalan ID Soalan
 * @return array|bool Data Soalan
 */
function getSoalanById( int $id_soalan )
{

    global $conn;
    $query = "SELECT * FROM soalan WHERE s_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_soalan );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0  ) return $res->fetch_assoc();

    }
    return false;

}

/**
 * Kemaskini Gambar Sesuatu Soalan
 * @param int $id_soalan ID Soalan
 * @param resource $image Gambar hendak dikemaskini
 * @return bool TRUE jika berjaya, FALSE sebaliknya
 */
function updateImage( $id_soalan, $image )
{

    global $conn;
    $gambar_url = uploadImage( $image );
    $query = "UPDATE soalan SET s_gambar = ? WHERE s_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ss', $gambar_url, $id_soalan );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return true;

    }

    return false;

}

/**
 * Padam data gambar sesuatu soalan
 * @param int $id_soalan ID Soalan
 * @return bool TRUE jika berjaya, FALSE sebaliknya
 */
function deleteImage( $id_soalan )
{

    global $conn;
    $query = "UPDATE soalan SET s_gambar = NULL WHERE s_id = ?";
    
    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_soalan );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return true;

    }

    return false;

}

/**
 * Padam data gambar dari server
 * @param string $path Lokasi serta nama gambar
 * @return bool TRUE jika berjaya, FALSE sebaliknya.
 */
function removeImage( $path )
{

    $path2check = '/images/';
    $path_gambar = substr( $path, 0, strlen( $path2check ) ) === $path2check ? '..' . $path : '';

    $dirpath = dirname( $path_gambar );
    $exist = file_exists( $path_gambar );
    $isReal = realpath( $dirpath );

    if( $exist && $isReal ) return unlink( $path_gambar );
    return false;

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kemaskini kuiz</title>

    <link rel="stylesheet" href="/base.css">
</head>
<body>
    <div class="container">
        <div id="navigasi">
            <?php require 'header_guru.php';?>
        </div>

        <main>

            <div class="kemaskini-kuiz-form">
                <h2>Kemaskini Kuiz</h2>

                <form action="" method="post" enctype="multipart/form-data" style="width: 70%;">
                    <div class="maklumat-kuiz">
                        <h3>Maklumat kuiz</h3>

                        <input type="hidden" name="id" value="<?=$kuiz['kz_id']?>">

                        <label for="nama" class="input-container">
                            <span>Nama Kuiz</span>

                            <input type="text" name="nama" id="nama" class="input-field" value="<?=$kuiz['kz_nama']?>" maxlength="255" required="required">
                        </label>

                        <div>
                            <label for="tarikh" class="input-container">
                                <span>Tarikh</span>

                                <?php
                                $tarikh = $kuiz['kz_tarikh'] ? $kuiz['kz_tarikh'] : date( 'Y-m-d' );
                                ?>

                                <input type="date" name="tarikh" id="tarikh" class="input-field" value="<?=$tarikh?>">
                            </label>

                            <label for="jenis" class="input-container">
                                <span>Jenis</span>

                                <?php

                                $jenis = $kuiz['kz_jenis'];
                                
                                ?>

                                <select name="jenis" id="jenis" class="input-field">
                                    <option value="latihan"<?=$jenis == 'latihan' ? 'selected' : ''?>>Latihan</option>

                                    <option value="kuiz"<?=$jenis == 'kuiz' ? 'selected' : ''?>>Kuiz</option>
                                </select>
                            </label>

                            <label for="masa" class="input-container">
                                <span>Masa(minit)</span>

                                <input type="number" name="masa" id="masa" class="input-field" <?=$jenis == 'latihan' ? 'disabled' : ''?> value="<?=$kuiz['kz_masa']?>" required>
                            </label>
                        </div>
                    </div>

                    <div id="soalan">
                        <h3>Soalan</h3>

                        <div id="soalan-list">
                            <?php
                            
                            $soalan_list = getSoalanByKuiz( $kuiz['kz_id'] );

                            foreach( $soalan_list as $soalan )
                            {

                                $idSoalan = uniqid( 's' );

                            ?>
                            <div class="soalan">
                                <input type="hidden" name="s[u][<?=$idSoalan?>][id]" value=<?=$soalan['s_id']?>>

                                <label class="input-container">
                                    <input type="text" name="s[u][<?=$idSoalan?>][teks]" value="<?=$soalan['s_teks']?>" class="input-field">
                                </label>

                                <?php
                                //papar gambar jika ada
                                if( isset( $soalan['s_gambar'] ) && $gambar = $soalan['s_gambar'] )
                                {

                                ?>
                                <div style="padding:10px; background-color: #ddd;display:flex;justify-content:left;" class="gambar-container">
                                    <img style="max-width: 300px;" src="<?=$gambar?>" alt="<?=$gambar?>">

                                    <button type="button" style="margin: 0 10px;" class="padam-gambar" data-padam-gambar="<?=$soalan['s_id']?>"> <b>&minus;</b> Padam Gambar</button>
                                </div>
                                <?php

                                }
                                ?>

                                <label class="input-container">
                                    <input type="file" name="<?=$idSoalan?>" id="">

                                    <button type="button" data-padam="<?=$soalan['s_id']?>" class="delete-exist">Padam soalan</button>
                                </label>

                                <div class="jawapan-container input-container">
                                    <h4>Jawapan</h4>

                                    <?php

                                    $jawapan_list = getJawapanBySoalan( $soalan['s_id'] );

                                    foreach( $jawapan_list as $jawapan )
                                    {

                                        $idJawapan = uniqid( 'j' );
                                        $jBetul = isJawapanToSoalan( $jawapan['j_id'], $soalan['s_id'] );

                                    ?>
                                    <div>
                                        <input type="hidden" name="s[u][<?=$idSoalan?>][j][<?=$idJawapan?>][id]" value="<?=$jawapan['j_id']?>">

                                        <input type="text" name="s[u][<?=$idSoalan?>][j][<?=$idJawapan?>][teks]" value="<?=$jawapan['j_teks']?>">

                                        <input type="radio" name="s[u][<?=$idSoalan?>][jBetul]" value="<?=$jawapan['j_id']?>"<?=$jBetul ? ' checked ' : ''?> required>
                                    </div>
                                    <?php

                                    }
                                    
                                    ?>

                                </div>
                            </div>
                            <hr>
                            <?php

                            }

                            ?>
                        </div>

                        <div id="soalan-padam"></div>
                        <div id="gambar-padam"></div>

                        <button id="tambah-soalan" class="btn btn-success" type="button" style="background: blue; color: white;">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Soalan</span>
                        </button>
                    </div>

                    <button type="submit" id="submit" name="submit" value="kemaskini-kuiz">Kemaskini</button>
                </form>
            </div>
        </main>

        <?php require '../footer.php';?>

    </div>
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

    <script src="kuiz.js"></script>

    <script>
    /**
     * Custom scriipt
     */
    $( function()
    {

        const deleteBtns = $( '.delete-exist' );
        const deleteContainer = $( '#soalan-padam' );
        console.log( deleteBtns );

        for( let i = 0; i < deleteBtns.length; i++ )
        {

            const btn = $( deleteBtns[i] );

            btn.click( function()
            {

                const padamVal = $( this ).attr( 'data-padam' );
                const container = $( this ).closest( '.soalan' );
                const delElem = $( document.createElement( 'input' ) )
                                .attr( {
                                    type: 'hidden',
                                    name: 's[d][]',
                                    value: padamVal
                                } )
                                .appendTo( deleteContainer );
                
                container.remove();

            } );

        }

        //padam gambar
        const deleteImageBtns = $( '.padam-gambar' );
        const deleteImageContainer = $( '#gambar-padam' );

        for( let i = 0; i < deleteImageBtns.length; i++ )
        {

            const btn = $( deleteImageBtns[i] );

            btn.click( function()
            {
                
                const btn = $( this );
                const idSoalan = btn.attr( 'data-padam-gambar' );
                const container = btn.closest( '.gambar-container' );
                console.log( container );

                const deleteElem = $( document.createElement( 'input' ) )
                                   .attr({
                                       type: 'hidden',
                                       name: `s[dg][]`,
                                       value: `${idSoalan}`
                                   })
                                   .appendTo( deleteImageContainer );

                container.remove();

            } );

        }

    } )
    </script>
</body>
</html>