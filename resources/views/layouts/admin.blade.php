<!DOCTYPE html>
<html lang="en">

@section('htmlheader')
    @include('layouts.partials.htmlheader')
@show

<body class="skin-purple sidebar-mini">
<div id="app">
    <div class="wrapper">

    @include('layouts.partials.mainheader')

    @include('layouts.partials.admin-sidebar')

    <div class="content-wrapper">

        @include('layouts.partials.contentheader')
        <div class="row global-message">
            <div class="col-md-10 col-md-offset-1">
                    @include('layouts.partials.flash-message')
            </div>
        </div>
        <section class="content">
            @yield('main-content')
        </section>
    </div>

    @include('layouts.partials.footer')

</div>
</div>
@section('scripts')
    @include('layouts.partials.scripts')
@show

@yield('footerscripts')
</body>
</html>
