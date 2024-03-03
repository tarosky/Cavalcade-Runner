<?php

namespace HM\Cavalcade\Runner;

function init_healthcheck($option)
{
    global $ni_count, $maintenance_path;

    list($c, $maintenance_path) = explode(',', $option);
    $ni_count = intval($c);
}

function check_nicount()
{
    global $ni_count;

    $nis = net_get_interfaces();

    if ($ni_count === count($nis)) {
        return [true, null];
    }

    return [
        false,
        new HealthcheckFailure(
            'ni',
            'network interface count does not match',
            [
                'count' => $ni_count,
                'interfaces' => join(',', array_keys($nis)),
            ],
        ),
    ];
}

function check_no_maintenance()
{
    global $maintenance_path;

    if (file_exists($maintenance_path)) {
        return [
            false,
            new HealthcheckFailure(
                'maintenance',
                'maintenance mode is active',
                ['file' => $maintenance_path],
            ),
        ];
    }

    return [true, null];
}

function healthcheck()
{
    list($is_no_maintenance, $reason) = check_no_maintenance();
    if (!$is_no_maintenance) {
        return [false, $reason];
    }

    return check_nicount();
}
