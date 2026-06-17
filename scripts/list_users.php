<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::all(['id','email','role','created_at']);
$arr = [];
foreach ($users as $u) {
    $arr[] = $u->only(['id','email','role','created_at']);
}
echo json_encode($arr);
