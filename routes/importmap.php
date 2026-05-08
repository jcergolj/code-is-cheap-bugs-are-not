<?php

use Tonysm\ImportmapLaravel\Facades\Importmap;

Importmap::pinAllFrom('resources/js', to: 'js/');
Importmap::pin('prismjs', to: '/js/vendor/prismjs.js'); // prismjs@1.30.0 downloaded from https://ga.jspm.io/npm:prismjs@1.30.0/prism.js
Importmap::pin('prismjs/plugins/line-highlight', to: '/js/vendor/prism-line-highlight.js'); // prismjs line-highlight plugin
Importmap::pin('laravel-echo', to: '/js/vendor/laravel-echo.js'); // laravel-echo@2.3.4 downloaded from https://ga.jspm.io/npm:laravel-echo@2.3.4/dist/echo.js
Importmap::pin('pusher-js', to: '/js/vendor/pusher-js.js'); // pusher-js@8.5.0 downloaded from https://ga.jspm.io/npm:pusher-js@8.5.0/dist/web/pusher.js
Importmap::pin('@hotwired/stimulus', to: '/js/vendor/@hotwired--stimulus.js'); // @hotwired/stimulus@3.2.2 downloaded from https://ga.jspm.io/npm:@hotwired/stimulus@3.2.2/dist/stimulus.js
Importmap::pin('@hotwired/turbo', to: '/js/vendor/@hotwired--turbo.js'); // @hotwired/turbo@8.0.23 downloaded from https://ga.jspm.io/npm:@hotwired/turbo@8.0.23/dist/turbo.es2017-esm.js
Importmap::pin('el-transition', to: '/js/vendor/el-transition.js'); // el-transition@0.0.7 downloaded from https://ga.jspm.io/npm:el-transition@0.0.7/index.js
