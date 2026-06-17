<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Course::with('users')->get() as $course) {
    $usersRelation = $course->getRelationValue('users');
    echo "COURSE {$course->id} {$course->name} users_relation_type=" . gettype($usersRelation) . " count=" . (is_countable($usersRelation) ? count($usersRelation) : 'N/A') . "\n";
    if (is_countable($usersRelation)) {
        foreach ($usersRelation as $u) {
            echo "  U:{$u->id} {$u->name} role=" . ($u->pivot->role ?? 'none') . "\n";
        }
    } else {
        echo "  users_relation raw: ";
        var_export($usersRelation);
        echo "\n";
    }
}
