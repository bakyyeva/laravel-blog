@extends('layouts.front')

@section('title')
@endsection

@section('css')
@endsection

@section('content')
    <section class="row">
        <div class="col-12 bg-white rounded-1 shadow-sm">
            <div class="article-wrapper">
                <div class="article-header font-lato d-flex justify-content-between pb-4">
                    <div class="article-header-date">
                        @php
                            $tags = $article->getAttribute("tagsToArray");
                            if (!is_null($tags) && count($tags))
                                {
                                   for($i=1; $i<=count($tags); $i++)
                                     {
                                        $class = ["text-danger", "text-success", "text-warning", "text-secondary", "text-primary"];
                                        $randomClass = $class[random_int(0,4)];
                                     }
                                }
                        @endphp
                        <time datetime="{{ $article->format_publish_date }}">{{ $article->format_publish_date }}</time>
                        @if(!is_null($tags) && count($tags))
                            @foreach($article->getAttribute("tagsToArray") as $tag)
                                <a href="{{ route('front.search', ['q' => $tag]) }}">
                                    <span class="{{ $randomClass }}">{{ $tag }}</span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <div class="article-header-author">
                        Yazar:
                        <a href="{{ route('front.authorArticles', ['user' => $article->user->username]) }}">
                            <strong>{{ $article->user->name }}</strong>
                        </a> <br>
                        Kategory:
                        <a href="{{ route('front.categoryArticles', ['category' => $article->category->slug]) }}" class="category-link">
                            {{ $article->category->name }}
                        </a>
                    </div>
                </div>
                <div class="article-content mt-4">
                    <h1 class="fw-bold mb-4">
                        {{ $article->title }}
                    </h1>
                    <div class="d-flex justify-content-center">
                        <img src="{{ imageExist($article->image, $settings->article_default_image) }}" class="img-fluid w-75 rounded-1">
                    </div>
                    <div class="text-secondary mt-5">
                        {!! $article->body !!}
                    </div>
                </div>
            </div>

        </div>

        <section class="col-12 mt-4">
            <div class="article-items d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="javascript:void(0)"
                       class="favorite-article me-1"
                       id="favoriteArticle"
                       data-id="{{ $article->id }}"
                        @if(!is_null($userLike))
                           style="color:red"
                        @endif
                    >
                        <span class="material-icons-outlined">favorite</span>
                    </a>
                    <span class="fw-light" id="favoriteCount">{{ $article->like_count }}</span>
                </div>
                <a href="javascript:void(0)" class="btn-response btnArticleResponse">Yorum Yap</a>

            </div>

            <div class="article-authors mt-5">
                <div class="bg-white p-4 d-flex justify-content-between align-items-center shadow-sm">
                    <a href="{{ route('front.authorArticles', ['user' => $article->user->username])  }}">
                        <img src="{{ imageExist($article->user->image, $settings->default_comment_profile_image) }}" alt="" width="75" height="75">
                    </a>
                    <div class="px-5 me-auto">
                        <h4 class="mt-3">
                            <a href="{{ route('front.authorArticles', ['user' => $article->user->username])  }}">
                                {{ $article->user->name }}
                            </a></h4>
                        {!! $article->user->about !!}
                    </div>
                </div>
            </div>

            @if(isset($suggestArticles) && count($suggestArticles))
            <div class="mt-5">
                <div class="swiper-suggest-article mt-3">
                    <div class="swiper-wrapper">
                        @foreach($suggestArticles as $suggestArticle)
                            <div class="swiper-slide">
                                <a href="{{ route('front.articleDetail', [
                                'user' => $suggestArticle->user,
                                'article' => $suggestArticle->slug
                                ]) }}">
                                    <img src="{{ imageExist($suggestArticle->image, $settings->article_default_image) }}" class="img-fluid">
                                </a>

                                <div class="most-popular-body mt-2">
                                    <div class="most-popular-author d-flex justify-content-between">
                                        <div>
                                            Yazar:
                                            <a href="{{ route('front.authorArticles', ['user' => $suggestArticle->user->username]) }}">
                                                {{ $suggestArticle->user->name }}
                                            </a>
                                        </div>
                                        <div class="text-end">Kategori:
                                            <a href="{{ route('front.categoryArticles', ['category' => $suggestArticle->category->slug]) }}">
                                                {{ $suggestArticle->category->name }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="most-popular-title">
                                        <h4 class="text-black">
                                            <a href="{{ route('front.articleDetail', [
                                                    'user' => $suggestArticle->user,
                                                    'article' => $suggestArticle->slug
                                                    ]) }}">
                                                {{ $suggestArticle->title }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="most-popular-date">
                                        <span>{{ $suggestArticle->format_publish_date }}</span> &#x25CF; <span>10 dk</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </section>

        <section class="article-responses mt-4">
            <div class="response-form bg-white shadow-sm rounded-1 p-4 d-none" id="newComment">
                <form action="{{ route('articleComment', ['article' => $article->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" id="comment_parent_id" value="{{ null }}">
                    <div class="row">
                        <div class="col-12">
                            <h5>Yorumunuz</h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Adınız" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" placeholder="Email Adresi" name="email" required>
                        </div>
                        <div class="col-12 mt-3">
                            <textarea name="comments" id="message" cols="30" rows="5" class="form-control" placeholder="Mesajınız"></textarea>
                        </div>
                        <div class="col-md-4">
                            <button class="btn-response align-items-center d-flex mt-3">
                                <span class="material-icons-outlined me-2">send</span>
                                Gönder
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="response-body p-4">
                <h3>Yorumlar</h3>
                <hr class="mb-4">
                @if($article->comments->count() < 1)
                    <div class="alert alert-info">
                        Henüz yorum yapılmamıştır.
                    </div>
                @endif

                @foreach($article->comments as $comment)
                    <div class="article-response-wrapper">
                        <div class="article-response bg-white p-2 mt-3 d-flex justify-content-between align-items-center shadow-sm">
                            <div class="col-md-2">
                                @php
                                    if ($comment->user)
                                    {
                                        $name = $comment->user->name;
                                    }
                                    else
                                    {
                                        $name = $comment->name;
                                    }
                                @endphp
                                <img src="{{ imageExist($comment->user?->image, $settings->default_comment_profile_image) }}" alt="" width="75" height="75">
                            </div>
                            <div class="col-md-10">
                                <div class="px-3">
                                    <div class="comment-title-date d-flex justify-content-between">
                                        <h4><a href=""> {{ $name }} </a></h4>
                                        <time datetime="{{ $comment->created_at }}">{{ $comment->created_at }}</time>
                                    </div>
                                    <p class="text-secondary">{{ $comment->comments }}</p>
                                    <div class="text-end d-flex  align-items-center justify-content-between">
                                        <div>
                                            <a href="javascript:void(0)"
                                               class="btn-response btnArticleResponseComment"
                                               data-id="{{ $comment->id }}">
                                                Yorum Yap
                                            </a>
                                        </div>
                                        <div class="d-flex  align-items-center">
                                            @php
                                                $commentLike = $comment->commentLikes->where('user_id', auth()->id())->where('comment_id', $comment->id)->first();
                                            @endphp
                                            <a href="javascript:void(0)"
                                               class="like-comment"
                                               data-id="{{ $comment->id }}"
                                               @if(!is_null($commentLike))
                                                   style="color: orange";
                                                @endif
                                            >
                                                <span class="material-icons">thumb_up</span>
                                            </a>
                                             <span id="commentLikeCount-{{ $comment->id }}">{{ $comment->like_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($comment->children)
                            <div class="articles-response-comment-wrapper">
                                @foreach($comment->children as $child)
                                    <div class="article-comment bg-white p-2 mt-3 d-flex justify-content-between align-items-center shadow-sm">
                                        <div class="col-md-2">
                                            @php
                                                if ($child->user)
                                                {
                                                    $childName = $child->user->name;
                                                }
                                                else
                                                {
                                                    $childName = $child->name;
                                                }
                                            @endphp
                                            <img src="{{ imageExist($child->user?->image, $settings->default_comment_profile_image) }}" alt="" width="75" height="75">
                                        </div>
                                        <div class="col-md-10">
                                            <div class="px-3">
                                                <div class="comment-title-date d-flex justify-content-between">
                                                    <h4><a href=""> {{ $childName }} </a></h4>
                                                    <time datetime="{{ $child->created_at }}">{{ $child->created_at }}</time>
                                                </div>
                                                <p class="text-secondary">{{ $child->comments }}</p>
                                                <div class="text-end d-flex  align-items-center justify-content-between">
                                                    <div>
                                                        <a href="javascript:void(0)" class="btn-response btnArticleResponseComment" data-id="{{ $child->id }}">Cevap Ver</a>
                                                    </div>
                                                    <div class="d-flex  align-items-center">
                                                        @php
                                                            $commentLikeChild = $child->commentLikes->where('user_id', auth()->id())->where('comment_id', $child->id)->first();
                                                        @endphp
                                                        <a href="javascript:void(0)"
                                                           class="like-comment"
                                                           data-id="{{ $child->id }}"
                                                           @if(!is_null($commentLikeChild))
                                                               style="color: orange";
                                                            @endif
                                                        >
                                                            <span class="material-icons">thumb_up</span>
                                                        </a>
                                                        <span id="commentLikeCount-{{ $child->id }}">{{ $child->like_count }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif


                    </div>
                @endforeach
            </div>
        </section>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function () {

            $('#favoriteArticle').click(function () {

                @if(Auth::check())
                    let articleID = $(this).data('id');
                    let self = $(this);
                    $.ajax({
                        method: "POST",
                        url: "{{ route('article.favorite') }}",
                        data: {
                            id : articleID
                        },
                        async:false,
                        success: function (data) {
                            if(data.process)
                            {
                                self.css("color", "red")
                            }
                            else
                            {
                                self.css("color", "inherit")
                            }
                            $('#favoriteCount').text(data.like_count)
                        },
                        error: function (){
                            console.log("hata geldi");
                        }
                    })
                @else
                    Swal.fire({
                        title: "Bilgi",
                        text: "Kullanıcı girişi yapmadan favorilerinize alamazsınız.",
                        confirmButtonText: 'Tamam',
                        icon: "info"
                    });
                @endif


            });

            $('.like-comment').click(function () {

                @if(Auth::check())
                    let commentID = $(this).data('id');
                    let self = $(this);

                    $.ajax({
                        method: "POST",
                        url: "{{ route('article.comment.favorite') }}",
                        data: {
                            id : commentID
                        },
                        async:false,
                        success: function (data) {
                            if(data.process)
                            {
                                self.css("color", "red")
                            }
                            else
                            {
                                self.css("color", "inherit")
                            }
                            $('#commentLikeCount-' + commentID).text(data.like_count)
                        },
                        error: function (){
                            console.log("hata geldi");
                        }
                    })
                @else
                    Swal.fire({
                        title: "Bilgi",
                        text: "Kullanıcı girişi yapmadan yorumu beğenemezsınız.",
                        confirmButtonText: 'Tamam',
                        icon: "info"
                    });
                @endif
            });


            $('.btnArticleResponse').click(function ()
            {
                // $('.response-form').toggle();
                let responseForm = $('.response-form');

                if(responseForm.hasClass('d-none'))
                {
                    responseForm.removeClass('d-none');
                    responseForm.addClass('d-block');
                }

                $('html, body').animate({
                    scrollTop: $('#newComment').offset().top
                }, 50)
            });

            $('.btnArticleResponseComment').click(function ()
            {
                let commentID = $(this).data("id");
                $("#comment_parent_id").val(commentID);

                // $('.response-form').toggle();

                let responseForm = $('.response-form');

                if(responseForm.hasClass('d-none'))
                {
                    responseForm.removeClass('d-none');
                    responseForm.addClass('d-block');
                }

                $('html, body').animate({
                    scrollTop: $('#newComment').offset().top
                }, 50)
            });

        });
    </script>
@endsection
