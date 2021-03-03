<?php
/**
 * Cipta Kuiz
 */

require '../php/conn.php';

accessGuru( 'Akses tanpa kebenaran!' );

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'tambah-kuiz')
{

    $nama = $_POST['nama'];
    $guru = $_SESSION['id'];
    $tarikh = $_POST['tarikh'];
    $jenis = $_POST['jenis'];
    $masa = $_POST['masa'];

    if( $id_kuiz = registerKuiz( $nama, $guru, $tarikh, $jenis, $masa ) )
    // if( true )
    {
        
        $soalan_list = $_POST['s'];
        $sBerjaya = 0;
        $sGagal = 0;
        $sJumlah = count( $soalan_list );

        foreach( $soalan_list as $id=>$soalan )
        {

            $sTeks = $soalan[0];
            var_dump( $id_kuiz );

            if( $id_soalan = registerSoalan( $id_kuiz, $sTeks, $_FILES[$id] ) )
            {

                $jawapan_list = $soalan['j'];
                $jBetul = $soalan[1];
                $sBerjaya++;

                foreach( $jawapan_list as $count=>$jawapan )
                {

                    $jTeks = $jawapan[0];

                    if( $id_jawapan = registerJawapan( $id_soalan, $jTeks ) )
                    {

                        if( $count == $jBetul )
                        {

                            registerSoalanJawapan( $id_soalan, $id_jawapan );

                        }

                    }

                }

            }
            else 
            {

                $sGagal++;

            }

        }

        echo alert( "Berjaya: {$sBerjaya}, Gagal: {$sGagal}, Jumlah: {$sJumlah}" );

    }
    else die( alert( 'Kuiz gagal dimuatnaik!' ) );

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cipta Kuiz</title>
</head>
<body>
    <main>
        <div id="cipta-kuiz">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="maklumat-kuiz">
                    <h3>Maklumat kuiz</h3>

                    <label for="nama" class="input-container">
                        <span>Nama Kuiz</span>

                        <input type="text" name="nama" id="nama" class="input-file" maxlength="255" required="required">
                    </label>

                    <div>
                        <label for="tarikh" class="input-container">
                            <span>Tarikh</span>

                            <input type="date" name="tarikh" id="tarikh" class="input-field" value="<?=date('Y-m-d')?>">
                        </label>

                        <label for="jenis" class="input-container">
                            <span>Jenis</span>

                            <select name="jenis" id="jenis" class="input-field">
                                <option value="latihan">Latihan</option>

                                <option value="kuiz">Kuiz</option>
                            </select>
                        </label>

                        <label for="masa" class="input-container">
                            <span>Masa(minit)</span>

                            <input type="number" name="masa" id="masa" class="input-field" disabled>
                        </label>
                    </div>
                </div>

                <hr>

                <div id="soalan">
                    <h3>Soalan</h3>

                    <div id="soalan-list"></div>

                    <button id="tambah-soalan" class="btn btn-success" type="button">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Soalan</span>
                    </button>
                </div>

                <button type="submit" id="submit" name="submit" value="tambah-kuiz">Simpan</button>
            </form>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

    <script>
        /** CUSTOM SCRIPT */
        $( function()
        {

            let sContainer, sList, submitBtn, tambahBtn, kJenis, kMasaInput;
            sContainer = $( '#soalan' );
            sList= sContainer.find( '#soalan-list' );
            submitBtn = $( '#submit' );
            tambahBtn = $( '#tambah-soalan' );
            kJenis = $( '#jenis' );
            kMasaInput = $( '#masa' );

            tambahBtn.click( 'click', function () { tambahSoalan( sList ) } );
            kJenis.change( function( e ) 
            {

                const target = e.target;
                let valToDisable = 'latihan';

                if( target.value == valToDisable )
                {

                    kMasaInput.attr( { disabled: true } );

                }
                else
                {

                    kMasaInput.attr( { disabled: false } );

                }

            } );

        } );

        function tambahSoalan( sList )
        {

            let sContainer, sInput, sImage, sjContainer;
            const sId = uniqid();

            sContainer = $( document.createElement( 'div' ) )
                         .attr( {
                             class: 'soalan'
                         } )
                         .appendTo( sList );

            sInput = $( document.createElement( 'input' ) )
                     .attr( {
                        class: 'input-field',
                        placeholder: 'Sila masukkan teks soalan',
                        name: 's[' + sId + '][]',
                        required: true
                     } )
                     .appendTo( sContainer );
            
            sImage = $( document.createElement( 'input' ) )
                     .attr( {
                        class: 'input-field',
                        type: 'file',
                        name: sId
                     } )
                     .appendTo( sContainer );

            sjContainer = $( document.createElement( 'div' ) )
                          .attr( {
                              class: 'jawapan-container'
                          } )
                          .appendTo( sContainer );

            const jawapanCount = 4;
            const jInput = [];

            for( let i = 0; i < jawapanCount; i++ )
            {

                const jId = uniqid();
                let jInput = $( document.createElement( 'input' ) )
                             .attr( {
                                 class: 'jawapan-input',
                                 placeholder: 'Sila masukkan jawapan',
                                 name: 's[' + sId + '][j][][0]',
                                 required: true
                             } )
                             .appendTo( sjContainer );

                let jBetul = $( document.createElement( 'input' ) )
                             .attr( {
                                 type: 'radio',
                                 value: i,
                                 name: 's[' + sId + '][]',
                                 required: true
                             } )
                             .appendTo( sjContainer );
            }

            return;

        }
        
        function uniqid() {
            var keys = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')
            var id = false;

            do {

                id = '';

                for (var i = 0; i < 5; i++) {

                    id += keys[Math.floor(Math.random() * keys.length)];

                }

                var _dummy = document.querySelector('#' + id);

                if (_dummy) {

                    id = false;

                }

            } while (!id);

            return id;
        }
    </script>
</body>
</html>