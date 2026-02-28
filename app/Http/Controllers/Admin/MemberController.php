<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Pupitre;
use Illuminate\Http\Request;

use App\Services\SupabaseService;

class MemberController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        $members = User::with('pupitre', 'role')
            ->latest()
            ->paginate(10);

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        $pupitres = Pupitre::all();
        $roles = Role::all();
        return view('admin.members.create', compact('pupitres', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'pupitre_id' => 'required|exists:pupitres,id',
            'role_id' => 'required|exists:roles,id',
            'date_naissance' => 'nullable|date',
            'photo' => 'nullable|image|max:2048', // Max 2Mo
            'citation' => 'nullable|string|max:255',
            'activite' => 'nullable|string|max:255',
            'hobbie' => 'nullable|string|max:255',
            'love_choir' => 'nullable|string|max:1000',
        ]);

        // 1. Préparation des données supplémentaires
        $password = \Illuminate\Support\Str::random(12);
        $validated['password'] = bcrypt($password);
        $validated['slug'] = str($validated['first_name'] . ' ' . $validated['last_name'])->slug();
        //$validated['is_active'] = $request->has('is_active');

        // 2. Créer l'utilisateur dans Supabase Auth
        $supabaseUser = $this->supabase->createUser($validated['email'], $password, [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
        ]);

        if (!$supabaseUser) {
            return back()->withInput()->with('error', 'Erreur lors de la création du compte Supabase Auth.');
        }

        // 3. Gérer l'upload de la photo si présente
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = 'avatars/' . $validated['slug'] . '-' . time() . '.' . $photo->getClientOriginalExtension();
            $photoUrl = $this->supabase->uploadFile('imgs', $path, $photo);
            if ($photoUrl) {
                $validated['photo_url'] = $photoUrl;
            }
        }

        // 4. Créer l'utilisateur localement
        $user = User::create($validated);

        // 4. Envoyer l'email de bienvenue avec les identifiants
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WelcomeMemberEmail($user, $password));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Welcome Email Fail: ' . $e->getMessage());
            // On ne bloque pas la création si l'email échoue, mais on prévient l'admin
            return redirect()->route('admin.members.index')
                ->with('success', 'Membre créé (Supabase OK), mais l\'email n\'a pas pu être envoyé.')
                ->with('warning', 'Mot de passe généré : ' . $password);
        }

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre créé avec succès. Un email de bienvenue a été envoyé.');
    }
    public function edit(User $member)
    {
        $pupitres = Pupitre::all();
        $roles = Role::all();
        return view('admin.members.edit', compact('member', 'pupitres', 'roles'));
    }

    public function update(Request $request, User $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'pupitre_id' => 'required|exists:pupitres,id',
            'role_id' => 'required|exists:roles,id',
            'date_naissance' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
            'citation' => 'nullable|string|max:255',
            'activite' => 'nullable|string|max:255',
            'hobbie' => 'nullable|string|max:255',
            'love_choir' => 'nullable|string|max:1000',
        ]);

        // Mise à jour du slug si le nom change
        $validated['slug'] = str($validated['first_name'] . ' ' . $validated['last_name'])->slug();
        //$validated['is_active'] = $request->has('is_active');

        // Gérer l'upload de la photo si présente
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = 'avatars/' . $validated['slug'] . '-' . time() . '.' . $photo->getClientOriginalExtension();
            $photoUrl = $this->supabase->uploadFile('imgs', $path, $photo);
            if ($photoUrl) {
                $validated['photo_url'] = $photoUrl;
            }
        }

        $member->update($validated);

        return redirect()->route('admin.members.index')->with('success', 'Membre mis à jour avec succès.');
    }

    public function destroy(User $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')
            ->with('success', 'Membre supprimé avec succès.');
    }

    public function toggleStatus(User $member)
    {
        $member->update(['is_active' => !$member->is_active]);
        return back()->with('success', 'Statut du membre mis à jour.');
    }
}
