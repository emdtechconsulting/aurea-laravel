<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('aurea:admin', function () {
    $email = $this->ask('Correo del administrador');
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Correo inválido');

        return 1;
    }
    if (User::where('email', $email)->exists()) {
        $this->error('Este usuario ya existe; no se modificó.');

        return 1;
    }
    $password = $this->secret('Contraseña (mínimo 12 caracteres)');
    if (strlen($password ?? '') < 12) {
        $this->error('Contraseña demasiado corta');

        return 1;
    }
    User::create(['name' => 'Administrador AUREA', 'email' => $email, 'password' => Hash::make($password)]);
    $this->info('Administrador creado.');
})->purpose('Crear una cuenta privada para el laboratorio');
