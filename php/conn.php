<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'nsejarah';

$conn = mysqli_connect( $dbhost, $dbuser, $dbpass, $dbname );

if( mysqli_connect_errno() ) {
    die( 'Sambungan gagal: ' . mysqli_connect_errno() );
} else {
   # echo 'Sambungan berjaya';
}

function login( $nokp, $password )
{

    global $conn;
    $table = 'murid';
    $col_1 = 'm_nokp';
    $col_2 = 'm_katalaluan';

    $query = "SELECT * FROM {$table} WHERE {$col_1} = ?";
    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $nokp );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 ) 
        {

            $user = $res->fetch_assoc();

            if( $user[$col_2] == $password )
            {

                return $user;

            }
            else
            {

                return;

            }

        } 
        else 
        {

            return;

        }

    }

}

function alert( $msg ) 
{

    return "<script>alert('{$msg}')</script>";

}