<?php
    require_once '../template/header.php';
    require_once '../config/conn.php';
    $getEvent = mysqli_query($conn, "SELECT id_event, tanggal_mulai, tanggal_selesai, judul, subjudul, status_event, status_publish FROM event_vaksin");
    $lstEvent = ""; $i = 0;
    while ($data = mysqli_fetch_assoc($getEvent)) {
        switch ($data["status_event"]) {
            case "0": $sttsevt = "<i class='bi bi-x-circle text-danger'></i>"; break;
            case "1": $sttsevt = "<i class='bi bi-check-lg text-success'></i>"; break;
        }
        switch ($data["status_publish"]) {
            case "0": $sttspub = "<i class='bi bi-x-circle text-danger'></i>"; break;
            case "1": $sttspub = "<i class='bi bi-check-lg text-success'></i>"; break;
        }
        $lstEvent .= "<tr><td>" . ++$i . "</td><td>" . $data["judul"] . "</td><td>" . $data["subjudul"] . "</td><td>" . $data["tanggal_mulai"] . "</td><td>" . $data["tanggal_selesai"] . "</td><td>" . $sttsevt . "</td><td>" . $sttspub . "</td><td><a class='btn btn-primary btn-sm' title='Ubah' href='edit_event.php?id=" . $data["id_event"] . "'><i class='bi bi-pencil-square'></a></td></tr>";
    }
?>

<h2>Event Vaksinasi</h2>
<a class="btn btn-primary" href="add_event.php"><i class="bi bi-plus-lg"></i> Tambah</a>
<table class="table table-hover">
    <tr>
        <th>No</th>
        <th>Judul Event</th>
        <th>Subjudul Event</th>
        <th>Tanggal Mulai</th>
        <th>Tanggal Selesai</th>
        <th>Status Event</th>
        <th>Status Publikasi</th>
        <th>Aksi</th>
    </tr>
    <?php echo $lstEvent ?>
</table>

<?php require_once '../template/footer.php' ?>