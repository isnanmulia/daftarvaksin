<?php
  require_once '../config/setting_rs.php';
  require_once '../config/session_check.php';
  $filename = explode("?", $URI[3])[0];
  switch ($filename) {
      case "index.php": $active = "home"; $title = "Beranda"; break;
      case "event.php": $active = "event"; $title = "Event Vaksin"; break;
      case "add_event.php": $active = "event"; $title = "Tambah Event Vaksin"; break;
      case "edit_event.php": $active = "event"; $title = "Ubah Event Vaksin"; break;
      case "export.php": $active = "export"; $title = "Ekspor Data"; break;
      case "statistics.php": $active = "stat"; $title = "Statistik"; break;
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- include summernote css -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <title><?php echo $title ?> | Halaman Admin - Pendaftaran Vaksin Covid-19 <?php echo $namaRS ?></title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#">DaftarVaksin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?php echo ($active === "home") ? "active" : "" ?>" href="index.php">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($active === "event") ? "active" : "" ?>" href="event.php">Event Vaksin</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownUtil" data-bs-toggle="dropdown" aria-expanded="false">Utilitas</a>
            <ul class="dropdown-menu" aria-labelledby="dropdownUtil">
              <li><a class="dropdown-item <?php echo ($active === "export") ? "active" : "" ?>" href="export.php">Ekspor Data</a></li>
              <li><a class="dropdown-item <?php echo ($active === "stat") ? "active" : "" ?>" href="statistics.php">Statistik</a></li>
            </ul>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link <?php echo ($active === "user") ? "active" : "" ?>" href="user_list.php">User</a>
          </li> -->
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> Keluar</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container p-3">
