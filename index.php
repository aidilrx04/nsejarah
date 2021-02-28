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

        if( $jenis == 'murid' )
        {

            if( $murid = loginMurid($nokp, $katalaluan) )
            {

                session_regenerate_id();
                $_SESSION['jenis'] = 'murid';
                $_SESSION['nokp'] = $murid['m_nokp'];
                $_SESSION['nama'] = $murid['m_nama'];
                $_SESSION['ting'] = $murid['m_ting'];
                $_SESSION['kelas'] = $murid['m_kelas'];

            }
            else
            {

                die( alert( 'No. KP atau Katalaluan salah!' ) . back() );

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

            }
            else
            {

                die( alert( 'No. KP atau Katalaluan salah!' ) . back() );

            }

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
</head>

<body>

    <main>

        <?php
        # papar apabila pengguna belum lagi login
        
        if( !isset( $_SESSION['jenis'] ) )
        {

        ?>
        <div class="login-form">
            <form action="" method="post">

                <label for="nokp" class="input-container">
                    <span>No. Kad Pengenalan</span>
                    <input type="text" name="nokp" id="nokp" class="input-field" placeholder="Cth. 111111111111" maxlength="12" minlength="12" required="required">
                </label>

                <label for="katalaluan" class="input-container">
                    <span>Katalaluan</span>
                    <input type="password" name="katalaluan" id="katalaluan" class="input-field" maxlength="15" required="required">
                </label>

                <div>
                    <span>Jenis</span>
                    <label for="murid" class="input-container">
                       <input type="radio" name="jenis" id="murid" class="input-field" value="murid" checked required>
                       <span>Murid</span>
                    </label>

                    <label for="guru">
                        <input type="radio" name="jenis" id="guru" class="input-field"  value="guru" required>
                        <span>Guru</span>
                    </label>
                </div>

                <button type="submit" name="submit" value="login">Submit</button>

            </form>
        </div>
        <?php

        }
        else
        {

        ?>
        <a href="/logout.php" class="logout-btn">Log Keluar</a>
        <?php

        }
        ?>

        <div class="senarai-kuiz">
            <table>
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

</body>

</html>