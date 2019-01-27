<?php

namespace distantnative\Retour;

class Log extends Store
{

    public function __construct()
    {
        $this->file = kirby()->root('content') . '/retour.log';
    }

    public function add(string $path, bool $isFail = true): void
    {
        $data = $this->data();
        $id   = $path . '$' . ($_SERVER['HTTP_REFERER'] ?? null);

        if (isset($data[$id]) === false) {
            $data[$id] = [
                'path'      => $path,
                'referrer'  => $_SERVER['HTTP_REFERER'] ?? null,
                'fails'     => 0,
                'redirects' => 0,
                'last'      => null
            ];
        }

        $data[$id][$isFail ? 'fails' : 'redirects']++;
        $data[$id]['last'] = date('Y-m-d H:i');

        $this->write($data);
    }

    public function fails(string $sort = 'fails'): array
    {
        // remove redirect-only logs
        $data = array_filter($this->data(), function ($log) {
            return ($log['fails'] ?? 0) !== 0;
        });

        // sort accordingly
        usort($data, function ($log1, $log2) use ($sort) {
            return $log2[$sort] <=> $log1[$sort];
        });

        return $data;
    }

}
