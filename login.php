<?php
    require_once 'config/base_url.php';
    require_once 'config/setting_rs.php';
    session_start();
    // session checking
    if (isset($_SESSION["USER"])) {
        $_SESSION["LAST_ACTIVITY"] = time();
        header("Location: admin/index.php");
    }
?>

<!doctype html>
<html>
    <head>
        <title>Halaman Admin - Pendaftaran Vaksin Covid-19 <?php echo $namaRS ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="../style.css">
    </head>
    <body class="p-3">
        <div class="container justify-content-center m-auto p-3 rounded" style="background-color: #FAC898; max-width: 500px">
            <table class="table text-center">
                <tr>
                    <td><img src="<?php echo $logoRS ?>" style="width: 50px"></td>
                    <td>
                        <h2 class="d-inline"><?php echo $namaPanjangRS ?></h2>
                        <h6><?php echo $alamatRS ?></h6>
                    </td>
                </tr>
            </table>
            <form action="validate_login.php" method="POST" onSubmit="return validateForm()">
                <div class='mb-3 bg-light rounded p-4'>
                    <h5 class="text-center mb-3">Masuk untuk memulai sesi Anda</h5>
                    <input type='text' id='inp_username' name='inp_username' class='form-control my-3' autocomplete='off' placeholder="Nama Pengguna">
                    <input type='password' id='inp_password' name='inp_password' class='form-control my-3' autocomplete='off' placeholder="Password">
                </div>
                <div class='d-grid mx-auto'>
                    <input type='hidden' id='form_submit' name='form_submit' value='1'><button type='submit' id='btn_submit' class='btn btn-primary'><i class="bi bi-box-arrow-in-right"></i> Masuk</button>
                </div>
            </form>
        </div>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../script.js"></script>
    <script>
        function validateForm() {
            user = $("#inp_username").val();
            pass = $("#inp_password").val();
            if (!user) {
                alert("Nama Pengguna kosong");
                $("#inp_username").focus();
                return false;
            } else if (!pass) {
                alert("Password kosong");
                $("#inp_password").focus();
                return false;
            }
            return true;
        }
        $("#inp_username").focus()
    </script>
</html>