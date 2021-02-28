<?php
/**
 * Senarai murid
 */

require '../php/conn.php';

accessAdmin( 'Akses tanpa kebenaran!' );

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] = 'submit_murid' )
{

    $nama = $_POST['nama'];
    $nokp = $_POST['nokp'];
    $katalaluan = $_POST['katalaluan'];
    $kelas = $_POST['kelas'];
    $query = "INSERT INTO murid(m_nama,m_nokp,m_katalaluan,m_kelas) VALUE (?,?,?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ssss', $nama, $nokp, $katalaluan, $kelas );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt )
        {

            echo alert( 'Data berjaya dimuat naik!' ) . ( isset( $_GET['redir'] ) ? redirect( $_GET['redir'] ) : '' );

        }
        else
        {

            die( alert( 'Data gagal dimuat naik!' ) . back() );

        }

    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Senarai Murid</title>
</head>
<body>
    <main>
        <h2>Senarai murid</h2>

        <table border="100">
            <thead>
                <tr>
                    <th>Nama</th>

                    <th>No. Kad Pengenalan</th>

                    <th>Katalaluan</th>

                    <th>Kelas</th>

                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <form action="" method="post">
                        <td>
                            <input type="text" name="nama" id="nama" placeholder="cth. Abu flyy" maxlength="255" required="required">
                        </td>

                        <td>
                            <input type="text" name="nokp" id="nokp" placeholder="cth. 555555555555" minlength="12" maxlength="12" required>
                        </td>

                        <td>
                            <input type="text" name="katalaluan" id="katalaluan" placeholder="cth. abufly123" maxlength="15" required="required">
                        </td>

                        <td>
                            <select name="kelas" id="kelas">
                                <?php
                                $ting_list = getTingList(-1);

                                foreach( $ting_list as $ting )
                                {

                                    $kelas = getKelasById( $ting['kt_kelas'] );

                                ?>
                                <option value="<?=$ting['kt_id']?>"><?=$ting['kt_ting'] . ' ' . $kelas['k_nama']?></option>
                                <?php

                                }
                                ?>
                            </select>
                        </td>

                        <td>
                            <button type="submit" name="submit" value="submit_murid">Simpan</button>
                        </td>
                    </form>
                </tr>

                <?php
                $murid_list = getMuridList(-1);
                
                
                foreach( $murid_list as $murid )
                {
                    $ting = getTingById( $murid['m_kelas'] );
                    $kelas = getKelasById( $ting['kt_kelas'] );

                ?>
                <tr>
                    <td><?=$murid['m_nama']?></td>

                    <td><?=$murid['m_nokp']?></td>

                    <td><?=$murid['m_katalaluan']?></td>

                    <td><?=$ting['kt_ting']?> <?=$kelas['k_nama']?></td>

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
    </main>
</body>
</html>