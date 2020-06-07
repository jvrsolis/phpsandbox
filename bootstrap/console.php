<?php

use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Console
{
    /**
     * Write a string as standard output.
     *
     * @param  string  $string
     * @param  string|null  $style
     * @return void
     */
    public static function line($string, $style = null)
    {
        $output = new ConsoleOutput;
        if ($style == 'warning' && !$output->getFormatter()->hasStyle('warning')) {
            $warningStyle = new OutputFormatterStyle('black', 'yellow');
            $output->getFormatter()->setStyle('warning', $warningStyle);
        }

        $styled = $style ? "<$style>$string</$style>" : $string;
        $output->writeln($styled);
    }

    /**
     * Write a string as information output.
     *
     * @param  string  $string
     * @return void
     */
    public static function info($string)
    {
        self::line($string, 'info');
    }

    /**
     * Write a string as error output.
     *
     * @param  string  $string
     * @return void
     */
    public static function error($string)
    {
        self::line($string, 'error');
    }

    /**
     * Write a string as comment output.
     *
     * @param  string  $string
     * @return void
     */
    public static function comment($string)
    {
        self::line($string, 'comment');
    }

    /**
     * Write a string as question output.
     *
     * @param  string  $string
     * @return void
     */
    public static function question($string)
    {
        self::line($string, 'question');
    }

    /**
     * Write a string as warning output.
     *
     * @param  string  $string
     * @return void
     */
    public static function warn($string)
    {
        self::line($string, 'warning');
    }

    /**
     * Write a string in an alert box.
     *
     * @param  string  $string
     * @return void
     */
    public static function alert($string)
    {
        $length = Str::length(strip_tags($string)) + 12;

        self::comment(str_repeat('*', $length));
        self::comment('*     ' . $string . '     *');
        self::comment(str_repeat('*', $length));
    }

    /**
     * Write a string in an alert box.
     *
     * @param  string  $string
     * @return void
     */
    public static function notice($string)
    {
        $length = Str::length(strip_tags($string)) + 12;

        self::info(str_repeat('*', $length));
        self::info('*     ' . $string . '     *');
        self::info(str_repeat('*', $length));
        self::info("\n");
    }

    /**
     * Format input to textual table.
     *
     * @param  array  $array
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $rows
     * @param  string  $tableStyle
     * @param  array  $columnStyles
     * @return \Symfony\Component\Console\Helper\Table
     */
    public static function table(array $array, int $dimension = 1, string $tableStyle = 'default', array $columnStyles = [])
    {
        $output = new ConsoleOutput;
        $table = new Table($output->section());

        if ($dimension == 1) {
            $headers = array_keys($array);
            $rows = [array_values($array)];
        } else {
            $firstKey = array_key_first($array);
            $headers = array_keys($array[$firstKey]);
            $rows = array_values($array);
        }

        $table->setHeaders($headers)->setRows($rows)->setStyle($tableStyle);

        foreach ($columnStyles as $columnIndex => $columnStyle) {
            $table->setColumnStyle($columnIndex, $columnStyle);
        }

        $table->render();

        return $table;
    }

    public static function output($value): void
    {
        dump($value);
    }
}
