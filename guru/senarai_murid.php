<?php
/**
 * Senarai murid
 */

require '../php/conn.php';

accessAdmin( 'Akses tanpa kebenaran!' );

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

        <table>
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
                <?php
                $murid_list = getMuridList(-1);
                var_dump($murid_list);
                
                foreach( $murid_list as $murid )
                {

                    $kelas = getKelasById( getKelasByTingId( $murid['m_kelas'] )['kt_kelas'] );

                ?>
                <tr>
                    <td><?=$murid['m_nama']?></td>
                </tr>
                <?php
                
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>