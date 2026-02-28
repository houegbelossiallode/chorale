<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Services\SupabaseService;

class PostController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'image_path' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['author_id'] = auth()->id();
        $data['slug'] = Str::slug($request->title);
        $data['published_at'] = $request->boolean('is_published') ? now() : null;
        $data['type'] = $request->category; // Mapping de category (vue) vers type (DB)

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $path = 'news/' . $data['slug'] . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imageUrl = $this->supabase->uploadFile('imgs', $path, $image);
            $data['image_path'] = $imageUrl;
        }

        // On retire is_published car ce n'est pas une colonne
        unset($data['is_published'], $data['category']);

        Post::create($data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Article créé avec succès.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'image_path' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->title !== $post->title) {
            $data['slug'] = Str::slug($request->title);
        }

        $data['published_at'] = $request->boolean('is_published') ? ($post->published_at ?? now()) : null;
        $data['type'] = $request->category;

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $path = 'news/' . ($data['slug'] ?? $post->slug) . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imageUrl = $this->supabase->uploadFile('imgs', $path, $image);
            $data['image_path'] = $imageUrl;
        }

        unset($data['is_published'], $data['category']);

        $post->update($data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')
            ->with('success', 'Article supprimé.');
    }
}
