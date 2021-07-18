<?php

/**
 * Paparan senarai guru
 */

require '../php/conn.php';

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'daftar_guru' )
{

    $nokp = $_POST['nokp'];
    $nama = $_POST['nama'];
    $katalaluan = $_POST['katalaluan'];
    $jenis = $_POST['jenis'];

    if( $berjaya = registerGuru( $nokp, $nama, $katalaluan, $jenis ) )
    {

        echo alert( 'Data berjaya dimuatnaik!' ) . ( isset( $_GET['redir'] ) ? redirect( $_GET['redir'] ) : '' );

    }
    else die( alert( 'Data gagal dimuatnaik!' ) . back() );

}

# hanya benarkan admin sahaja mengakses laman ini
accessAdmin('Akses tanpa kebenaran!');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Senarai Guru</title>
    
    <link rel="stylesheet" href="../base.css">
</head>
<body>
    <div class="container">
        <div id="navigasi"><?php require 'header_guru.php';?></div>

        <main>

            <h2>Senarai Guru</h2>

            <table border="1">
                <thead>
                    <tr>
                        <th>Nama Guru</th>

                        <th>No. KP</th>
                        
                        <th>Katalaluan</th>

                        <th>Jenis</th>

                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <form action="" method="post" class="tambah-guru">
                            <td class="input-container">
                                <input type="text" name="nama" id="nama" class="input-field" placeholder="cth. Samad fly" maxlength="255" required="required">
                            </td>

                            <td>
                                <input type="text" name="nokp" id="nokp" placeholder="696969696969" minlength="12" maxlength="12" required="required">
                            </td>

                            <td>
                                <input type="text" name="katalaluan" id="katalaluan" placeholder="samadfly123" maxlength="15" required="required">
                            </td>

                            <td>
                                <select name="jenis" id="jenis">
                                    <option value="guru">Guru</option>

                                    <option value="admin">Admin</option>
                                </select>
                            </td>

                            <td>
                                <button type="submit" name="submit" value="daftar_guru">Simpan</button>
                            </td>
                        </form>
                    </tr>

                    <?php

                    $guru_list = getGuruList(1000);

                    foreach( $guru_list as $guru )
                    {

                    ?>
                    <tr>
                        <td><?=$guru['g_nama']?></td>

                        <td><?=$guru['g_nokp']?></td>

                        <td><?=$guru['g_katalaluan']?></td>

                        <td><?=$guru['g_jenis']?></td>

                        <td>
                            <a href="kemaskini_guru.php?id_guru=<?=$guru['g_id']?>&redir=senarai_guru.php" class="kemaskini">Kemaskini</a>

                            <a href="padam.php?table=guru&col=g_id&val=<?=$guru['g_id']?>&redir=senarai_guru.php" class="padam">Padam</a>
                        </td>
                    </tr>
                    <?php

                    }

                    ?>
                </tbody>
            </table>


        </main>

        <?php require '../footer.php';?>

    </div>
</body>
</html>