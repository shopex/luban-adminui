<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title>@yield('title') - {{$app_name}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">    
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <script src="{{ mix('/js/app.js') }}"></script>    
  </head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top" id="app-header">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <span class="navbar-brand">{{$app_name}}</span>
	    </div>
	    <div id="navbar" class="navbar-collapse collapse">
	      <ul class="nav navbar-nav hide-in-search">
	      @if (Auth::guest())
	      	@else

	      	@foreach ($app_menus as $menu)
	      	@if (isset($menu['items']) && count($menu['items']) > 0)
	        <li class="dropdown">
	          <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	          {{$menu['label']}} <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	          	@foreach ($menu['items'] as $item)
		          	@if (isset($item['label']))
			          	<li>
			          	<a href="{{ url($item['link']) }}">
			          	@if (isset($item['icon']))
			          	<i class="glyphicon {{ $item['icon'] }}"></i>
			          	@else
			          	<i style="display:inline-block;width:1em"></i>
			          	@endif
			          	{{$item['label']}}
			          	</a>
			          	</li>
		          	@else
			          	<li role="separator" class="divider"></li>
		          	@endif
	          	@endforeach
	          </ul>
	        </li>
	      	@else	        
			<li><a href="{{ url($menu['link']) }}">{{$menu['label']}}</a></li>	        
	        @endif
			@endforeach

	        @endif
	      </ul>

	      <ul class="nav navbar-nav navbar-right">
            @if (Auth::guest())
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @else
				<li class="dropdown">
				  <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="glyphicon glyphicon-th"></i>
				  </a>
				  <ul class="dropdown-menu app-sel">
				    <li>
					    <a href="#">
					    	<img src="https://git.shopex.cn/img/favicon.png" width="48px" />
					    	<div>CRM</div>
					    </a>
				    </li>
				    <li>
					    <a href="">
					    	<img src="https://git.shopex.cn/img/favicon.png" width="48px" />
					    	<div>CRM</div>
					    </a>
				    </li>
				    <li>
					    <a href="">
					    	<img src="https://git.shopex.cn/img/favicon.png" width="48px" />
					    	<div>CRM</div>
					    </a>
				    </li>
				    <li>
					    <a href="">
					    	<img src="https://git.shopex.cn/img/favicon.png" width="48px" />
					    	<div>CRM</div>
					    </a>
				    </li>
				  </ul>
				</li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>                
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                退出系统
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            @endif
	      </ul>
	      @if (false==Auth::guest())
	      @if ($searchbar)
		  	<searchbar :items="searchbar"></searchbar>
	      @endif
        @endif
	  </div>
	</nav>

    <div class="container main-content">
		@yield('content')
    </div>

    <script>
    var searchbar = {!! json_encode($searchbar) !!};
  	var app = new Vue({ 
  		el: '#app-header',
  		data: {
  			searchbar: searchbar
  		}
  	});    
    </script>
</html>