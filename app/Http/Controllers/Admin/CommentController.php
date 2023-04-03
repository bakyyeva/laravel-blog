<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentFilterRequest;
use App\Http\Requests\Comment\CommentStatusRequest;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(CommentFilterRequest $request)
    {
        $users = User::all();
        $articles = Article::all();

        $list = Comment::query()
            ->where(function($query) use ($request)
            {
                if ($request->min_like_count)
                {
                    $query->where('like_count', ">=", (int)$request->min_like_count);
                }

                if ($request->max_like_count)
                {
                    $query->where('like_count', "<=", (int)$request->max_like_count);
                }

                if ($request->min_unlike_count)
                {
                    $query->where('unlike_count', ">=", (int)$request->min_unlike_count);
                }

                if ($request->max_unlike_count)
                {
                    $query->where('unlike_count', "<=", (int)$request->max_unlike_count);
                }
            })
           ->comment($request->comment)
            ->status($request->status)
            ->user($request->user_id)
            ->article($request->article_id)
            ->paginate(5);;

        return view('admin.comments.list', compact('list', 'articles', 'users'));
    }
    public function create()
    {
        $users = User::all();
        $articles = Article::all();
        $comments = Comment::all();

        return view('admin.comments.create-update', compact('users', 'articles', 'comments'));
    }

    public function store(CommentStoreRequest $request)
    {
        try
        {
            Comment::create([
                'user_id' => random_int(1,10),
                'article_id' => $request->article_id,
                'parent_id' => $request->parent_id,
                'comments' => $request->comments,
                'status' => $request->status ? 1 : 0,
                'like_count' => $request->like_count,
                'unlike_count' => $request->unlike_count,
            ]);
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Başarılı', "Yorum Kaydedildi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route('comment.index');
    }

    public function changeStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $commentID = $request->commentID;

        $comment = Comment::query()->find($commentID);

        if ($comment)
        {
            $comment->status = $comment->status ? 0 : 1;
            $comment->save();

            return response()
                ->json(['status' => "success", "message" => "Başarılı", "data" => $comment, "comment_status" => $comment->status])
                ->setStatusCode(200);
        }
        return response()
            ->json(['status' => "error", "message" => "Yorum bulunamadı"])
            ->setStatusCode(404);
    }

    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        $commentID = $request->commentID;

        $comment = Comment::query()->find($commentID);

        if ($comment)
        {
            $comment->delete();

            return response()
                ->json(["status" => "success", "message"=>"Başarılı", "data"=>""])
                ->setStatusCode(200);
        }
        return response()
            ->json(["status" => "error", "message" => "Yorum Bulunamadı"])
            ->setStatusCode(404);
    }

    public function edit(Request $request, int $commentID)
    {

        $users = User::all();
        $articles = Article::all();
        $comments = Comment::all();

        $comment = Comment::query()->where('id', $commentID)->first();

        if (is_null($comment))
        {
            alert()->error('Hata', 'Yorum bulunamadı')->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
            return redirect()->back();
        }
        return view('admin.comments.create-update', compact('comment', 'users', 'articles', 'comments'));
    }

    public function update(CommentStoreRequest $request, int $commentID)
    {
        $comment = Comment::query()->find($commentID);

        try {
                $comment->user_id = random_int(1,10);
                $comment->article_id = $request->article_id;
                $comment->parent_id = $request->parent_id;
                $comment->comments = $request->comments;
                $comment->status = $request->status ? 1 : 0;
                $comment->like_count = $request->like_count;
                $comment->unlike_count = $request->unlike_count;

                $comment->save();
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }
        alert()->success('Başarılı', "Yorum Güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route('comment.index');
    }
}
