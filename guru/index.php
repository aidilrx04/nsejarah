<?php
/**
 * GURU
 */

require '../php/conn.php';

accessGuru( 'Akses tanpa kebenaran!' );



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | NSejarah</title>
</head>
<body>

    <main>
        <div id="maklumat-guru">
            <?php

            $guru = getGuru( $_SESSION['id'] );
            $kelas = getKelasByGuru( $guru['g_id'] );
            $kuiz = getKuizList( $guru['g_id'] );
            $kelas_data = [];
            
            foreach( $kelas as $k )
            {

                $data = getKelasById( $k['kt_kelas'] );
                array_push( $kelas_data, $data );

            }

            ?>

            <div id="guru">
                <h3>Maklumat Guru</h3>

                <h4>Nama: <?=$guru['g_nama']?></h4>

                <h4>No. Kad Pengenalan: <?=$guru['g_nokp']?></h4>

                <h4>Status: <?=$guru['g_jenis']?></h4>

                <h4>
                Kelas: 
                <?php
                foreach( $kelas_data as $k )
                {

                    echo $k['k_nama'];

                }
                ?>
                </h4>
            </div>

            <div id="kelas">
                <h3>Kelas Guru</h3>

                <table>
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>

                            <th>Jumlah Murid</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach( $kelas_data as $k )
                        {

                            $jumlah = getKelasJumlah( $k['k_id'], 1 );
                            
                        ?>
                        <tr>
                            <td><?=$k['k_nama']?></td>

                            <td><?=$jumlah?></td>
                        </tr>
                        <?php

                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="kuiz">
                <h3>Senarai Kuiz</h3>

                <table>
                    <thead>
                        <tr>
                            <th>Nama Kuiz</th>
                            
                            <th>Jenis</th>

                            <th>Masa</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                    foreach( $kuiz as $kz )
                    {

                        $masa  = $kz['kz_masa'] ? $kz['kz_masa'] : 'Tiada';

                    ?>
                    <tr>
                        <td><?=$kz['kz_nama']?></td>

                        <td><?=$kz['kz_jenis']?></td>

                        <td><?=$masa?></td>
                    </tr>
                    <?php

                    }
                    ?>
                    </tbody>
                </table>
            </div>        
        </div>
    </main>
    
</body>
</html>