<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle the user login request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login_admin(Request $request)
    {
        // Valider les données reçues
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Tenter l'authentification
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate(); // Protection contre fixation de session

            // Vérifier le rôle de l'utilisateur connecté
            if (Auth::user()) { // 2 pour admin, 3 pour super-admin
                return redirect()->route('dashboard')->with('success', 'Connexion réussie');
            }

            // Déconnexion si le rôle ne correspond pas
            Auth::logout();
            return back()->withErrors([
                'email' => 'Vous n\'êtes pas autorisé à accéder à cette section.',
            ])->onlyInput('email');
        }

        // Échec : identifiants invalides
        return back()->withErrors([
            'email' => 'Les identifiants sont invalides.',
        ])->onlyInput('email');
    }


    /**
     * Handle the user logout request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Protection CSRF

        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }
}
