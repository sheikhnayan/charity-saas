<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'owner@test.local')->first();
if ($user) {
    echo "Found user: {$user->id} - {$user->email}\n";
    $roles = \DB::table('role_user_website')->where('user_id', $user->id)->get();
    echo "Roles:\n";
    foreach ($roles as $r) {
        $role = App\Models\Role::find($r->role_id);
        echo " - " . ($role ? $role->name : $r->role_id) . " (website: " . ($r->website_id ?? 'global') . ")\n";
    }
} else {
    echo "User not found\n";
}
