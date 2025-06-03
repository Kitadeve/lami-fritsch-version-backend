<?php
header('Content-Type: application/json');

// Inclure la clé API depuis un fichier non versionné
include 'config.php';

// Prépare la requête cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.thecatapi.com/v1/images/search?size=med&mime_types=jpg&format=json&has_breeds=true&order=RANDOM&page=0&limit=1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "x-api-key: $apiKey"
]);

$response = curl_exec($ch);

if(curl_errno($ch)){
    http_response_code(500);
    echo json_encode(["error" => "Erreur serveur"]);
} else {
    echo $response;
}

curl_close($ch);
?>