<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'admin@gmail.com')->first();
if ($user) {
    echo json_encode($user->only(['id','email','role','created_at']));
} else {
    echo "null";
}
