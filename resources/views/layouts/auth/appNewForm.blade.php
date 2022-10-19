<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts/head')
    <body class="et_header_style_left">
        @guest
        <script src="//code.tidio.co/inycfezmgb8nzwjlbm7akfpl71tqq0hp.js"></script>

        <div id="app" class="registration-form page-container {{Request::path()}}">
            <main id="wrapper">
                <div id="top-header">
                    <div class="container clearfix">

                        <div class="top-header__left">
                            <div id="text-2" class="widget widget_text">
                                <div class="textwidget"><p><span class="thin">
                                    <a href="/register" target="_blank" rel="noopener noreferrer"><u>Sign Up</u></a></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="top-header__right">
                            <div id="text-3" class="widget widget_text">
                                <div class="textwidget">
                                    <p><strong>CONTACT US</strong> <a class="thin" href="tel:01423575353">01423 575353</a></p>
                                </div>
                            </div>
                            <div class="top-header__social-icons">
                                <a href="https://www.facebook.com/TenancyStream/" target="_blank"><i class="fa fa-facebook-f"></i></a>
                                <a href="https://twitter.com/tenancystream?lang=en"><i class="fa fa-twitter"></i></a>
                                <a href="https://www.linkedin.com/company/tenancy-stream/"><i class="fa fa-linkedin" target="_blank"></i></a>
                            </div>
                        </div>

                    </div> <!-- .container -->
                </div>


                <header id="main-header" data-height-onload="86" data-height-loaded="true" data-fixed-height-onload="86">
                    <div class="container clearfix et_menu_container">
                        <div class="logo_container">
                            <span class="logo_helper"></span>
                            <a href="{{url('/')}}">
                                <img src="{{ url('/images/logo-header.png') }}" class="w-25" alt="Tenancy Stream" id="logo">
                            </a>
                        </div>

                        <div id="et-top-navigation" data-height="66" data-fixed-height="40" style="padding-left: 218px;">


                            <nav id="top-menu-nav">
                              <ul id="top-menu" class="nav">
                                <li id="menu-item-244" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-244"><a href="/" aria-current="page">Home</a></li>
                                <li id="menu-item-58" class="navbar-btn signup menu-item menu-item-type-custom menu-item-object-custom menu-item-58"><a href="{{ route('register') }}">Sign Up</a></li>
                              </ul>
                            </nav>



                            <div id="et_mobile_nav_menu">
				                      <div class="mobile_nav closed">
                      					<span class="select_page">Select Page</span>
                      					<span class="mobile_menu_bar mobile_menu_bar_toggle"></span>
				                        <ul id="mobile_menu" class="et_mobile_menu">
                                  <li id="menu-item-244" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-244 et_first_mobile_item"><a href="/" aria-current="page">Home</a></li>
                                  <li id="menu-item-58" class="navbar-btn signup menu-item menu-item-type-custom menu-item-object-custom menu-item-58"><a href="{{ route('register') }}">Sign Up</a></li>
                                  </ul>
                                </div>
			                        </div>

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
