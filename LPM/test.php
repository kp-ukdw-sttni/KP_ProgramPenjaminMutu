<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$role = Spatie\Permission\Models\Role::findByName('auditee');
dump($role->permissions->pluck('name'));
