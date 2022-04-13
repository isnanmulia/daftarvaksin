<?php
    require_once 'config/conn.php';
    require_once 'config/base_url.php';

    if (isset($_POST["form_submit"])) {
        $username = $_POST["inp_username"];
        $password = $_POST["inp_password"];
        $username = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $username);
        $username = preg_replace("/[?;'\"<>]/", "", $username);
        $username = trim(mysqli_real_escape_string($conn, $username));
        $password = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $password);
        $password = preg_replace("/[?;'\"<>]/", "", $password);
        $password = trim(mysqli_real_escape_string($conn, $password));
        $doLogin = mysqli_query($conn, "SELECT * from pengguna WHERE nama_pengguna = '" . $username . "'");
        $login = mysqli_fetch_assoc($doLogin);
        if (md5($password) == $login["password_pengguna"]) {
            session_start();
            $_SESSION["USER"] = array(
                "USERNAME" => $login["nama_pengguna"],
                "FULLNAME" => $login["nama_lengkap"],
            );
            $_SESSION["START"] = time();
            $_SESSION["LAST_ACTIVITY"] = time();
            header("Location: admin/index.php");
        } else {
            echo "<script>alert('Username/password salah'); history.back();</script>";
            exit();
        }
    }
?>