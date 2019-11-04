<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Permissions;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission(Permissions::CREATE_POST)) {
            return response()->json([
                'message' => 'unAuthorized'
            ], 401);
        }

        // @TODO add validation here

        $article = new Article();
        $article->title = $request->title;
        $article->content = $request->get('content');
        $article->user_id = auth()->user()->id;
        $article->save();
    }

    public function update(Request $request)
    {
        $article = Article::find($request->article_id);

        if(! $article){
            return response()->json([
                'message' => 'Article not found'
            ],404);
        }

        if(! auth()->user()->canEditArticle($article)){
            return response()->json([
                'message' => 'UnAuthorized'
            ],401);
        }

        $article->title = $request->title;
        $article->content = $request->get('content');

        $article->save();
    }
}
