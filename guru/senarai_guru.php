<?php

/**
 * Paparan senarai guru
 */

require '../php/conn.php';

# hanya benarkan admin sahaja mengakses laman ini
accessAdmin('Akses tanpa kebenaran!');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Senarai Guru</title>
</head>
<body>
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
                        <a href="kemaskini_guru.php?id_guru=<?=$guru['g_id']?>&redir=senarai_guru.php">Kemaskini</a>

                        <a href="padam.php?table=guru&col=g_id&val=<?=$guru['g_id']?>&redir=senarai_guru.php">Padam</a>
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