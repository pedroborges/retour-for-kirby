<?php

return [
    'route:before' => function ($route, $path) use ($retour) {
        $pattern   = $route->pattern();
        $redirects = $retour->redirects()->data();

        if (in_array($pattern, array_column($redirects, 'from')) === true) {
            $retour->redirects()->hit($pattern);
            $retour->log()->add($path, false);
            $retour->stats()->count(false);
        }
    },
    'route:after' => function ($route, $path, $method, $result) use ($retour) {
        if (empty($result) === true) {
            $retour->log()->add($path);
            $retour->stats()->count();
        }
    }
];
