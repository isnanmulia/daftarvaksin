<?php
    $days = ["", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu", "Minggu"];
    $months = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    require_once 'config/setting_rs.php';
    require_once 'config/conn.php';
    if (isset($_POST["form_submit"])) {
        // sanitize input text
        $namapx = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $_POST["inp_namapx"]);
        $namapx = preg_replace("/[?;'\"<>]/", "", $namapx);
        $namapx = trim(mysqli_real_escape_string($conn, $namapx));
        $nik = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $_POST["inp_nik"]);
        $nik = preg_replace("/[?;'\"<>]/", "", $nik);
        $nik = trim(mysqli_real_escape_string($conn, $nik));
        $tgl_lahir = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $_POST["inp_tgllahir"]);
        $tgl_lahir = preg_replace("/[?;'\"<>]/", "", $tgl_lahir);
        $tgl_lahir = trim(mysqli_real_escape_string($conn, $tgl_lahir));
        $alamat = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $_POST["inp_alamat"]);
        $alamat = preg_replace("/[?;'\"<>]/", "", $alamat);
        $alamat = trim(mysqli_real_escape_string($conn, $alamat));
        $nohp = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $_POST["inp_nohp"]);
        $nohp = preg_replace("/[?;'\"<>]/", "", $nohp);
        $nohp = trim(mysqli_real_escape_string($conn, $nohp));
        $email = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $_POST["inp_email"]);
        $email = preg_replace("/[?;'\"<>]/", "", $email);
        $email = trim(mysqli_real_escape_string($conn, $email));
        $etiket = preg_replace("/\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b/i", "", $_POST["inp_etiket"]);
        $etiket = preg_replace("/[?;'\"<>]/", "", $etiket);
        $etiket = trim(mysqli_real_escape_string($conn, $etiket));
        // check availability
        $checkEvent = mysqli_query($conn, "SELECT status_event, kuota, (SELECT COUNT(*) FROM peserta_vaksin P WHERE P.id_detail_event=DE.id_detail_event AND status_peserta='1') AS terpakai FROM detail_event_vaksin DE INNER JOIN event_vaksin E ON DE.id_event=E.id_event WHERE id_detail_event=" . $_POST["schedule_id"]);
        $event = mysqli_fetch_assoc($checkEvent);
        if ($event["status_event"] != "0" && $event["kuota"] > $event["terpakai"]) {
            // save the data
            $qInsertPeserta = "INSERT INTO peserta_vaksin (waktu_daftar, id_detail_event, nama, jk, nik, tanggal_lahir, kelompok_usia, alamat, no_hp, email, vaksin_ke, vaksin_primer, etiket_pl, status_peserta) VALUES (Now(), " . $_POST["schedule_id"] . ", '" . $namapx . "', '" . $_POST["inp_jk"] . "', '" . $nik . "', '" . $tgl_lahir . "', '" . $_POST["inp_klpusia"] . "', '" . $alamat . "', '" . $nohp . "', '" . $email . "', '" . $_POST["inp_vaksinke"] . "', " . $_POST["inp_jenisvaksin"] . ", '" . $etiket . "', '1')";
            $insertPeserta = mysqli_query($conn, $qInsertPeserta);
            if ($insertPeserta) $msg = "Anda sudah terdaftar.<br>Mohon agar dapat hadir untuk vaksinasi tepat pada waktunya, sesuai dengan jadwal & sesi yang telah dipilih.";
                else $msg = "Mohon maaf, terdapat kesalahan pada sistem.<br>Mohon agar dapat mengulangi proses pemasukan data.";
        } else {
            $msg = "Mohon maaf, kami tidak dapat mendaftarkan Anda.<br>Kuota pendaftaran vaksinasi sudah habis, atau pendaftaran vaksinasi sudah ditutup.";
            $insertPeserta = false;
        } 
?>
<html>
    <head>
        <title>Pendaftaran Vaksin Covid-19 - <?php echo $namaPanjangRS ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="p-2">
        <div class="container justify-content-center m-auto p-3 rounded" style="background-color: #FAC898; max-width: 700px">
            <table class="table text-center">
                <tr>
                    <td><img src="<?php echo $logoRS ?>" style="width: 50px"></td>
                    <td>
                        <h2 class="d-inline"><?php echo $namaPanjangRS ?></h2>
                        <h6><?php echo $alamatRS ?></h6>
                    </td>
                </tr>
            </table>
            <?php
                $getEvent = mysqli_query($conn, "SELECT judul, subjudul FROM event_vaksin WHERE id_event=(SELECT id_event FROM detail_event_vaksin WHERE id_detail_event=" . $_POST["schedule_id"] . ")");
                $event = mysqli_fetch_assoc($getEvent);
            ?>
            <h3 class="text-center"><?php echo $event["judul"] ?></h3>
            <h5 class="text-center"><?php echo (strlen($event["subjudul"]) ? $event["subjudul"] : "") ?></h5>
            <div class="p-0">
                <div class='mb-3 bg-light rounded p-4'>
                    <p>
                        <?php
                            echo $msg;
                            if ($insertPeserta) {
                                $getJadwal = mysqli_query($conn, "SELECT sesi, tanggal FROM detail_event_vaksin WHERE id_detail_event=" . $_POST["schedule_id"]);
                                $j = mysqli_fetch_assoc($getJadwal);
                                $date = date_create($j["tanggal"]);
                                $hr = date_format($date, "N");
                                $tg = date_format($date, "j");
                                $bl = date_format($date, "n");
                                $th = date_format($date, "Y");
                                switch ($j["sesi"]) {
                                    case "9": $sesi = "Sesi 1 (9.00 - 10.00)"; break;
                                    case "10": $sesi = "Sesi 2 (10.00 - 11.00)"; break;
                                    case "11": $sesi = "Sesi 3 (11.00 - 12.00)"; break;
                                    case "N": $sesi = "9.00 - 12.00"; break;
                                }
                                $sesivaksin = $days[$hr] . ", " . $tg . " " . $months[$bl] . " " . $th . ", " . $sesi;
                                echo "<br><br><table>
                                <tr><td>Nama</td><td>:</td><td>" . $namapx . "</td></tr>
                                <tr><td>No. KTP</td><td>:</td><td>" . substr($nik, 0, 4) . "****" . substr($nik, -2, 2) . "</td></tr>
                                <tr><td>Sesi Vaksin</td><td>:</td><td>" . $sesivaksin . "</td></tr>
                                <tr><td>Vaksin ke</td><td>:</td><td>" . ucfirst($_POST["inp_vaksinke"]) . "</td></tr>
                                <tr><td>Nomor Etiket</td><td>:</td><td>" . (strlen($etiket) ? substr($etiket, 0, 3) . "****" : "-") . "</td></tr>
                                </table>";
                            }
                        ?>
                        <br><br>
                        Informasi lebih lanjut hubungi:<br>021-7778899 ext. 200<br>WhatsApp: 082 123 456 809 (marketing)
                    </p>
                    <div class='mt-5'><a class='link-primary' onClick='newResponse()' role='button'>Kirim tanggapan lain</a></div>
                </div>
            </div>
            <br><br><div class="text-end"><small class="text-muted fst-italic">Developed by <a href="https://github.com/isnanmulia" target="_blank">isnanmulia</a></small></div>
        </div>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="script.js"></script>
</html>
<?php } ?>