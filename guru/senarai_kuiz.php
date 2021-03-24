<?php

/**
 * Papar senarai kuiz
 */

require '../php/conn.php';

accessGuru( 'Akses tanpa kebenaran!' );

$kuiz_list = isAdmin() ? getKuizList( null, 10000 ) : getKuizByGuru( $_SESSION['id'] );

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Senarai Kuiz</title>

    <link rel="stylesheet" href="/base.css">
</head>
<body>
    <div class="container">
        <div id="navigasi">
            <?php require 'header_guru.php';?>
        </div>

        <main>

            <h2>Senarai Kuiz</h2>

            <a href="cipta_kuiz.php">Cipta kuiz baharu &plus;</a>

            <table id="senarai-kuiz" border="1">
                <thead>
                    <tr>
                        <th>Nama Kuiz</th>

                        <th>Jenis</th>

                        <?=isAdmin() ? "<th>Guru</th>" : ""?>

                        <th>Tarikh</th>

                        <th>Masa</th>

                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    
                    foreach( $kuiz_list as $kuiz )
                    {

                    ?>
                    <tr>
                        <td><?=$kuiz['kz_nama']?></td>

                        <td><?=$kuiz['kz_jenis']?></td>

                        <?php
                        if( isAdmin() )
                        {
                            $guru = getGuru( $kuiz['kz_guru'] );
                            ?>
                            <td><?=$guru['g_nama']?></td>
                            <?php
                        }
                        ?>

                        <td><?=$kuiz['kz_tarikh']?></td>

                        <td><?=$kuiz['kz_masa']?></td>

                        <td>
                            <a href="kemaskini_kuiz.php?id_kuiz=<?=$kuiz['kz_id']?>" class="kemaskini">Kemaskini</a>|
                            <a href="padam.php?table=kuiz&col=kz_id&val=<?=$kuiz['kz_id']?>" class="padam">Padam</a>
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