<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Sunucu ayarlarını optimize et (büyük dosyalar için)
ini_set('max_execution_time', 300); // 5 dakika
ini_set('memory_limit', '512M'); // 512MB hafıza
ini_set('post_max_size', '100M'); // 100MB maksimum post
ini_set('upload_max_filesize', '50M'); // 50MB maksimum dosya

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $uploadDir = 'uploads/';
    
    // Uploads klasörü yoksa oluştur
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
    $filePath = $uploadDir . $fileName;
    $fileSize = $_FILES['photo']['size'];
    $mimeType = $_FILES['photo']['type'];
    
    // Dosya boyutu kontrolü (max 50MB)
    if ($fileSize > 50 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Dosya boyutu 50MB\'dan küçük olmalıdır']);
        exit;
    }
    
    // Dosya tipi kontrolü
    $allowedTypes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-matroska'
    ];
    
    if (!in_array($mimeType, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Desteklenmeyen dosya formatı']);
        exit;
    }
    
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
        // Veritabanına kaydet
        $stmt = $pdo->prepare('INSERT INTO photos (filename, guest_name, message, file_size, mime_type) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$fileName, $_POST['name'], $_POST['message'], $fileSize, $mimeType]);
        
        echo json_encode(['success' => true, 'message' => 'Fotoğraf başarıyla yüklendi']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Dosya yükleme hatası']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
}
?>
