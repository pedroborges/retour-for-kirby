<?php

namespace distantnative;

use Kirby\Toolkit\F;
use Kirby\Data\Yaml;

class Retour
{

    protected $log;
    protected $redirects;
    protected $stats;
    protected $system;

    public function flush(): void
    {
        $this->log()->write([]);
        $this->stats()->write([]);
    }

    public function load()
    {
        $file = kirby()->root('content') . '/retour.tmp';

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
        $file = kirby()->root('content') . '/retour.tmp';

        F::append($file, Yaml::encode([[
            'path'     => $path,
            'referrer' => $_SERVER['HTTP_REFERER'] ?? null,
            'isFail'   => $isFail,
            'pattern'  => $pattern,
            'date'     => date('Y-m-d H:i')
        ]]));
    }

}
