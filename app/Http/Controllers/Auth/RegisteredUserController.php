<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Medecin;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', Rule::in(['admin', 'medecin'])],
        'specialite' => 'required_if:role,medecin|string|max:255|nullable',
        'type' => 'required_if:role,medecin|in:GENERALISTE,ENTREPRISE|nullable',
    ]);
       // Créer l'utilisateur
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);
      // Créer le médecin si nécessaire
    if ($validated['role'] === 'medecin') {
        Medecin::create([
            'user_id' => $user->id,
            'nom' => $validated['name'],
            'specialite' => $validated['specialite'],
            'type' => $validated['type'],
        ]);
    }
    event(new Registered($user));
    Auth::login($user);
return redirect()->to('/');
}
}
