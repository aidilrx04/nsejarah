<?php

#start session
session_start();

#conn and stuffs
require 'php/conn.php';

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
{

    if( isset( $_POST['submit'] ) && $_POST['submit'] == 'login' ) 
    {

        $jenis = $_POST['jenis'];        
        $nokp = $_POST['nokp'];
        $katalaluan = $_POST['katalaluan'];
        $status_login = false;

        if( $jenis == 'murid' )
        {

            if( $murid = loginMurid($nokp, $katalaluan) )
            {

                session_regenerate_id();
                $_SESSION['jenis'] = 'murid';
                $_SESSION['nokp'] = $murid['m_nokp'];
                $_SESSION['nama'] = $murid['m_nama'];
                $_SESSION['kelas'] = $murid['m_kelas'];
                $_SESSION['id'] = $murid['m_id'];
                $status_login = true;

            }
            else
            {

                $status_login = false;

            }

        }
        else if ( $jenis == 'guru' )
        {

            if( $guru = loginGuru( $nokp, $katalaluan ) )
            {

                session_regenerate_id();
                $_SESSION['jenis'] = $guru['g_jenis'];
                $_SESSION['nokp'] = $guru['g_nokp'];
                $_SESSION['nama'] = $guru['g_nama'];
                $_SESSION['id'] = $guru['g_id'];
                $status_login = true;

            }
            else
            {

                $status_login = false;

            }

        }

        if( $status_login )
        {

            $redirect = $jenis == 'murid' ? '/murid/pilih_latihan.php' : '/guru/';
            echo redirect( $redirect );

        }
        else
        {

            die( alert( 'No. KP atau Katalaluan salah!' ) . back() );

        }

    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>NSejarah</title>

    <link rel="stylesheet" href="/base.css">
</head>

<body>

    <div class="container">
        <div id="navigasi">            
            <?php require 'header.php'?>
        </div>
        <main>

            <?php
            # papar apabila pengguna belum lagi login
            
            if( !isset( $_SESSION['jenis'] ) )
            {

            ?>
            <div class="login-form">
                <h3>Login</h3>

                <form action="" method="post">

                    <label for="nokp" class="input-container">
                        <span>No. Kad Pengenalan</span>
                        <input type="text" name="nokp" id="nokp" class="input-field" placeholder="Cth. 111111111111" maxlength="12" minlength="12" required="required">
                    </label>

                    <label for="katalaluan" class="input-container">
                        <span>Katalaluan</span>
                        <input type="password" name="katalaluan" id="katalaluan" class="input-field" maxlength="15" required="required">
                    </label>

                    <div class="input-container">
                        <span>Jenis: </span>
                        
                        <label for="murid" class="input-containers no-margin">
                            <input type="radio" name="jenis" id="murid" value="murid" checked required>
                            <span>Murid</span>
                        </label>

                        <label for="guru" class="input-containers no-margin">
                            <input type="radio" name="jenis" id="guru"  value="guru" required>
                            <span>Guru</span>
                        </label>
                    </div>

                    <button type="submit" name="submit" value="login">Submit</button>

                </form>
            </div>
            <?php

            }
            ?>

            <div class="senarai-kuiz">
                <h3>Senarai Kuiz</h3>

                <table border="1">
                    <thead>
                        <tr>
                            <th>Nama</th>

                            <th>Guru</th>

                            <th>Jenis</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php

                        # [O] senarai kuiz
                        $kuiz_list = getKuizList();

                        # papar kuiz
                        foreach( $kuiz_list as $kuiz )
                        {

                            $guru = getGuru( $kuiz['kz_guru'] );
                            

                        ?>
                            
                            <tr>
                                <td><?=$kuiz['kz_nama']?></td>

                                <td><?=$guru['g_nama']?></td>

                                <td><?=$kuiz['kz_jenis']?></td>
                            </tr>

                        <?php

                        }

                        ?>
                    </tbody>
                </table>
            </div>

            
        </main>

        <?php require 'footer.php'?>

    </div>

</body>

</html>