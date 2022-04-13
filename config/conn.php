<?php
    require_once 'setting_rs.php';
    $S = $serverDB;
    $U = $userDB;
    $P = $passDB;
    $D = $namaDB;
    $conn = mysqli_connect($S, $U, $P, $D);
    if (!$conn) die ("<h2>Gagal tersambung dengan database!</h2>");
?>