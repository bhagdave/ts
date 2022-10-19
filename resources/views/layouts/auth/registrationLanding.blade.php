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
                                <a href="https://www.tenancystream.com/">
                                    <img src="https://www.tenancystream.com/wp-content/uploads/2020/09/TS-Full-Logo.png" alt="Tenancy Stream" id="logo">
                                </a>
                            </div>
                            <div id="et-top-navigation" data-height="66" data-fixed-height="40">
                                <a class="registration-login" href="https://www.tenancystream.app/login">Log In</a>
                            </div> <!-- #et-top-navigation -->
                        </div> <!-- .container -->
                    </header>

                    <section id="section" class="container">
                      <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                          <h1 class="text-center text-sm-left">Try out Tenancy Streams 30 Day free trial to change the way you stream &#8203;your communication</h1>
                        </div>
                        <div class="col-md-6">
                          <img class="img-fluid" src="{{ url('/images/All-Devices_1500px_superimpose-1024x683.png') }}" alt="">
                        </div>

                      </div>

                      <div class="row d-flex justify-content-center">
                        <div class="">
                          <p class="font-weight-bold">Start your Journey with Tenancy Stream. See how easy it is to communicate the proper way with our platform features:</p>

                          <div class="row">
                            <div class="col-md-6 mt-3">
                              <span class="oi oi-check"></span> Messaging Stream
                            </div>
                            <div class="col-md-6 mt-3">
                              <span class="oi oi-check"></span> Message Archive
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6 mt-3">
                              <span class="oi oi-check"></span> Your organisation communication
                            </div>
                            <div class="col-md-6 mt-3">
                              <span class="oi oi-check"></span> Search message history
                            </div>
                          </div>

                          <button class="button button--accent-bg" type="button" name="button">Try for free</button>
                        </div>

                      </div>

                    </section>

                    <footer id="footer">
                        <div id="top-footer">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-md-4 col-sm-12" id="contact-us">
                                        <img src="https://www.tenancystream.com/wp-content/uploads/2020/09/TS-Full-Logo-300x37.png" alt="Footer Logo" height="60">
                                        <div class="text-wrapper">
                                            <h4>CONTACT US: 0333 577 1095</h4>
                                            <p>Manage more properties.<br>Effortlesly</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <div class="text-wrapper">
                                            <h4>Useful Links</h4>
                                            <ul id="menu-menu" class="menu">
                                                <li class="menu-item"><a href="https://www.tenancystream.com/contact-us/" target="_blank">Contact Us</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-auto col-sm-12">
                                        <div class="text-wrapper">
                                            <h4>Web Links</h4>
                                            <ul id="menu-web-law" class="menu">
                                                <li class="menu-item"><a href="https://www.tenancystream.com/privacy-policy-gdpr/" target="_blank">Privacy Policy GDPR</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- container -->
                        </div>
                        <div id="bottom-footer">
                            <div class="container">
                                <p>© Copyrights 2020. All Rights Reserved.
                                    <span class="float-right">
                                        <a href="https://www.facebook.com/TenancyStream/"><i class="fa fa-facebook-f"></i></a>
                                        <a href="https://twitter.com/tenancystream?lang=en"><i class="fa fa-twitter"></i></a>
                                        <a href="https://www.linkedin.com/company/tenancy-stream/"><i class="fa fa-linkedin"></i></a>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </footer>
                </main>
            </div>

       @endguest
    </body>
</html>
