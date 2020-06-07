<?php

require 'vendor/autoload.php';

function exception_handler($exception)
{
    Console::error($exception->getMessage());
}

function error_handler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return false;
    }

    switch ($errno) {
        case E_USER_ERROR:
            Console::error("FATAL ERROR: [$errno] $errstr. Line $errline in file $errfile");
            exit(1);
            break;

        case E_USER_WARNING:
            Console::warn("WARNING: [$errno] $errstr");
            break;

        case E_USER_NOTICE:
            Console::question("NOTICE: [$errno] $errstr");
            break;

        default:
            Console::error("UNKNOWN: [$errno] $errstr");
            break;
    }

    return true;
}

set_error_handler("error_handler");
set_exception_handler('exception_handler');
