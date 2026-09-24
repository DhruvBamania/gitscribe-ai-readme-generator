<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $service = app(\App\Services\GeminiService::class);
    
    echo "1. Picking files...\n";
    $files = $service->pickFilesToExplore("test-repo", json_encode([['path'=>'test1.php'], ['path'=>'test2.php']]));
    print_r($files);

    echo "2. Generating readme...\n";
    $res = $service->generateReadme('test-repo', 'desc', 'PHP');
    echo "Result: \n" . substr($res, 0, 100) . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
