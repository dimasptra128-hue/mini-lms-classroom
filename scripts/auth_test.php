<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'admin@gmail.com')->first();
if (! $user) {
    echo "no-user";
    exit;
}
$check = \Illuminate\Support\Facades\Hash::check('admin123', $user->password);
echo $check ? 'hash-check-ok' : 'hash-check-failed';
