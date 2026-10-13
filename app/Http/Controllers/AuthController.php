<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $r)
    {
        $d = $r->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (! Auth::attempt($d)) {
            throw ValidationException::withMessages(['email' => 'Credenciales incorrectas.']);
        }$r->session()->regenerate();

        return $r->expectsJson() ? response()->json(['message' => 'Sesión iniciada']) : redirect('/');
    }

    public function logout(Request $r)
    {
        Auth::guard('web')->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return $r->expectsJson() ? response()->json(['message' => 'Sesión cerrada']) : redirect('/login');
    }

    public function token(Request $r)
    {
        $d = $r->validate(['email' => 'required|email', 'password' => 'required|string', 'device_name' => 'required|string|max:80']);
        $u = User::where('email', $d['email'])->first();
        if (! $u || ! Hash::check($d['password'], $u->password)) {
            throw ValidationException::withMessages(['email' => 'Credenciales incorrectas.']);
        }

return ['token' => $u->createToken($d['device_name'], ['*'], now()->addDay())->plainTextToken, 'expires_in_seconds' => 86400];
    }

    public function revoke(Request $r)
    {
        $token = $r->user()->currentAccessToken();
        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

return response()->noContent();
    }
}
