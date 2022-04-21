<?php
    require_once 'config/setting_rs.php';
    require_once 'config/conn.php';
    $days = ["", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu", "Minggu"];
    $months = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    date_default_timezone_set("Asia/Jakarta");
    $now = date("Y-m-d H:i:s");
?>

<!doctype html>
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
                $getEvent = mysqli_query($conn, "SELECT id_event, judul, subjudul, syarat_ketentuan, status_publish, (SELECT SUM(kuota) FROM detail_event_vaksin DE WHERE DE.id_event=E.id_event) AS kuota, (SELECT COUNT(*) FROM peserta_vaksin P INNER JOIN detail_event_vaksin DE ON P.id_detail_event=DE.id_detail_event WHERE DE.id_event=E.id_event AND status_peserta='1') AS terdaftar FROM event_vaksin E WHERE status_event='1' AND tanggal_mulai>=Curdate() AND tanggal_selesai>=Curdate() ORDER BY tanggal_mulai ASC LIMIT 1");
                $event = mysqli_fetch_assoc($getEvent);
                if ($event && ($event["status_publish"] == "1" || ($event["status_publish"] == "0" && isset($_GET["u"])))) { ?>
                    <h3 class="text-center"><?php echo $event["judul"] ?></h3>
                    <h5 class="text-center"><?php echo (strlen($event["subjudul"]) ? $event["subjudul"] : "") ?></h5>
                    <div class="p-0">
                        <?php
                            if (isset($_GET["data"])) {
                                $data = base64_decode($_GET["data"]);
                                parse_str($data, $param);
                                switch ($param["loc"]) {
                                    case "step1":
                                        echo "<div class='mb-3 bg-light rounded p-4'>Pilih Jadwal Vaksinasi"; 
                                        $getTanggal = mysqli_query($conn, "SELECT DISTINCT tanggal FROM detail_event_vaksin WHERE id_event=" . $event["id_event"]);
                                        echo "<table class='table table-hover'>";
                                        $idx = 0;
                                        while ($t = mysqli_fetch_assoc($getTanggal)) {
                                            $date = date_create($t["tanggal"]);
                                            $hr = date_format($date, "N");
                                            $tg = date_format($date, "j");
                                            $bl = date_format($date, "n");
                                            $th = date_format($date, "Y");
                                            if ($idx > 0) echo "<tr><td></td></tr>";
                                            echo "<tr><td>" . $days[$hr] . ", " . $tg . " " . $months[$bl] . " " . $th . "</td></tr>";
                                            $getJadwal = mysqli_query($conn, "SELECT id_detail_event, sesi, kuota, status_detail_event, (SELECT COUNT(*) FROM peserta_vaksin P WHERE P.id_detail_event=J.id_detail_event AND status_peserta='1') AS terpakai FROM detail_event_vaksin J WHERE id_event=" . $event["id_event"] . " AND tanggal='" . $t["tanggal"] . "'");
                                            while ($j = mysqli_fetch_assoc($getJadwal)) {
                                                $sisa = $j["kuota"]-$j["terpakai"];
                                                $n_sesi = ($j["sesi"] == "N" ? "11" : $j["sesi"]);
                                                $not_available = $sisa<=0 || $j["status_detail_event"] == 0 || $now>=$t["tanggal"] . " " . str_pad($n_sesi, 2, "0", STR_PAD_LEFT) . ":00:00";
                                                echo "<tr><td><label class='form-check-label'><input type='radio' class='form-check-input' name='schedule' value='" . $j["id_detail_event"] . "' " . ($not_available ? "disabled" : "") . " onChange='activateNextBtn()'>&nbsp;&nbsp;";
                                                switch ($j["sesi"]) {
                                                    case "9": $sesi = "Sesi 1 (9.00 - 10.00)"; break;
                                                    case "10": $sesi = "Sesi 2 (10.00 - 11.00)"; break;
                                                    case "11": $sesi = "Sesi 3 (11.00 - 12.00)"; break;
                                                    case "N": $sesi = "9.00 - 12.00"; break;
                                                }
                                                echo $sesi . " | Kuota: " . $j["kuota"] . " orang | Sisa kuota: " . ($sisa>0 ? $sisa : "0") . " orang " . ($not_available ? "<strong>[Ditutup]</strong>" : "") . "</label>";
                                                echo "</td></tr>";
                                            }
                                            $idx++;
                                        }
                                        echo "</table></div>";
                                        echo "<div class='mt-5'><button type='button' id='btn_back' class='btn btn-secondary' onClick='history.back()'>Kembali</button> <button type='button' id='btn_next' class='btn btn-primary' onClick='next(&quot;step1&quot;)' disabled>Berikutnya</button><br><br><a class='link-primary' onClick='clearForm()' role='button'>Bersihkan form</a></div>";
                                        break;
                                    case "step2":
                                        $getJadwal = mysqli_query($conn, "SELECT sesi, tanggal, vaksin_1, vaksin_2, vaksin_booster FROM detail_event_vaksin WHERE id_detail_event=" . $param["schedule"]);
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
                                        $getVaksin = mysqli_query($conn, "SELECT id_vaksin, nama_vaksin FROM jenis_vaksin");
                                        $lstVaksin = "";
                                        while ($v = mysqli_fetch_assoc($getVaksin)) {
                                            $lstVaksin .= (strlen($lstVaksin) ? "<br>" : "") . "<label class='form-check-label'><input type='radio' class='form-check-input' name='inp_jenisvaksin' value='" . $v["id_vaksin"] . "' onChange='activateSubmitBtn()'>&nbsp;&nbsp;" . $v["nama_vaksin"] . "</label>";
                                        }
                                        echo "<form method='POST' action='prosesdaftar.php'>";
                                        echo "<div class='mb-3 bg-light rounded p-4'>Jadwal Vaksinasi Dipilih: <strong>" . $days[$hr] . ", " . $tg . " " . $months[$bl] . " " . $th . ", " . $sesi . "</strong><input type='hidden' id='schedule_id' name='schedule_id' value='" . $param["schedule"] . "'></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_namapx' class='form-label'>Nama Lengkap <span class='text-danger'>*</span></label><input type='text' id='inp_namapx' name='inp_namapx' class='form-control' placeholder='Jawaban Anda' autocomplete='off' onKeyUp='activateSubmitBtn()'></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_jk' class='form-label'>Jenis Kelamin <span class='text-danger'>*</span></label><br><label class='form-check-label'><input type='radio' class='form-check-input' name='inp_jk' value='L' onChange='activateSubmitBtn()'>&nbsp;&nbsp;Laki-laki</label><br><label class='form-check-label'><input type='radio' class='form-check-input' name='inp_jk' value='P' onChange='activateSubmitBtn()'>&nbsp;&nbsp;Perempuan</label></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_nik' class='form-label'>No. KTP <span class='text-danger'>*</span></label><input type='text' id='inp_nik' name='inp_nik' class='form-control' placeholder='Jawaban Anda' autocomplete='off' onKeyUp='activateSubmitBtn()'></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_tgllahir' class='form-label'>Tanggal Lahir <span class='text-danger'>*</span></label><input type='text' id='inp_tgllahir' name='inp_tgllahir' class='form-control tanggal-lahir' placeholder='Jawaban Anda' autocomplete='off' onChange='activateSubmitBtn()'></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_klpusia' class='form-label'>Kelompok Usia <span class='text-danger'>*</span></label><br><label class='form-check-label'><input type='radio' class='form-check-input' name='inp_klpusia' value='umum' onChange='activateSubmitBtn()'>&nbsp;&nbsp;18 - 49 tahun</label><br><label class='form-check-label'><input type='radio' class='form-check-input' name='inp_klpusia' value='pralansia' onChange='activateSubmitBtn()'>&nbsp;&nbsp;50 - 59 tahun</label><br><label class='form-check-label'><input type='radio' class='form-check-input' name='inp_klpusia' value='lansia' onChange='activateSubmitBtn()'>&nbsp;&nbsp;60 tahun ke atas</label></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_alamat' class='form-label'>Alamat <span class='text-danger'>*</span></label><input type='text' id='inp_alamat' name='inp_alamat' class='form-control' placeholder='Jawaban Anda' autocomplete='off' onKeyUp='activateSubmitBtn()'></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_nohp' class='form-label'>No. Handphone <span class='text-danger'>*</span></label><input type='text' id='inp_nohp' name='inp_nohp' class='form-control' placeholder='Jawaban Anda' autocomplete='off' onKeyUp='activateSubmitBtn()'></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_email' class='form-label'>Email <span class='text-danger'>*</span></label><input type='text' id='inp_email' name='inp_email' class='form-control' placeholder='Jawaban Anda' autocomplete='off' onKeyUp='activateSubmitBtn()'></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_vaksinke' class='form-label'>Vaksin ke <span class='text-danger'>*</span></label><br>" . (strlen($j["vaksin_1"]) ? "<label class='form-check-label'><input type='radio' class='form-check-input' name='inp_vaksinke' value='1' onChange='activateSubmitBtn()'>&nbsp;&nbsp;1</label><br>" : "") . (strlen($j["vaksin_2"]) ? "<label class='form-check-label'><input type='radio' class='form-check-input' name='inp_vaksinke' value='2' onChange='activateSubmitBtn()'>&nbsp;&nbsp;2</label><br>" : "") . (strlen($j["vaksin_booster"]) ? "<label class='form-check-label'><input type='radio' class='form-check-input' name='inp_vaksinke' value='booster' onChange='activateSubmitBtn()'>&nbsp;&nbsp;Booster</label>" : "") . "</div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_vaksinprimer' class='form-label'>Jenis vaksin primer (1 dan 2) <span class='text-danger'>*</span></label><br>" . $lstVaksin . "</div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label for='inp_etiket' class='form-label'>Kode E-Tiket PeduliLindungi (jika ada)</label><input type='text' id='inp_etiket' name='inp_etiket' class='form-control' placeholder='Jawaban Anda' autocomplete='off'></div>";
                                        echo "<div class='mb-3 bg-light rounded p-4'><label class='form-check-label'><input type='checkbox' id='inp_pernyataan' class='form-check-input' name='inp_pernyataan' value='OK' onChange='activateSubmitBtn()'>&nbsp;&nbsp;Dengan ini, saya menyatakan bahwa data yang saya masukkan adalah data sebenarnya.</label></div>";
                                        echo "<div class='mt-5'><input type='hidden' id='form_submit' name='form_submit' value='1'><button type='button' id='btn_back' class='btn btn-secondary' onClick='history.back()'>Kembali</button> <button type='submit' id='btn_submit' class='btn btn-primary' disabled>Kirim</button><br><br><a class='link-primary' onClick='clearForm()' role='button'>Bersihkan form</a></div>";
                                        echo "</form>";
                                        break;
                                    default: echo "";
                                }
                            } else {
                                echo "<div class='mb-3 bg-light rounded p-4'>" . $event["syarat_ketentuan"] . "</div>";
                                echo "<div class='mb-3 bg-light rounded p-4'>Saya <strong>telah</strong> membaca syarat & ketentuan di atas, dan:<br><input type='hidden' id='cnt_sisa' value='" . ($event["kuota"] - $event["terdaftar"]) . "'>
                                <label class='form-check-label'><input type='radio' class='form-check-input' name='is_proceed' value='yes' onChange='activateProceedBtn()'>&nbsp;&nbsp;Ya, saya setuju untuk divaksin</label><br>
                                <label class='form-check-label'><input type='radio' class='form-check-input' name='is_proceed' value='no' onChange='activateProceedBtn()'>&nbsp;&nbsp;Tidak, saya tidak jadi vaksin</label><br>
                                <div class='mt-5'><button type='button' id='btn_proceed' class='btn btn-primary' onClick='isProceed()' disabled>Proses</button></div></div>";
                            }
                        ?>
                    </div>
                <?php } else {
                    echo "<h3 class='text-center'>Mohon maaf, untuk saat ini kami belum memiliki agenda vaksinasi massal COVID-19 dalam waktu dekat. <br>Silakan kembali lagi nanti.</h3>";
                }
            ?>
           <br><br><div class="text-end"><small class="text-muted fst-italic">Developed by <a href="https://github.com/isnanmulia" target="_blank">isnanmulia</a></small></div>
        </div>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="script.js"></script>
</html>
