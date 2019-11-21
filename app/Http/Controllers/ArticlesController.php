<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Permissions;
use Illuminate\Support\Facades\Storage;


class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Article::paginate(10);
    }

    public function show(Request $request)
    {
        $article = Article::find($request->article_id);
        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], 404);
        }
        return $article;
    }

    public function store(Request $request)
    {
        //@TODO check if auth is admin or legal user
        $this->isAdminOrUserWithPermission($request);
        if (!auth()->user()->hasPermission(Permissions::CREATE_POST)) {
            return response()->json([
                'message' => 'unAuthorized'
            ], 401);
        }
        // @TODO add validation here
        $this->dataValidation($request);
        $article = new Article();
        $article->title = $request->title;
        $article->content = $request->get('content');
        $article->user_id = auth()->user()->id;
        if ($request->img != null) {
            $img_name = rand(0, 999) . $request->img->getClientOriginalName();
            $request->img->storeAs('public', $img_name);
//            Storage::putFile('images',$img_name);
//            $request->img->store('images');
            $article->img = $img_name;
        }
        $article->save();
    }

    public function update(Request $request)
    {
        $article = Article::find($request->article_id);
        if (!$article) {
            return response()->json([
                'message' => 'Article not found'
            ], 404);
        }

        if (!auth()->user()->canEditArticle($article)) {
            return response()->json([
                'message' => 'UnAuthorized'
            ], 401);
        }
        //@TODO check validation
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        $article->title = $request->title;
        $article->content = $request->get('content');
        $article->save();
    }

    private function dataValidation($request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
    }

    private function isAdminOrUserWithPermission($request)
    {
        if (!auth()->user()->is_super_admin) {
            if (auth()->id() != $request->user_id)
                return response()->json([
                    'message' => 'not Admin or not auth user'
                ]);
        }
    }
}
