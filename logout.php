<base href="./">

<?php
/**
 * Log keluar
 */
session_start();

session_unset();

session_destroy();

# redirect
header('Location: .');
