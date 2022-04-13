<?php
    require_once '../template/header.php';
    require_once '../config/conn.php';
    if (isset($_POST["form_submit"])) {
        // var_dump($_POST); die();
        mysqli_autocommit($conn, FALSE);
        $sk = addslashes(trim($_POST["inp_sk"]));
        $qUpdateEvent = "UPDATE event_vaksin SET judul='" . $_POST["inp_judul"] . "', subjudul='" . $_POST["inp_subjudul"] . "', syarat_ketentuan='" . $sk . "', status_event='" . $_POST["sel_sttsevent"] . "', status_publish='" . $_POST["sel_sttspub"] . "' WHERE id_event=" . $_POST["hdn_id"];
        $updateEvent = mysqli_query($conn, $qUpdateEvent);
        if ($updateEvent) {
            mysqli_commit($conn);
            $msg = "Sukses mengubah data Event Vaksin";
        } else {
            mysqli_rollback($conn);
            $msg = "Gagal mengubah data Event Vaksin";
        }
        mysqli_autocommit($conn, TRUE);
        echo "<script>alert('" . $msg . "'); window.location.href='event.php'</script>";
    } else if (isset($_GET["id"])) {
        $getEvent = mysqli_query($conn, "SELECT tanggal_mulai, tanggal_selesai, judul, subjudul, syarat_ketentuan, status_event, status_publish FROM event_vaksin WHERE id_event=" . $_GET["id"]);
        $event = mysqli_fetch_assoc($getEvent);
        $getEventDetail = mysqli_query($conn, "SELECT Group_Concat(sesi) AS sesi, IfNull(VS.nama_vaksin, '-') AS v1, IfNull(VD.nama_vaksin, '-') AS v2, IfNull(VB.nama_vaksin, '-') AS vb, kuota FROM detail_event_vaksin DE LEFT JOIN jenis_vaksin VS ON DE.vaksin_1=VS.id_vaksin LEFT JOIN jenis_vaksin VD ON DE.vaksin_2=VD.id_vaksin LEFT JOIN jenis_vaksin VB ON DE.vaksin_booster=VB.id_vaksin WHERE id_event=" . $_GET["id"] . " GROUP BY tanggal LIMIT 1");
        $eventDetail = mysqli_fetch_assoc($getEventDetail);
        $getVaksin = mysqli_query($conn, "SELECT id_vaksin, nama_vaksin FROM jenis_vaksin ORDER BY nama_vaksin");
        $lstVaksin = "<option value=''>--- Pilih Vaksin ---</option>";
        while ($data = mysqli_fetch_assoc($getVaksin)) {
            $lstVaksin .= "<option value='" . $data["id_vaksin"] . "'>" . $data["nama_vaksin"] . "</option>";
        }
?>

<h2>Ubah Event Vaksinasi</h2>
<form method="POST" action="" onSubmit="return validateForm()">
    <table class="table">
        <tr>
            <td style="width: 200px">Judul Event <span class="text-danger">*</span></td>
            <td style="width: 800px"><input type="text" id="inp_judul" name="inp_judul" class="form-control d-inline" style="width: 500px" value="<?php echo $event["judul"] ?>"><input type="hidden" id="hdn_id" name="hdn_id" value="<?php echo $_GET["id"] ?>"></td>
        </tr>
        <tr>
            <td>Subjudul Event</td>
            <td><input type="text" id="inp_subjudul" name="inp_subjudul" class="form-control d-inline" style="width: 500px" value="<?php echo $event["subjudul"] ?>"></td>
        </tr>
        <tr>
            <td>Tanggal <span class="text-danger">*</span></td>
            <td><?php echo $event["tanggal_mulai"] . "&nbsp;&nbsp;s.d.&nbsp;&nbsp;" . $event["tanggal_selesai"] ?></td>
        </tr>
        <tr>
            <td>Jenis Vaksinasi</td>
            <td>
                <?php if ($eventDetail["v1"] != '-') { ?><label class="form-check-label">1: <?php echo $eventDetail["v1"] ?></label><br><?php } ?>
                <?php if ($eventDetail["v2"] != '-') { ?><label class="form-check-label">2: <?php echo $eventDetail["v2"] ?></label><br><?php } ?>
                <?php if ($eventDetail["vb"] != '-') { ?><label class="form-check-label">Booster: <?php echo $eventDetail["vb"] ?></label><?php } ?>
            </td>
        </tr>
        <tr>
            <td>Kuota <span class="text-danger">*</span></td>
            <td><?php echo $eventDetail["kuota"] ?>&nbsp;&nbsp;orang per hari</td>
        </tr>
        <tr>
            <td>Sesi Per Hari</td>
            <td>
                <?php if ($eventDetail["sesi"] == "N") echo "Tunggal"; else if ($eventDetail["sesi"] == "9,10,11") echo "3"; ?>
            </td>
        </tr>
        <tr>
            <td>Syarat & Ketentuan <span class="text-danger">*</span></td>
            <td><textarea id="inp_sk" name="inp_sk"><?php echo $event["syarat_ketentuan"] ?></textarea></td>
        </tr>
        <tr>
            <td>Status Event</td>
            <td><select id="sel_sttsevent" name="sel_sttsevent" class="form-control d-inline" style="width: 100px">
                <option value="0" <?php if ($event["status_event"] == "0") { ?>selected<?php } ?>>Tidak aktif</option>
                <option value="1" <?php if ($event["status_event"] == "1") { ?>selected<?php } ?>>Aktif</option>
            </select></td>
        </tr>
        <tr>
            <td>Status Publikasi</td>
            <td><select id="sel_sttspub" name="sel_sttspub" class="form-control d-inline" style="width: 170px">
                <option value="0" <?php if ($event["status_publish"] == "0") { ?>selected<?php } ?>>Tidak dipublikasikan</option>
                <option value="1" <?php if ($event["status_publish"] == "1") { ?>selected<?php } ?>>Dipublikasikan</option>
            </select></td>
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
        sk = $("#inp_sk").val();
        if (!judul) {
            alert("Judul kosong");
            $("#inp_judul").focus();
            return false;
        } else if (!sk) {
            alert("Syarat & Ketentuan kosong");
            $("#inp_sk").summernote("focus");
            return false;
        }
        return true;
    }
    setTimeout(function() {
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

<?php require_once '../template/footer.php'; } ?>