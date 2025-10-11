<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $stmt = $pdo->query('SELECT * FROM photos ORDER BY upload_time DESC');
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Tam URL'leri oluştur
    $baseUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/';
    foreach ($photos as &$photo) {
        $photo['url'] = $baseUrl . 'uploads/' . $photo['filename'];
    }
    
    echo json_encode($photos);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Veri çekme hatası: ' . $e->getMessage()]);
}
?>
