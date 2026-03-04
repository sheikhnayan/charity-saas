<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$email = env('INITIAL_SUPERADMIN_EMAIL', 'superadmin@local.test');
$super = App\Models\User::where('email', $email)->first();
if (!$super) {
    echo "Superadmin not found. Seed or create one first.\n";
    exit(1);
}

Auth::login($super);

$request = Request::create('/admins/users', 'POST', [
    'name' => 'Website Owner Test',
    'email' => 'owner@test.local',
    'password' => 'secret123',
    'password_confirmation' => 'secret123',
    'role' => 'website_owner',
    'website_id' => null,
]);

$controller = app()->make(App\Http\Controllers\Admin\UserController::class);
$response = $controller->store($request);

echo "Response: ";
if (is_object($response) && method_exists($response, 'getStatusCode')) {
    echo $response->getStatusCode() . "\n";
}
echo "Done.\n";
