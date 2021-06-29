<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Post;
use App\Category;
use App\Tag;
use App\Mail\NewPostAdminNotification;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        $data = [
            'posts' => $posts
        ];

        return view('admin.posts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        $data = [
            'categories' => $categories,
            'tags' => $tags
        ];

        return view('admin.posts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());       Per verificare che lo store riceva i dati inseriti

        $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|max:65000',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|exists:tags,id',
            'cover-image' => 'nullable|image|max:10000'
        ]);

        $new_post_data = $request->all();

        // SLUG
        $new_slug = Str::slug($new_post_data['title'], '-');
        $base_slug = $new_slug;
        $post_with_existing_slug = Post::where('slug', '=', $new_slug)->first();
        $counter = 1;

        while($post_with_existing_slug) {
            $new_slug = $base_slug . '-' . $counter;
            $counter++;
            $post_with_existing_slug = Post::where('slug', '=', $new_slug)->first();
        }

        $new_post_data['slug'] = $new_slug;

        if(isset($new_post_data['cover-image'])) {
            $new_img_path = Storage::put('post_covers', $new_post_data['cover-image']);

            if($new_img_path) {
                $new_post_data['cover'] = $new_img_path;
            }
        }

        // NEW POST
        $new_post = new Post();
        $new_post->fill($new_post_data);
        $new_post->save();

        // TAGS
        if (isset($new_post_data['tags']) && is_array($new_post_data['tags'])) {
            $new_post->tags()->sync($new_post_data['tags']);
        }

        // Invio della mail
        Mail::to('manuel@email.it')->send(new NewPostAdminNotification($new_post));

        return redirect()->route('admin.posts.show', ['post' => $new_post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post =Post::findOrFail($id);

        $data = [
            'post' => $post,
            'post_category' => $post->category,
            'post_tags' => $post->tags
        ];

        return view('admin.posts.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();

        $data = [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags
        ];

        return view('admin.posts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|max:65000',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|exists:tags,id',
            'cover-image' => 'nullable|image|max:10000'
        ]);

        $form_data = $request->all();

        $post = Post::findOrFail($id);

        // Di default lo slug non dovrebbe essere cambiato a meno che cambi il titolo del post
        $form_data['slug'] = $post->slug;

        // Se il titolo cambia allora ricalcolo lo slug
        if($form_data['title'] != $post->title) {
            // Creo lo slug
            $new_slug = Str::slug($form_data['title'], '-');
            $base_slug = $new_slug;

            // Controllo che non esista un post con questo slug
            $post_with_existing_slug = Post::where('slug', '=', $new_slug)->first();
            $counter = 1;

            // Se esiste provo con altri slug
            while($post_with_existing_slug) {
                // Provo on un nuovo slug appendendo il counter
                $new_slug = $base_slug . '-' . $counter;
                $counter++;

                // Se anche il nuovo slug esiste nel db, il while continua...
                $post_with_existing_slug = Post::where('slug', '=', $new_slug)->first();
            }

            // Quando finalmente trovo uno slug libero, popolo i data da salvare
            $form_data['slug'] = $new_slug;
        }

        if(isset($form_data['cover-image'])) {
            $new_img_path = Storage::put('post_covers', $form_data['cover-image']);

            if($new_img_path) {
                $form_data['cover'] = $new_img_path;
            }
        }

        $post->update($form_data);

        //  tags 
        if(isset($form_data['tags']) && is_array($form_data['tags'])){
            $post->tags()->sync($form_data['tags']);
        } else{
            $post->tags()->sync([]);
        }

        return redirect()->route('admin.posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->tags()->sync([]);
        $post->delete();

        return redirect()->route('admin.posts.index');
    }
}
