<div class="app-sidebar">
    <div class="logo">
        <a href="/" class="logo-icon"><span class="logo-text">Neptune</span></a>
        <div class="sidebar-user-switcher user-activity-online">
            <a href="#">
                <img src="{{ asset('assets2/admin/images/avatars/avatar.png') }}">
                <span class="activity-indicator"></span>
                <span class="user-info-text">Chloe<br><span class="user-state-info">On a call</span></span>
            </a>
        </div>
    </div>
    <div class="app-menu">
        <ul class="accordion-menu">
            <li class="sidebar-title">
                Apps
            </li>
            <li class="active-page">
                <a href="{{ route('home') }}" class="{{ Route::is('home') ? 'active' :  '' }} ">
                    <i class="material-icons-two-tone">dashboard</i>Dashboard
                </a>
            </li>
            <li class="{{ Route::is("article.index") ||
                            Route::is("article.create") ||
                            Route::is("article.pending-approval") ||
                            Route::is("article.comment.list")
                            ? "open" : "" }}">
                <a href="#">
                    <i class="material-icons-two-tone">tune</i>
                    Makale Yönetimi
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('article.create') }}" class="{{ Route::is('article.create') ? 'active' : '' }}">Makale Ekle</a>
                    </li>
                    <li>
                        <a href="{{ route('article.index') }}" class="{{ Route::is('article.index') ? 'active' : '' }}">Makale Listesi</a>
                    </li>
                    <li>
                        <a href="{{ route('article.comment.list') }}" class="{{ Route::is('article.comment.list') ? 'active' : '' }}">Yorum Listesi</a>
                    </li>
                    <li>
                        <a href="{{ route('article.pending-approval') }}" class="{{ Route::is('article.pending-approval') ? 'active' : '' }}">Onay Bekleyen Yorumlar</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Route::is("category.index") || Route::is("category.create") ? "open" : "" }}">
                <a href="#">
                    <i class="material-icons-two-tone">tune</i>
                    Kategory Yönetimi
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('category.create') }}" class="{{ Route::is('category.create') ? 'active' : '' }}">Kategory Ekle</a>
                    </li>
                    <li>
                        <a href="{{ route('category.index') }}" class="{{ Route::is('category.index') ? 'active' : '' }}">Kategory Listesi</a>
                    </li>
                </ul>
            </li>
            <li class="{{ Route::is("user.index") || Route::is("user.create") ? "open" : "" }}">
                <a href="#">
                    <i class="material-icons-two-tone">person</i>
                    Kullanıcı Yönetimi
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('user.create') }}" class="{{ Route::is('user.create') ? 'active' : '' }}">Kullanıcı Oluştur</a>
                    </li>
                    <li>
                        <a href="{{ route('user.index') }}" class="{{ Route::is('user.index') ? 'active' : '' }}">Kullanıcı Listesi</a>
                    </li>
                </ul>
            </li>
            <li class="{{ Route::is("settings") ? "open" : "" }}">
                <a href="{{ route("settings") }}" >
                    <i class="material-icons-two-tone">settings</i>
                    Ayarlar
                </a>
            </li>
            <li class="{{ Route::is("dbLogs") ? "open" : "" }}">
                <a href="{{ route("dbLogs") }}" >
                    <i class="material-icons-two-tone">settings</i>
                    Log Yönetimi
                </a>
            </li>

{{--            <li class="{{ Route::is("comment.index") || Route::is("comment.create") ? "open" : "" }}">--}}
{{--                <a href="#">--}}
{{--                    <i class="material-icons-two-tone">tune</i>--}}
{{--                    Yorum Yönetimi--}}
{{--                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>--}}
{{--                </a>--}}
{{--                <ul class="sub-menu" style="">--}}
{{--                    <li>--}}
{{--                        <a href="{{ route('comment.create') }}" class="{{ Route::is('comment.create') ? 'active' : '' }}">Yorum Güncelle</a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="{{ route('comment.index') }}" class="{{ Route::is('comment.index') ? 'active' : '' }}">Yorum Listesi</a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--            <li class="{{ Route::is("social-media.index") || Route::is("social-media.create") ? "open" : "" }}">--}}
{{--                <a href="#">--}}
{{--                    <i class="material-icons-two-tone">tune</i>--}}
{{--                    Sosyal Medya Yönetimi--}}
{{--                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>--}}
{{--                </a>--}}
{{--                <ul class="sub-menu" style="">--}}
{{--                    <li>--}}
{{--                        <a href="{{ route('social-media.create') }}" class="{{ Route::is('social-media.create') ? 'active' : '' }}">Sosyal Medya Ekle</a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="{{ route('social-media.index') }}" class="{{ Route::is('social-media.index') ? 'active' : '' }}">Sosyal Medya Listesi</a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}


        </ul>
    </div>
</div>
