<head>
    <title>Tenancy Stream</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <!-- Preloading -->
    <link rel="preload" href="serviceworker.js" as="script">
    <link rel="preload" href="https://js.pusher.com/4.4/pusher.min.js" as="script">
    <link rel="preload" href="css/app.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" as="style">
    <link rel="preload" href="https://tenancystream.com/logos/icon.png" as="image">
    <link rel="preload" href="//code.tidio.co/inycfezmgb8nzwjlbm7akfpl71tqq0hp.js" as="script">
    <link rel="preload" href="https://cdn.onesignal.com/sdks/OneSignalSDK.js" as="script">
    <link rel="preload" href="https://www.googletagmanager.com/gtag/js?id=UA-164168766-1" as="script">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/flatpickr" as="script">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" as="script">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WT6GRFM');</script>
    <!-- End Google Tag Manager -->

    <script src="{{ mix('js/app.js') }}" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js" ></script>

    <!-- Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" >
    <script src="https://cdn.jsdelivr.net/npm/flatpickr" defer></script>

    <script>
        $(function() {
            $(".date").flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "d-m-Y",
            });
        });
    </script>

    <!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '850168072125326');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=850168072125326&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->

    @laravelPWA
    @auth
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function onTidioChatApiReady() {
                window.tidioChatApi.hide();
                window.tidioChatApi.on("close", function() {
                    window.tidioChatApi.hide();
                });
            }

            if (window.tidioChatApi) {
                window.tidioChatApi.on("ready", onTidioChatApiReady);
            } else {
                document.addEventListener("tidioChat-ready", onTidioChatApiReady);
            }

            document.querySelector(".chat-button").addEventListener("click", function(e) {
                e.preventDefault();
                window.tidioChatApi.show();
                window.tidioChatApi.open();
            });

            document.tidioIdentify = {
                email: "{{$user->email}}", // User email
            };
        });
    </script>
    <script defer src="//code.tidio.co/inycfezmgb8nzwjlbm7akfpl71tqq0hp.js"></script>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
              appId: "e1813107-71e4-41e4-8deb-06b769340bec",
              safari_web_id: 'web.onesignal.auto.504512c8-952a-4d80-9d02-48e16e3cc659',
              autoResubscribe: true,
              notifyButton: {
                enable: true,
                displayPredicate: function() {
                    return OneSignal.isPushNotificationsEnabled()
                    .then(function(isPushEnabled) {
                        return !isPushEnabled;
                    });
                },
                size: 'small',
                colors: { // Customize the colors of the main button and dialog popup button
                    'circle.background': 'rgb(84,110,123)',
                    'circle.foreground': 'white',
                    'badge.background': 'rgb(84,110,123)',
                    'badge.foreground': 'white',
                    'badge.bordercolor': 'white',
                    'pulse.color': 'white',
                    'dialog.button.background.hovering': 'rgb(77, 101, 113)',
                    'dialog.button.background.active': 'rgb(70, 92, 103)',
                    'dialog.button.background': 'rgb(84,110,123)',
                    'dialog.button.foreground': 'white'
                },
                showCredit: false,
                prenotify: true, /* Show an icon with 1 unread message for first-time site visitors */
              },
              welcomeNotification: {
                disable: true
              },
            });
        });

        let tsUserID = "{{$user->sub}}";

        OneSignal.push(function() {
            OneSignal.removeExternalUserId();
            OneSignal.setExternalUserId(tsUserID);
        });

    </script>
    @endauth

    <!-- Styles -->
    <link rel="icon" type="image/png" href="https://tenancystream.com/wp-content/uploads/2020/09/TS-Wave-Icon-150x148.png">
    <link rel="apple-touch-icon" href="https://tenancystream.com/logos/icon.png">
    <link rel="stylesheet" href="https://tenancystream.com/css/header.css">
    <link rel="stylesheet" href="https://tenancystream.com/css/main.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" data-turbolinks-track="true">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css"  rel="stylesheet" data-turbolinks-track="true">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet" data-turbolinks-track="true">
    @stack("authStyles")
    <script src="https://js.pusher.com/4.4/pusher.min.js" defer></script>
</head>
