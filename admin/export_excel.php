<?php
    require_once '../config/session_check.php';
    require_once '../config/conn.php';
    date_default_timezone_set("Asia/Jakarta");
    if (isset($_POST["form_submit"])) {
        $getEvent = mysqli_query($conn, "SELECT tanggal_mulai, tanggal_selesai, judul FROM event_vaksin WHERE id_event=" . $_POST["sel_event"]);
        $event = mysqli_fetch_assoc($getEvent);
        $file = "Daftar Peserta Vaksin";
        $title = "Daftar Peserta Vaksin: " . $event["judul"] . " (" . $event["tanggal_mulai"] . " s.d. " . $event["tanggal_selesai"] . ")";
        $subtitle = "Per " . date("Y-m-d H:i:s");
        $getRegistrants = mysqli_query($conn, "SELECT waktu_daftar, tanggal, sesi, nama, jk, nik, tanggal_lahir, kelompok_usia, alamat, no_hp, email, vaksin_ke, etiket_pl FROM peserta_vaksin P INNER JOIN detail_event_vaksin D ON P.id_detail_event=D.id_detail_event INNER JOIN event_vaksin E ON D.id_event=E.id_event WHERE E.id_event=" . $_POST["sel_event"] . " AND status_peserta='1' ORDER BY waktu_daftar");
        $lstRegistrants = "";
        while ($data = mysqli_fetch_assoc($getRegistrants)) {
            switch ($data["sesi"]) {
                case "9": $sesi = "Sesi 1 (9.00 - 10.00)"; break;
                case "10": $sesi = "Sesi 1 (10.00 - 11.00)"; break;
                case "11": $sesi = "Sesi 1 (11.00 - 12.00)"; break;
            }
            switch ($data["jk"]) {
                case "L": $jk = "Laki-laki"; break;
                case "P": $jk = "Perempuan"; break;
            }
            $lstRegistrants .= "<tr><td>" . $data["waktu_daftar"] . "</td><td>" . $data["tanggal"] . "</td><td>" . $sesi . "</td><td>" . $data["nama"] . "</td><td>" . $jk . "</td><td class='str'>" . $data["nik"] . "</td><td>" . $data["tanggal_lahir"] . "</td><td>" . ucfirst($data["kelompok_usia"]) . "</td><td>" . $data["alamat"] . "</td><td class='str'>" . $data["no_hp"] . "</td><td>" . $data["email"] . "</td><td>" . ucfirst($data["vaksin_ke"]) . "</td><td>" . $data["etiket_pl"] . "</td></tr>";
        }
        $lstRegistrants = "<table><tr><th>Waktu Daftar</th><th>Tanggal Vaksin</th><th>Sesi</th><th>Nama Peserta</th><th>Jenis Kelamin</th><th>No. KTP</th><th>Tanggal Lahir</th><th>Kelompok Usia</th><th>Alamat</th><th>No. HP</th><th>Email</th><th>Vaksin Ke</th><th>Etiket PeduliLindungi</th></tr>" . $lstRegistrants . "</table>";
        $table = $lstRegistrants;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ekspor ke Excel</title>
    </head>
    <script>
        setTimeout(function() { window.close() }, 100);
    </script>
    <body>
        <style type="text/css">
            body { font-family: sans-serif; }
            table { margin: 20px auto; border-collapse: collapse; }
            table th, table td { border: 1px solid black; padding: 3px 8px; }
            .str{ mso-number-format:\@; }
        </style>
        <?php
            header("Content-type: application/vnd-ms-excel");
            header("Content-disposition: attachment; filename=" . (strlen($file) ? str_replace(array(" ", ","), "", $file) : "output") . ".xls");
        ?>
        <h3><?php echo $title . (strlen($subtitle) ? "<br>" . $subtitle : "") ?></h3>
        <?php echo $table ?>
        <h6><?php echo "Tanggal Ekspor: " . date("Y-m-d H:i:s") ?></h6>
    </body>
</html>