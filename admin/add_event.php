<?php
    require_once '../template/header.php';
    require_once '../config/conn.php';
    if (isset($_POST["form_submit"])) {
        mysqli_autocommit($conn, FALSE);
        switch ($_POST["inp_sesi"]) {
            case "N":
                $sesi = ['N'];
                $kuota = $_POST["inp_kuota"];
                break;
            case "3":
                $sesi = ['9', '10', '11'];
                $kuota = floor($_POST["inp_kuota"]/3);
                break;
        }
        $sk = addslashes(trim($_POST["inp_sk"]));
        $mulai = new DateTime($_POST["inp_tglmulai"]);
        $selesai = new DateTime($_POST["inp_tglselesai"]);
        $interval = DateInterval::createFromDateString('1 day');
        $periode = new DatePeriod($mulai, $interval, $selesai->modify("+1 day"));
        $qInsertEvent = "INSERT INTO event_vaksin (tanggal_mulai, tanggal_selesai, judul, subjudul, syarat_ketentuan, status_event, status_publish) VALUES ('" . $_POST["inp_tglmulai"] . "', '" . $_POST["inp_tglselesai"] . "', '" . $_POST["inp_judul"] . "', '" . $_POST["inp_subjudul"] . "', '" . $sk . "', '1', '0')";
        $insertEvent = mysqli_query($conn, $qInsertEvent);
        $status = $insertEvent;
        $getID = mysqli_query($conn, "SELECT id_event FROM event_vaksin WHERE tanggal_mulai='" . $_POST["inp_tglmulai"] . "' AND tanggal_selesai='" . $_POST["inp_tglselesai"] . "' AND judul='" . $_POST["inp_judul"] . "'");
        $id = mysqli_fetch_row($getID)[0];
        $fieldjenis = "";
        $valuejenis = "";
        foreach ($_POST["inp_jenis"] as $j) {
            $fieldjenis .= "vaksin_" . $j . ", ";
            $valuejenis .= $_POST["sel_vaksin"] . ", ";
        }
        $qInsertDetailEvent = "";
        $qInsertDetailEvent2 = "";
        foreach ($sesi as $s) {
            $qInsertDetailEvent .= "INSERT INTO detail_event_vaksin (id_event, tanggal, sesi, kuota, " . $fieldjenis . "status_detail_event) VALUES (" . $id . ", '#Date#', '" . $s . "', " . $kuota . ", " . $valuejenis . "'1');";
        }
        foreach ($periode as $tgl) {
            $qInsertDetailEvent2 .= str_replace("#Date#", $tgl->format("Y-m-d"), $qInsertDetailEvent);
        }
        $insertDetailEvent = mysqli_multi_query($conn, $qInsertDetailEvent2);
        do {
            if ($result = mysqli_store_result($conn)) {
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($conn));
        $status = $status && $insertDetailEvent;
        if ($status) {
            mysqli_commit($conn);
            $msg = "Sukses menambahkan data Event Vaksin";
        } else {
            mysqli_rollback($conn);
            $msg = "Gagal menambahkan data Event Vaksin";
        }
        mysqli_autocommit($conn, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='event.php'</script>";
    }
    $getVaksin = mysqli_query($conn, "SELECT id_vaksin, nama_vaksin FROM jenis_vaksin ORDER BY nama_vaksin");
    $lstVaksin = "<option value=''>--- Pilih Vaksin ---</option>";
    while ($data = mysqli_fetch_assoc($getVaksin)) {
        $lstVaksin .= "<option value='" . $data["id_vaksin"] . "'>" . $data["nama_vaksin"] . "</option>";
    }
?>

<h2>Tambah Event Vaksinasi</h2>
<form method="POST" action="" onSubmit="return validateForm()">
    <table class="table">
        <tr>
            <td style="width: 200px">Judul Event <span class="text-danger">*</span></td>
            <td style="width: 800px"><input type="text" id="inp_judul" name="inp_judul" class="form-control d-inline" style="width: 500px"></td>
        </tr>
        <tr>
            <td>Subjudul Event</td>
            <td><input type="text" id="inp_subjudul" name="inp_subjudul" class="form-control d-inline" style="width: 500px"></td>
        </tr>
        <tr>
            <td>Tanggal <span class="text-danger">*</span></td>
            <td>
                <input type="text" id="inp_tglmulai" name="inp_tglmulai" class="form-control inline-input tanggal">&nbsp;&nbsp;s.d.&nbsp;&nbsp;<input type="text" id="inp_tglselesai" name="inp_tglselesai" class="form-control inline-input tanggal">
            </td>
        </tr>
        <tr>
            <td>Merk Vaksin <span class="text-danger">*</span></td>
            <td>
                <select id="sel_vaksin" name="sel_vaksin" class="form-control d-inline" style="width: 200px"><?php echo $lstVaksin ?></select>
            </td>
        </tr>
        <tr>
            <td>Jenis Vaksinasi <span class="text-danger">*</span></td>
            <td>
                <label class="form-check-label"><input type="checkbox" class="form-check-input" name="inp_jenis[]" value="1">&nbsp;&nbsp;1</label>&nbsp;&nbsp;&nbsp;&nbsp;
                <label class="form-check-label"><input type="checkbox" class="form-check-input" name="inp_jenis[]" value="2">&nbsp;&nbsp;2</label>&nbsp;&nbsp;&nbsp;&nbsp;
                <label class="form-check-label"><input type="checkbox" class="form-check-input" name="inp_jenis[]" value="booster">&nbsp;&nbsp;Booster</label>
            </td>
        </tr>
        <tr>
            <td>Kuota <span class="text-danger">*</span></td>
            <td><input type="text" id="inp_kuota" name="inp_kuota" class="form-control d-inline" style="width: 70px">&nbsp;&nbsp;orang per hari</td>
        </tr>
        <tr>
            <td>Sesi <span class="text-danger">*</span></td>
            <td>
                <label class='form-check-label'><input type='radio' class='form-check-input' name='inp_sesi' value='N'>&nbsp;&nbsp;Tunggal</label>&nbsp;&nbsp;&nbsp;&nbsp;
                <label class='form-check-label'><input type='radio' class='form-check-input' name='inp_sesi' value='3'>&nbsp;&nbsp;3 (kuota akan dibagi rata per sesi)</label>
            </td>
        </tr>
        <tr>
            <td>Syarat & Ketentuan <span class="text-danger">*</span></td>
            <td><textarea id="inp_sk" name="inp_sk"></textarea></td>
        </tr>
    </table>
    <div class="d-flex justify-content-end">
        <input type="hidden" id="form_submit" name="form_submit" value="1">
        <a class="btn btn-primary" onClick="history.back()"><i class="bi bi-x-lg"></i> Batal</a>&nbsp;
        <button type="submit" id="btn_submit" class="btn btn-primary"><i class="bi bi-save" disabled></i> Simpan</button>
    </div>
</form>
<script>
    function validateForm() {
        judul = $("#inp_judul").val();
        tglmulai = $("#inp_tglmulai").val();
        tglselesai = $("#inp_tglselesai").val();
        vaksin = $("#sel_vaksin").val();
        jenis = $('input[name="inp_jenis[]"]:checked').val();
        kuota = $("#inp_kuota").val();
        sesi = $('input[name="inp_sesi"]:checked').val();
        sk = $("#inp_sk").val();
        if (!judul) {
            alert("Judul kosong");
            $("#inp_judul").focus();
            return false;
        } else if (!tglmulai) {
            alert("Tanggal Mulai kosong");
            $("#inp_tglmulai").focus();
            return false;
        } else if (!tglselesai) {
            alert("Tanggal Selesai kosong");
            $("#inp_tglselesai").focus();
            return false;
        } else if (tglmulai > tglselesai) {
            alert("Tanggal Selesai tidak boleh kurang dari Tanggal Mulai");
            return false;
        } else if (!vaksin) {
            alert("Merk Vaksin belum dipilih");
            $("#sel_vaksin").focus();
            return false;
        } else if (!jenis) {
            alert("Jenis Vaksinasi belum dipilih");
            return false;
        } else if (!kuota) {
            alert("Kuota kosong");
            $("#inp_kuota").focus();
            return false;
        } else if (!sesi) {
            alert("Sesi kosong");
            return false;
        } else if (!sk) {
            alert("Syarat & Ketentuan kosong");
            $("#inp_sk").summernote("focus");
            return false;
        }
        return true;
    }
    setTimeout(function() {
        $(".tanggal").datepicker({
            autoclose: true,
            startDate: "0d",
            language: "id",
            todayHighlight: true,
            weekStart: 1,
            format: "yyyy-mm-dd",
        });
        $('#inp_sk').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['para', ['ul', 'ol']],
                ['view', ['codeview', 'help']]
            ],
        });
    }, 500);
</script>

<?php require_once '../template/footer.php' ?>