<?php
    require_once '../template/header.php';
    require_once '../config/conn.php';
    $getEvents = mysqli_query($conn, "SELECT id_event, tanggal_mulai, tanggal_selesai, judul FROM event_vaksin");
    $events = "<option value='-'>--- Pilih event ---</option>";
    while ($data = mysqli_fetch_assoc($getEvents)) {
        $events .= "<option value='" . $data["id_event"] . "'>" . $data["judul"] . " (" . $data["tanggal_mulai"] . " s.d. " . $data["tanggal_selesai"] . ")</option>";
    }
?>

<h2>Ekspor Data Peserta Vaksinasi</h2>

<p>
    <form action="export_excel.php" method="POST" onSubmit="return validateForm()">
        Pilih event vaksin:&nbsp;&nbsp;
        <select id="sel_event" name="sel_event" class="form-control d-inline" style="width:600px">
            <?php echo $events ?>
        </select>
        <input type="hidden" id="form_submit" name="form_submit" value="1">
        <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-right"></i> Proses</button>
    </form>
</p>
<script>
    function validateForm() {
        event = $("#sel_event").val();
        if (event == "-") {
            alert("Silakan pilih event vaksin");
            $("#sel_event").focus();
            return false;
        }
        return true;
    }
</script>

<?php require_once '../template/footer.php' ?>