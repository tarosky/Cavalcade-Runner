<?php

const ACTUAL_FUNCTION = '/workspace/work/log/test_function_name.txt';
const JOB = 'test_job';
const JOB2 = 'test_job2';
const JOB_CHATTY = 'test_job_chatty';
const JOB_FAILED = 'test_job_failed';
const JOB_LONG = 'test_job_long';
const WPTEST_WPCLI_FIFO = '/workspace/work/log/wptest-wpcli.fifo';
const WPCLI_WPTEST_FIFO = '/workspace/work/log/wpcli-wptest.fifo';
const TEXT_124 = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n";

function go_wptest_blocking()
{
    file_put_contents(WPCLI_WPTEST_FIFO, "\n");
}

function wait_wptest_blocking()
{
    file_get_contents(WPTEST_WPCLI_FIFO);
}

if (defined('WP_CLI')) {
    WP_CLI::add_hook('before_invoke:cavalcade run', function () {
        go_wptest_blocking();
        wait_wptest_blocking();
    });
}

add_action(JOB, function ($func) {
    file_put_contents(ACTUAL_FUNCTION, $func);
});

add_action(JOB_LONG, function ($func) {
    sleep(10);
    file_put_contents(ACTUAL_FUNCTION, $func);
});

add_action(JOB_CHATTY, function () {
    for ($i = 0; $i < 20; $i++) {
        error_log(TEXT_124);
        echo TEXT_124;
        fwrite(STDERR, TEXT_124);
    }
});

add_action(JOB_FAILED, function ($func) {
    file_put_contents(ACTUAL_FUNCTION, $func);
    wp_die();
});

// Never register unrelated hooks to cron
add_filter('pre_schedule_event', function ($pre, $event, $wp_error = false) {
    if (substr($event->hook, 0, 5) !== 'test_') {
        return true;
    }
    return null;
}, 9, 3);
