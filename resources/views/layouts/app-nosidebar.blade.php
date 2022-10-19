<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts/head')
    <body>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P2DCMKZ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <div class="bg-white border-bottom row fixed-top" id="fixed-header" data-turbolinks-permanent>
            <div class="container-fluid py-2">
              <div class="">
                  <div class="">
                    <a href="/">
                        <svg data-v-1084b650="" xmlns="http://www.w3.org/2000/svg" style="width: 40px;height: 30px;" viewBox="0 0 300 300"><!----> <!---->
                            <rect data-v-1084b650="" fill="#212529" fill-opacity="0.0" x="0" y="0" width="300px" height="300px" class="logo-background-square">
                            </rect> <!----> <!---->
                            <g data-v-1084b650="" id="aaf6eb69-4e66-8918-4040-caa36d392423" fill="#212529" stroke="none" transform="matrix(0.7999999999999998,0,0,0.7999999999999998,25.894802856445338,30.00000000000003)">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                                    <path d="M67.899,23.977l0.007-0.011L35.056,5L13.443,43.155l5.19,2.997v29.88l32.617,18.831l0.055,0.093l0.056-0.027  L51.484,95v-0.141l12.481-6.832V77.509l8.9,5.138l11.6-6.131V40.641l5.513-3.184L67.899,23.977z M83.791,40.251v35.874  l-10.925,5.759V58.207l-9.562,5.521v6.314h-0.01V87.64l-11.81,6.448V59.125l-5.197,2.997l21.28-37.557l21.09,12.876L83.791,40.251z">
                                    </path>
                                </svg>
                            </g>
                        </svg>
                    </a>
                    <button class="btn float-right" id="menu-toggle" onClick='$("#sidebar-wrapper").show();$("#page-content-wrapper").hide();$("#menu-toggle").hide();$("#menu-toggle-close").show();'>
                        <span class="oi oi-menu"></span>
                    </button>

                    <button class="btn float-right" id="menu-toggle-close" onClick=' $("#sidebar-wrapper").hide();$("#page-content-wrapper").show();$("#menu-toggle-close").hide();$("#menu-toggle").show();'>
                        <span class="oi oi-x"></span>
                    </button>
                  </div>
              </div>
          </div>
        </div>

        <div id="app" class="d-flex">

            <div class="bg-white border-right shadow-sm row vh-100" id="sidebar-wrapper" data-turbolinks-permanent>
                <div class="list-group list-group-flush align-self-start">
                    @include('layouts/sidebar-heading')
                </div>
            </div>
            <div id="page-content-wrapper" class="main overflow-auto pl-md-3 pt-2 vh-100">
                @if(isset($mainClassOff))
                    <main class="">
                @else
                    <main class="py-4">
                @endif
                @if(session()->has('message'))
                    <div class="container">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ session()->get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
