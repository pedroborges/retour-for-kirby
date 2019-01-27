<?php

namespace distantnative\Retour;

class Stats extends Store
{

    public function __construct()
    {
        $this->file = kirby()->root('content') . '/retour.stats';
    }

    protected static function defaults(): array
    {
        return [
            'day'   => [],
            'week'  => [],
            'month' => [],
        ];
    }

    public function count(bool $isFail = true): void
    {
        $data      = $this->data();
        $structure = [
            'day'   => ['group' => date('Y-m-d'), 'key' => date('Y-m-d H:')],
            'week'  => ['group' => date('Y-W'),   'key' => date('Y-m-d')],
            'month' => ['group' => date('Y-m'),   'key' => date('Y-m-d')]
        ];

        $type = $isFail ? 'fails' : 'redirects';

        foreach ($structure as $by => $id) {
            if (isset($data[$by][$id['group']]) === false) {
                $data[$by][$id['group']] = [];
            }

            if (isset($data[$by][$id['group']][$id['key']]) === false) {
                $data[$by][$id['group']][$id['key']] = [
                    'fails'     => 0,
                    'redirects' => 0
                ];
            }

            $data[$by][$id['group']][$id['key']][$type]++;
        }

        $this->write($data);
    }

    public function get(string $by, int $offset = 0): array
    {
        switch ($by) {
            case 'day':
                $step  = 60 * 60;
                $start = strtotime(date('Y-m-d 00:00') . $offset . ' day');
                $end   = strtotime(date('Y-m-d 23:59') . $offset . ' day');
                $group = date('Y-m-d', $start);
                $key   = 'Y-m-d H:';
                $label = 'G:00';
                break;

            case 'week':
                $step    = 60 * 60 * 24;
                $start   = strtotime(date('Y-m-d ', strtotime('last Monday')) . $offset . ' week');
                $end     = $start + ($step * 6);
                $group   = date('Y-W', $start);
                $key     = 'Y-m-d';
                $label   = 'l';
                break;

            case 'month':
                $step    = 60 * 60 * 24;
                $start   = strtotime(date('Y-m-01 ') . $offset . ' month');
                $end     = strtotime(date('Y-m-t', $start));
                $group   = date('Y-m', $start);
                $key     = 'Y-m-d';
                $label   = 'j';
                break;
        }

        $result = [
            'headline'  => static::headline($start, $end),
            'labels'    => [],
            'fails'     => [],
            'redirects' => [],
        ];

        for ($time = $start; $time <= $end; $time += $step) {
            $result['labels'][]    = date($label, $time);
            $result['fails'][]     = $this->data()[$by][$group][date($key, $time)]['fails'] ?? 0;
            $result['redirects'][] = $this->data()[$by][$group][date($key, $time)]['redirects'] ?? 0;
        }

        return $result;
    }

    protected function headline($start, $end): string
    {
        // whole day
        if (date('Y-m-d', $start) === date('Y-m-d', $end)) {
            return date('j F Y', $end);
        }

        // whole month
        if (
            date('Y-m', $start) === date('Y-m', $end) &&
            date('j', $start) === '1' &&
            date('j', $end) === date('t', $end)
        ) {
            return date('F Y', $end);
        }

        // days, same month
        if (date('m', $start) === date('m', $end)) {
            return date('j', $start) . '-' . date('j F Y', $end);
        }

        // detailed date
        if (date('Y', $start) === date('Y', $end)) {
           return date('j F', $start) . ' - ' . date('j F Y', $end);
        }

        return date('j F Y', $start) . ' - ' . date('j F Y', $end);
    }
}

