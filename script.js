function activateProceedBtn () {
    if ($('input[name="is_proceed"]:checked').length) $("#btn_proceed").prop("disabled", "");
}
function activateNextBtn () {
    if ($('input[name="schedule"]:checked').length) $("#btn_next").prop("disabled", "");
}
function activateSubmitBtn() {
    nama = $("#inp_namapx").val();
    jk = $('input[name="inp_jk"]:checked').val();
    nik = $("#inp_nik").val();
    tgl_lahir = $("#inp_tgllahir").val();
    klpusia = $('input[name="inp_klpusia"]:checked').val();
    alamat = $("#inp_alamat").val();
    hp = $("#inp_nohp").val();
    email = $("#inp_email").val();
    vaksinke = $('input[name="inp_vaksinke"]:checked').val();
    vaksinprimer = $('input[name="inp_jenisvaksin"]:checked').val();
    pernyataan = $('#inp_pernyataan').prop('checked');
    if (nama && jk && nik && tgl_lahir && klpusia && alamat && hp && email && vaksinke && vaksinprimer && pernyataan)
        $("#btn_submit").prop("disabled", "");
    else $("#btn_submit").prop("disabled", "true");
}
function isProceed() {
    if ($('input[name="is_proceed"]:checked').val() == "yes") {
        if ($('#cnt_sisa').val() > 0) {
            data = "loc=step1";
            location.href = "index.php?data=" + btoa(data);
        } else alert("Mohon maaf, kuota vaksinasi sudah habis.");
    } else {
        alert("Terima kasih telah mengunjungi halaman ini.");
        window.close();
    }
}
function next() {
    schedule = $('input[name=schedule]:checked').val();
    data = "loc=step2&schedule=" + schedule;
    location.href = "index.php?data=" + btoa(data);
}
function clearForm() {
    if (confirm("Apakah Anda yakin?\nTindakan ini akan menghapus jawaban Anda dari semua pertanyaan, dan tidak dapat diurungkan."))
        location.href = "index.php";
}
function newResponse() {
    location.href = "index.php";
}
setTimeout(function() {
    $(".tanggal-lahir").datepicker({
        autoclose: true,
        endDate: "0d",
        language: "id",
        todayHighlight: true,
        weekStart: 1,
        format: "yyyy-mm-dd",
    });
}, 500);