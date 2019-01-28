<?php

namespace distantnative;

use Kirby\Data\Yaml;
use Kirby\Toolkit\Dir;
use Kirby\Toolkit\F;

class Retour
{

    protected $log;
    protected $redirects;
    protected $stats;
    protected $system;

    public function flush(): void
    {
        Dir::remove(kirby()->root('site') . '/logs/retour/');
    }

    public function load()
    {
        $file = kirby()->root('site') . '/logs/retour/.tmp';

        if ($tmp  = Yaml::decode(F::read($file))) {

            $this->log()->add($tmp);
            $this->stats()->count($tmp);

            $redirects = array_filter($tmp, function ($item) {
                $item['isFail'] === false;
            });

            $this->redirects()->hit($redirects);

            F::remove($file);
        }
    }

    public function log(): Retour\Log
    {
        if ($this->log) {
            return $this->log;
        }

        return $this->log = new Retour\Log;
    }

    public function redirects(): Retour\Redirects
    {
        if ($this->redirects) {
            return $this->redirects;
        }

        return $this->redirects = new Retour\Redirects;
    }

    public function stats(): Retour\Stats
    {
        if ($this->stats) {
            return $this->stats;
        }

        return $this->stats = new Retour\Stats;
    }

    public function system(): Retour\System
    {
        if ($this->system) {
            return $this->system;
        }

        return $this->system = new Retour\System;
    }

    public function tmp(string $path, bool $isFail, string $pattern = null)
    {
        $file = kirby()->root('site') . '/logs/retour/.tmp';

        F::append($file, Yaml::encode([[
            'path'     => $path,
            'referrer' => $_SERVER['HTTP_REFERER'] ?? null,
            'isFail'   => $isFail,
            'pattern'  => $pattern,
            'date'     => date('Y-m-d H:i')
        ]]));
    }

}
