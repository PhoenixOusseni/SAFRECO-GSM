<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function auth()
    {
        return view('login-admin');
    }

    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function etat()
    {
        return view('pages.etats.index');
    }

    public function parametres()
    {
        return view('pages.parametres.index');
    }

    /**
     * Effacer le cache de l'application
     */
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        return redirect()->back()->with('success', 'Le cache a été vidé avec succès.');
    }

    /**
     * Effacer les logs
     */
    public function clearLogs()
    {
        try {
            $logFiles = glob(storage_path('logs/*.log'));
            foreach ($logFiles as $logFile) {
                unlink($logFile);
            }
            return redirect()->back()->with('success', 'Les logs ont été réinitialisés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la réinitialisation des logs.');
        }
    }

    /**
     * Exécuter les migrations
     */
    public function migrate()
    {
        try {
            \Artisan::call('migrate', ['--force' => true]);
            return redirect()->back()->with('success', 'Les migrations ont été exécutées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'exécution des migrations.');
        }
    }
}

