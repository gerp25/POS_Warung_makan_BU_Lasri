<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = array();
session_destroy();
echo "<script>
    alert('Anda telah berhasil keluar.');
    window.location.href = '../resto_project/login';
</script>";
exit();
?>