<?php
/**
 * Upload data murid
 */

require '../php/conn.php';

accessAdmin( 'Akses tanpa kebenaran!' );

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'upload_murid' )
{

    $data_file = $_FILES['data'];

    if( $data_file['size'] > 0 && pathinfo( basename( $data_file['name'] ), PATHINFO_EXTENSION ) == 'csv' )
    {

        $bil_berjaya = 0;
        $bil_gagal = 0;
        $jumlah = 0;
        $baris = 1;
        $file = fopen( $data_file['tmp_name'], 'r' );

        while( ( $data = fgetcsv(  $file ) ) !== FALSE )
        {

            $nokp = $data[0];
            $nama = $data[1];
            $katalaluan = $data[2];
            $kelas = $data[3];

            if( $baris > 1 )
            {

                if( $berjaya = registerMurid( $nokp, $nama, $katalaluan, (int)$kelas ) )
                {
                    
                    $bil_berjaya++;

                }
                else $bil_gagal++;

                $jumlah++;

            }

            $baris++;

        }

        echo 'Berjaya: ' . $bil_berjaya;
        echo '<br>Gagal: ' . $bil_gagal;
        echo '<br>Jumlah: ' . $jumlah;

        fclose( $file );

    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Upload Data Murid</title>
</head>
<body>
    <main>
        <div id="upload-murid">
            <form action="" method="post" enctype="multipart/form-data">
                <label for="data" class="input-container">
                    <span>Data murid(.csv)</span>

                    <input type="file" name="data" id="data" accept=".csv" required>
                </label>

                <button type="submit" name="submit" value="upload_murid">Muat naik</button>
            </form>
        </div>
    </main>
</body>
</html>