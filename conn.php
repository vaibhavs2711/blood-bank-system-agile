<?php
mysqli_report(MYSQLI_REPORT_OFF);
try {
    $conn = @mysqli_connect("localhost", "root", "root", "blood_donation");
} catch (mysqli_sql_exception $error) {
    $conn = null;
}
if ($conn) {
    mysqli_set_charset($conn, 'utf8mb4');
}
?>
