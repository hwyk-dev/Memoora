<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleController extends Controller
{
    public function callback(Request $request): RedirectResponse
    {
        $request->validate(['token' => 'required|string']);

        try {
            $payload = $this->verifyFirebaseToken($request->token);
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google sign-in failed. Please try again.']);
        }

        $user = User::updateOrCreate(
            ['email' => $payload->email],
            [
                'name' => $payload->name ?? explode('@', $payload->email)[0],
                'firebase_uid' => $payload->sub,
                'email_verified_at' => now(),
            ]
        );

        $user->update(['last_login_at' => now()]);

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }

    private function verifyFirebaseToken(string $idToken): object
    {
        $projectId = config('firebase.project_id');

        $publicKeys = Cache::remember('firebase_public_keys', 3600, function () {
            $response = Http::get(
                'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com'
            );
            return $response->json();
        });

        $lastException = null;
        foreach ($publicKeys as $payload) {
            try {
                $decoded = JWT::decode($idToken, new Key($payload, 'RS256'));

                if ($decoded->iss !== "https://securetoken.google.com/{$projectId}") {
                    throw new \RuntimeException('Invalid token issuer.');
                }
                if ($decoded->aud !== $projectId) {
                    throw new \RuntimeException('Invalid token audience.');
                }

                return $decoded;
            } catch (\Throwable $e) {
                $lastException = $e;
            }
        }

        throw $lastException ?? new \RuntimeException('Token verification failed.');
    }
}
