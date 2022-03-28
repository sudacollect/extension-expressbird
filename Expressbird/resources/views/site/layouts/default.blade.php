<!DOCTYPE html>
<html lang="zh-CN">
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="zhila, hello@ecdo.co">
    
    <title>{{ metas($sdcore) }}</title>
    <meta name="keywords" itemprop="keywords" content="{{ $sdcore->keywords }}">
    <meta name="description" property="og:description" itemprop="description" content="{{ $sdcore->description }}">
    
    <link rel="shortcut icon" href="{{ zest_asset('images/logo_fav.png') }}" type="image/x-icon">
    <meta property="og:site_name" content="{{ $sdcore->settings->site_name }}">
    <meta property="og:title" itemprop="name" content="{{ metas($sdcore->title) }}">
    <meta property="og:image" itemprop="image" content="{{ $sdcore->settings->og_image }}">
    
    <link rel="stylesheet" href="{{ zest_asset('css/app_site.css') }}">
    
    @sitehead
    
    @stack('styles')
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.zhila = window.zhila || {};
        zhila.meta = { csrfToken: "{{csrf_token()}}",url:"{{url('/')}}" }
    </script>
    
    @stack('scripts-head')
    
</head>
<body>
   
    <div id="app" class="app-zhilapress">
        
        <nav class="navbar navbar-site navbar-static-top zhila-navbar">
            
            <div class="container container-flex">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" title="{{ $sdcore->settings->site_name }}" href="{{ url('/home') }}" style="border-right:none; @if(isset($logo)) background:url({{ passet($logo) }}) no-repeat center center;background-size:contain; @endif">
                        
                    </a>
                </div>

                <div class="collapse navbar-collapse navbar-right" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li>
                            <a id="menu-price" href="{{ url('/') }}">演示应用首页</a>
                        </li>
                        
                    </ul>
                    
                </div>
                
                
            </div>
            
        </nav>

        @yield('content')
        
        @include('view_zest::site.layouts.footer')
        
        @zhilapower
    </div>
    
    
    <!-- Scripts -->
    <script src="{{ zest_asset('js/app_site.js') }}"></script>
    
    @stack('scripts')
    

    <script>
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?6a9fa8413da128ff64350caee0feecdb";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    </script>

</body>
</html>
