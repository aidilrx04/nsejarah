<?php

/**
 * Kemaskini guru
 */

require '../php/conn.php';

accessAdmin( 'Akses tanpa kebenaran!' );

if( $_SERVER['REQUEST_METHOD'] == "POST" && $_POST['submit'] == 'kemaskini_guru' )
{
    
    $id = $_POST['id'];
    $nokp = $_POST['nokp'];
    $nama = $_POST['nama'];
    $katalaluan = $_POST['katalaluan'];
    $jenis = $_POST['jenis'];

    if( $berjaya = updateGuru( $id, $nokp, $nama, $katalaluan, $jenis ) )
    {

        echo alert( 'Kemaskini berjaya' ) . ( isset($_GET['redir']) ? redirect( $_GET['redir'] ) : '' );

    }
    else die( alert( 'Kemaskini gagal!' ) . back() );

}

_assert( isset($_GET['id_guru']), alert( 'Sila masukkan ID guru' ) . back(), 1 );

_assert( $guru = getGuru( $_GET['id_guru'] ), alert( 'ID tidak sah!' ) . back(), 1 );

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kemaskini Guru</title>
</head>
<body>
    <main>
        <?php require 'header_guru.php';?>

        <form action="" method="post" class="kemaskini-form">
            <input type="hidden" name="id" value="<?=$guru['g_id']?>">

            <label for="nokp" class="input-container">
                <span>No. Kad Pengenalan</span>

                <input type="text" name="nokp" id="nokp" class="input-field" value="<?=$guru['g_nokp']?>" minlength="12" maxlength="12" required="required">
            </label>

            <label for="nama" class="input-container">
                <span>Nama</span>

                <input type="text" class="input-field" id="nama" name="nama" value="<?=$guru['g_nama']?>" maxlength="255" required="required">
            </label>

            <label for="katalaluan" class="input-container">
                <span>Katalaluan</span>

                <input type="password" name="katalaluan" id="katalaluan" class="input-field" value="<?=$guru['g_katalaluan']?>" maxlength="15" required="required">
            </label>

            <label for="jenis" class="input-container">
                <?php
                $jenis = $guru['g_jenis'] == 'admin' ? 'guru' : 'admin';
                ?>

                <span>Jenis</span>

                <select name="jenis" id="jenis" class="input-field">
                    <option value="<?=$guru['g_jenis']?>" selected><?=$guru['g_jenis']?></option>

                    <option value="<?=$jenis?>"><?=$jenis?></option>
                </select>
            </label>

            <button type="submit" name="submit" value="kemaskini_guru">Kemaskini</button>
        </form>
        <?php require '../footer.php';?>
        
    </main>
</body>
</html>