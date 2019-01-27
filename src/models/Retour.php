<?php

namespace distantnative;

class Retour
{

    protected $log;
    protected $redirects;
    protected $stats;
    protected $system;

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

}
