<?php
/**
 * Senarai Kelas
 */

require '../php/conn.php';

accessAdmin( 'Akses tanpa kebenaran!' );

if( $_SERVER['REQUEST_METHOD'] == "POST" )
{

    $jenis_submit = $_POST['submit'];

    if( $jenis_submit == 'tambah_ting' )
    {

        $ting = $_POST['ting'];
        $kelas = $_POST['kelas'];
        $guru = $_POST['guru'];

        if( registerTing( $ting, $kelas, $guru ) )
        {

            echo alert( 'Data berjaya dimuatnaik!' );

        }
        else die( alert( 'Data gagal dimuatnaik!' ) . back() ); 

    }
    else if( $jenis_submit == 'tambah_kelas' )
    {

        $nama_kelas = $_POST['nama'];

        if( registerKelas( $nama_kelas ) )
        {

            echo alert( 'Data berjaya dimuatnaik!' );

        }
        else die( alert( 'Data gagal dimuatnaik!' ) . back() );

    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Senarai Tingkatan</title>
</head>
<body>
    <main>
        <?php require 'header_guru.php';?>    


        <div id="senarai-tingkatan">
            <h2>Senarai Tingkatan</h2>

            <table border="1">
                <thead>
                    <tr>
                        <th>Tingkatan</th>

                        <th>Nama</th>

                        <th>Guru</th>

                        <td>Aksi</td>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <form action="" method="post">
                            <td>
                                <input type="number" name="ting" id="ting" min="1" max="5" placeholder="cth. 1" required="required">
                            </td>

                            <td>
                                <select name="kelas" id="kelas" required="required">
                                    <?php
                                    $kelas_list = getKelasList(-1);

                                    foreach( $kelas_list as $kelas )
                                    {

                                    ?>
                                    <option value="<?=$kelas['k_id']?>"><?=$kelas['k_nama']?></option>
                                    <?php

                                    }
                                    ?>
                                </select>
                            </td>

                            <td>
                                <select name="guru" id="guru">
                                    <?php
                                    $guru_list = getGuruList();

                                    foreach( $guru_list as $guru )
                                    {

                                    ?>
                                    <option value="<?=$guru['g_id']?>"><?=$guru['g_nama']?></option>
                                    <?php

                                    }
                                    ?>
                                </select>
                            </td>

                            <td>
                                    <button type="submit" name="submit" value="tambah_ting">Simpan</button>
                            </td>
                        </form>
                    </tr>

                    <?php
                    
                    $ting_list = getTingList(-1);

                    foreach( $ting_list as $ting )
                    {

                        $kelas = getKelasById( $ting['kt_kelas'] );
                        $guru = getGuru( $ting['kt_guru'] );

                    ?>

                    <tr>
                        <td><?=$ting['kt_ting']?></td>

                        <td><?=$kelas['k_nama']?></td>

                        <td><?=$guru['g_nama']?></td>

                        <td>
                            <a href="#kemaskini">Kemaskini</a>

                            <a href="#padam">Padam</a>
                        </td>
                    </tr>

                    <?php

                    }

                    ?>
                </tbody>
            </table>
        </div>

        <div id="senarai-kelas">
            <h2>Senarai Kelas</h2>

            <table border="1">
                <thead>
                    <tr>
                        <th>Nama</th>

                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <form action="" method="post">
                            <td>
                                <input type="text" name="nama" id="nama_kelas" placeholder="cth. Amanah" required="required">
                            </td>

                            <td>
                                <button type="submit" name="submit" value="tambah_kelas">Simpan</button>
                            </td>
                        </form>
                    </tr>

                    <?php
                    
                    $kelas_list = getKelasList(-1);

                    foreach( $kelas_list as $kelas )
                    {

                    ?>
                    <tr>
                        <td><?=$kelas['k_nama']?></td>

                        <td>
                            <a href="#kemaskini">Kemaskini</a>
                            <a href="#padam">Padam</a>
                        </td>
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