<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$geminiService = app(App\Services\GeminiService::class);
$result = $geminiService->generateReadmeFromDiff('test-repo', 'Old readme', 'diff content');

echo "Result: \n";
var_dump($result);
