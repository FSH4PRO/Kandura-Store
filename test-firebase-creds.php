<?php

$json = json_decode(file_get_contents('storage/app/firebase/firebase-admin.json'), true);
if (!$json) {
    echo "❌ Invalid JSON\n";
    exit(1);
}

echo "✅ Project ID: " . ($json['project_id'] ?? 'MISSING') . "\n";
echo "✅ Client Email: " . substr($json['client_email'] ?? 'MISSING', 0, 50) . "...\n";
echo "✅ Has Private Key: " . (isset($json['private_key']) ? 'YES (' . strlen($json['private_key']) . ' bytes)' : 'NO') . "\n";
echo "✅ Valid JSON: YES\n";

// Check if the project_id matches .env
$envProjectId = env('FIREBASE_PROJECT_ID', 'kandura-store');
echo "\n📌 ENV FIREBASE_PROJECT_ID: " . $envProjectId . "\n";
echo "📌 JSON project_id: " . $json['project_id'] . "\n";
echo ($json['project_id'] === $envProjectId ? "✅ MATCH\n" : "❌ MISMATCH\n");
