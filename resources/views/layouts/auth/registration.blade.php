<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts/head')
    <body class="et_header_style_left">
        @guest
            <script src="//code.tidio.co/inycfezmgb8nzwjlbm7akfpl71tqq0hp.js"></script>

            <div id="app" class="registration-form page-container {{Request::path()}}">
                <main id="wrapper">

                    <header id="main-header" data-height-onload="86" data-height-loaded="true" data-fixed-height-onload="86">
                        <div class="container clearfix et_menu_container">
                            <div class="logo_container">
                                <span class="logo_helper"></span>
                                <a href="{{url('/')}}">
                                    <img src="{{ url('/images/logo-header.png') }}"  alt="Tenancy Stream" id="logo">
                                </a>
                            </div>
                            <div id="et-top-navigation" data-height="66" data-fixed-height="40">
                                <a class="registration-login" href="{{ route('login') }}">Log In</a>
                            </div> <!-- #et-top-navigation -->
                        </div> <!-- .container -->
                    </header>

                    <section id="section">
                        @yield('content')
                    </section>
                    @include('layouts/footer')
                </main>
            </div>

       @endguest
    </body>
</html>
