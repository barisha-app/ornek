<?php
// Veritabanı bağlantı bilgileri - BUNLARI KENDİ BİLGİLERİNİZLE DEĞİŞTİRİN
$host = 'localhost';
$dbname = 'sizin_veritabani_adi';
$username = 'sizin_kullanici_adi';
$password = 'sizin_sifre';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
?>
