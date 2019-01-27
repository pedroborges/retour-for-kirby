<?php

namespace distantnative\Retour;

use Kirby\Toolkit\Collection;

class Redirects extends Store
{

    protected $retour;

    public function hit(string $pattern): void
    {
        $data = $this->data();
        $key  = array_search($pattern, array_column($data, 'from'));
        $data[$key]['hits'] = ($data[$key]['hits'] ?? 0) + 1;
        $data[$key]['last'] = date('Y-m-d H:i');
        $this->write($data);
    }

    public function read()
    {
        return $this->data = site()->retour()->yaml();
    }

    public function routes(): array
    {
        $data = array_filter($this->data(), function ($redirect) {
            return $redirect['status'] !== 'disabled';
        });

        return array_map(function($redirect) {
            return [
                'pattern' => $redirect['from'],
                'action'  => function () use ($redirect) {
                    go($redirect['to'], (int)$redirect['status']);
                }
            ];
        }, $data);
    }

    public function write(array $data = []): void
    {
        site()->update(['retour' => $data]);
    }

}
