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

    <link rel="stylesheet" href="/base.css">
</head>
<body>
    <div class="container">
        <div id="navigasi"><?php require 'header_guru.php';?></div>
        <main>
            <div id="upload-murid">
                <h2>[&bigoplus;] Muatnaik Data Murid</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <label for="data" class="input-container">
                        <span>Data murid(.csv)</span>

                        <input type="file" name="data" id="data" accept=".csv" required>
                    </label>

                    <button type="submit" name="submit" value="upload_murid">Muat naik</button>
                </form>

                <p style="white-space: pre-wrap;">
Untuk memuat naik data murid, pastikan anda
menggunakan template yang telah disediakan.
Muat turun <a href="/guru/data_murid.csv">di sini</a>.
</p>
            </div>
        </main>

        <?php require '../footer.php';?>
    </div>
    

    <script>
        const uploadContainer = document.querySelector( '#upload-murid' ),
              inputFile = uploadContainer.querySelector( '#data' );

        uploadContainer.addEventListener( 'dragenter', dragenter );
        uploadContainer.addEventListener( 'dragover', dragover );
        uploadContainer.addEventListener( 'drop', drop );

        function dragenter( e )
        {

            e.stopPropagation();
            e.preventDefault();

        }
        
        function dragover( e )
        {

            e.stopPropagation();
            e.preventDefault();

        }

        function drop( e )
        {
            
            e.stopPropagation();
            e.preventDefault();


            const dt = e.dataTransfer;
            const file = dt.files;

            inputFile.files = file;
            
        }
    </script>
</body>
</html>