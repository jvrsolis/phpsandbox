<?php

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

if (!function_exists('array_seek_recursive')) {
    /**
     * Get an item from a multidimensional array.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @return mixed
     */
    function array_seek_recursive($haystack, $needle)
    {
        foreach ($haystack as $key => $value) {
            if ($key == $needle) {
                return $value;
            } elseif (is_array($value)) {
                $output = array_seek_recursive($value, $needle);
                if (!is_null($output)) {
                    return $output;
                }
            }
        }
        return null;
    }
}

if (!function_exists('array_dot_seek')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_seek($haystack, $needle, $default = null)
    {
        /* If ArrayAccessible, retrieve item accordingly */
        if (!array_accessible($haystack)) {
            return value($default);
        }

        /* If null, return array */
        if (is_null($needle)) {
            return $haystack;
        }

        /* If needle is key in singlular array or the topmost layer of a multidimenstional array, return value */
        if (array_key($haystack, $needle)) {
            return $haystack[$needle];
        }

        /* Itterate through the dot notation destination on the array and return value. */
        foreach (explode('.', $needle) as $segment) {
            if (array_accessible($haystack) && array_key($haystack, $segment)) {
                $haystack = $haystack[$segment];
            } else {
                return value($default);
            }
        }

        /* Return array */
        return $haystack;
    }
}


if (!function_exists('array_find')) {
    /**
     * Get a key from a single dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @return mixed
     */
    function array_find($haystack, $needle)
    {
        foreach ($haystack as $key => $value) {
            if ($needle == $value) {
                return $key;
            }
        }

        return null;
    }
}

if (!function_exists('array_find_all')) {
    /**
     * Get all keys from a single dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @param  array   $output
     * @return array   $output
     */
    function array_find_all($haystack, $needle)
    {
        $result = [];

        foreach ($haystack as $key => $value) {
            if ($needle == $value) {
                array_push($result, $key);
            }
        }

        return !empty($result) ? $result : [];
    }
}

if (!function_exists('array_find_nth')) {
    /**
     * Get the key from a single dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @param  int     $nth
     * @return mixed
     */
    function array_find_nth($haystack, $needle, $nth = 1)
    {
        $result = [];

        foreach ($haystack as $key => $value) {
            if ($needle == $value) {
                array_push($result, $key);
            }
        }

        return empty($result) && !isset($result[$nth - 1]) ? $result : $result[$nth - 1];
    }
}

if (!function_exists('array_find_first')) {
    /**
     * Get the key from a single dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @param  int     $nth
     * @return mixed
     */
    function array_find_first($haystack, $needle)
    {
        $result = [];
        $nth    = 1;

        foreach ($haystack as $key => $value) {
            if ($needle == $value) {
                array_push($result, $key);
            }

            if (count($result) == $nth) {
                break;
            }
        }

        return empty($result) && !isset($result[$nth - 1]) ? $result : $result[$nth - 1];
    }
}

if (!function_exists('array_find_last')) {
    /**
     * Get the key from a single dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @param  int     $nth
     * @return mixed
     */
    function array_find_last($haystack, $needle)
    {
        $result = [];

        foreach ($haystack as $key => $value) {
            if ($needle == $value) {
                array_push($result, $key);
            }
        }

        $nth = count($result);

        return empty($result) && !array_key_exists($result, $nth - 1) ? $result : $result[$nth - 1];
    }
}

if (!function_exists('array_find_in_column')) {
    function array_find_in_column($array, $value, $columnName)
    {
        $keys   = array_keys($array);
        $column = array_column($array, $columnName);
        $key    = array_search($value, $column);

        return $array[$keys[$key]];
    }
}

if (!function_exists('array_find_nth_in_column')) {
    function array_find_nth_in_column($array, $value, $columnName, $nth)
    {
        $keys   = array_keys($array);
        $column = array_column($array, $columnName);
        $count  = 0;

        foreach ($column as $key => $value) {
            $result = array_search($value, $column);
            if ($count == $nth) {
                $column = array_slice($column, $result, null, true);
                $count += 1;
                break;
            }
        }

        return $array[$keys[$result]];
    }
}

if (!function_exists('array_find_recursive')) {
    /**
     * Get the key from a multidimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @return mixed
     */
    function array_find_recursive($haystack, $needle)
    {
        foreach ($haystack as $key => $value) {
            if ($value == $needle) {
                return $key;
            } elseif (is_array($value)) {
                $result = array_find_recursive($value, $needle);
                if (!is_null($result)) {
                    return $result;
                }
            }
        }
        return null;
    }
}

if (!function_exists('array_find_all_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @return mixed
     */
    function array_find_all_recursive($haystack, $needle)
    {
        $output = [];

        foreach ($haystack as $key => $value) {
            if ($value == $needle) {
                array_push($output, $key);
            } elseif (is_array($value)) {
                $result = array_find_all_recursive($value, $needle);

                if (!empty($result)) {
                    array_push($output, ...$result);
                }
            }
        }

        return empty($output) ? [] : $output;
    }
}

if (!function_exists('array_find_nth_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_find_nth_recursive($haystack, $needle, $nth = 1)
    {
        $count  = 0;
        $output = [];

        if (is_array($haystack) && count($haystack) > 0) {
            foreach ($haystack as $key => $value) {
                if ($value == $needle) {
                    array_push($output, $key);
                } elseif (is_array($value)) {
                    $result = array_find_nth_recursive($value, $needle, $nth);
                    if (!empty($result)) {
                        array_push($output, ...$result);
                    }
                }
                $count = !empty($output) ? count($output) : 0;
                if ($count >= $nth) {
                    break;
                }
            }
        }

        return empty($output) && !isset($output[$nth - 1]) ? $output : $output[$nth - 1];
    }
}

if (!function_exists('array_find_first_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_find_first_recursive($haystack, $needle)
    {
        $count  = 0;
        $nth    = 1;
        $output = [];

        if (is_array($haystack) && count($haystack) > 0) {
            foreach ($haystack as $key => $value) {
                if ($value == $needle) {
                    array_push($output, $key);
                } elseif (is_array($value)) {
                    $result = array_find_nth_recursive($value, $needle);
                    if (!empty($result)) {
                        array_push($output, ...$result);
                    }
                }
                $count = !empty($output) ? count($output) : 0;
                if ($count >= $nth) {
                    break;
                }
            }
        }

        return empty($output) && !isset($output[$nth - 1]) ? $output : $output[$nth - 1];
    }
}

if (!function_exists('array_find_last_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_find_last_recursive($haystack, $needle)
    {
        $output = [];

        if (is_array($haystack) && count($haystack) > 0) {
            foreach ($haystack as $key => $value) {
                if ($value == $needle) {
                    array_push($output, $key);
                } elseif (is_array($value)) {
                    $result = array_find_last_recursive($value, $needle);
                    if (!empty($result)) {
                        array_push($output, ...$result);
                    }
                }
            }
        }

        $nth = count($output);

        return empty($output) && !isset($output[$nth - 1]) ? $output : $output[$nth - 1];
    }
}

if (!function_exists('array_dot_find')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for a given value.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_find($haystack, $needle)
    {
        $dotted = array_dot($haystack);
        foreach ($dotted as $key => $value) {
            if ($needle == $value) {
                return $key;
            }
        }
        return null;
    }
}

if (!function_exists('array_dot_find_nth')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @param  int     $nth
     * @return mixed
     */
    function array_dot_find_nth($haystack, $needle, $nth = 1)
    {
        $dotted = array_dot($haystack);
        $result = [];
        foreach ($dotted as $key => $value) {
            if ($needle == $value) {
                array_push($result, $key);
            }
        }

        return empty($result) && !isset($result[$nth - 1]) ? $result : $result[$nth - 1];
    }
}

if (!function_exists('array_dot_find_first')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for the first instance of a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @return mixed
     */
    function array_dot_find_first($haystack, $needle, $depth)
    {
        $nth    = 1;
        $dotted = array_dot($haystack);
        $result = [];
        foreach ($dotted as $key => $value) {
            if ($needle == $value) {
                array_push($result, $key);
            }
        }

        return empty($result) && !isset($result[$nth - 1]) ? $result : $result[$nth - 1];
    }
}

if (!function_exists('array_dot_find_first_at_depth')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for the first instance of a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @return mixed
     */
    function array_dot_find_first_at_depth($haystack, $needle, $depth = 1)
    {
        $nth    = 1;
        $dotted = array_dot($haystack);
        $result = [];
        foreach ($dotted as $key => $value) {
            $dotDepth = array_dot_depth($key);
            if ($needle == $value && $dotDepth == $depth) {
                array_push($result, $key);
            }
        }

        return empty($result) && !isset($result[$nth - 1]) ? $result : $result[$nth - 1];
    }
}

if (!function_exists('array_dot_find_last')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for the last instance of a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @return mixed
     */
    function array_dot_find_last($haystack, $needle)
    {
        $nth    = 1;
        $dotted = array_dot($haystack);
        $result = [];
        foreach ($dotted as $key => $value) {
            if ($needle == $value) {
                array_push($result, $key);
            }
        }
        $nth = count($result);
        return empty($result) && !isset($result[$nth - 1]) ? $result : $result[$nth - 1];
    }
}

if (!function_exists('array_dot_find_last_at_depth')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for the last instance of a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @return mixed
     */
    function array_dot_find_last_at_depth($haystack, $needle, $depth = 1)
    {
        $nth    = 1;
        $dotted = array_dot($haystack);
        $result = [];
        foreach ($dotted as $key => $value) {
            $dotDepth = array_dot_depth($key);
            if ($needle == $value && $dotDepth == $depth) {
                array_push($result, $key);
            }
        }
        $nth = count($result);
        return empty($result) && !isset($result[$nth - 1]) ? $result : $result[$nth - 1];
    }
}

if (!function_exists('array_dot_find_all')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for the all instances of a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @return mixed
     */
    function array_dot_find_all($haystack, $needle)
    {
        $dotted = array_dot($haystack);
        $result = [];
        foreach ($dotted as $key => $value) {
            if ($needle == $value) {
                array_push($result, $key);
            }
        }
        return $result;
    }
}

if (!function_exists('array_dot_find_all_at_depth')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for the all instances of a given value.
     *
     * @param  array   $haystack
     * @param  string  $needle
     * @return mixed
     */
    function array_dot_find_all_at_depth($haystack, $needle, $depth = 1)
    {
        $dotted = array_dot($haystack);
        $result = [];

        foreach ($dotted as $key => $value) {
            $dotDepth = array_dot_depth($key);
            if ($needle == $value && $dotDepth == $depth) {
                array_push($result, $key);
            }
        }

        return $result;
    }
}

if (!function_exists('array_dot_find_recursive')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for a given value.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_find_recursive($haystack, $needle, $output = '')
    {
        foreach ($haystack as $key => $value) {
            if ($value == $needle) {
                if ($output != '') {
                    return $output . "." . $key;
                } else {
                    return $output;
                }
                return $output;
            } elseif (is_array($value)) {
                $result = array_dot_find_recursive($value, $needle, $key);
                if (!is_null($result) && $result != $output) {
                    if ($output != '') {
                        return $output . "." . $result;
                    } else {
                        return $result;
                    }
                }
            }
        }
        return null;
    }
}

if (!function_exists('array_dot_find_at_depth_recursive')) {
    /**
     * Get the "dot" notation key from a multidimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  mixed   $needle
     * @param  int     $depth
     * @param  string  $output
     * @return mixed
     */
    function array_dot_find_at_depth_recursive($haystack, $needle, $depth = 1, $level = 1, $output = '')
    {
        if ($level < $depth) {
            foreach ($haystack as $key => $value) {
                if ($value == $needle) {
                    if ($output != '') {
                        return $output . "." . $key;
                    } else {
                        return $output;
                    }
                    return $output;
                } elseif (is_array($value)) {
                    $level += 1;
                    $result = array_dot_find_at_depth_recursive($value, $needle, $depth, $level, $key);
                    $level -= 1;
                    if (!is_null($result) && $result != $output) {
                        if ($output != '') {
                            return $output . "." . $result;
                        } else {
                            return $result;
                        }
                    }
                }
            }
        }
        return null;
    }
}

if (!function_exists('array_dot_find_all_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_find_all_recursive($haystack, $needle, $notation = '')
    {
        $output = [];

        foreach ($haystack as $key => $value) {
            if ($value == $needle) {
                if ($notation != '') {
                    array_push($output, $notation . "." . $key);
                } else {
                    array_push($output, $key);
                }
            } elseif (is_array($value)) {
                $result = array_dot_find_all_recursive($value, $needle, $key);

                if (!empty($result) && $result != $output) {
                    array_push($output, ...$result);
                }
            }
        }

        return empty($output) ? [] : $output;
    }
}

if (!function_exists('array_dot_find_all_at_depth_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_find_all_at_depth_recursive($haystack, $needle, $depth = 1, $level = 1, $notation = '')
    {
        $output = [];

        if ($level < $depth) {
            foreach ($haystack as $key => $value) {
                if ($value == $needle) {
                    if ($notation != '') {
                        array_push($output, $notation . "." . $key);
                    } else {
                        array_push($output, $key);
                    }
                } elseif (is_array($value)) {
                    $level += 1;
                    $result = array_dot_find_all_at_depth_recursive($value, $needle, $depth, $level, $key);
                    $level -= 1;

                    if (!empty($result) && $result != $output) {
                        array_push($output, ...$result);
                    }
                }
            }
        }

        return empty($output) ? [] : $output;
    }
}

if (!function_exists('array_dot_find_first_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_find_first_recursive($haystack, $needle, $notation = '')
    {
        $count  = 0;
        $nth    = 1;
        $output = [];
        if (is_array($haystack) && count($haystack) > 0) {
            foreach ($haystack as $key => $value) {
                if ($value == $needle) {
                    if ($notation != '') {
                        array_push($output, $notation . "." . $key);
                    } else {
                        array_push($output, $key);
                    }
                } elseif (is_array($value)) {
                    $result = array_dot_find_nth_recursive($value, $needle, $key);
                    if (!empty($result) && $result != $output) {
                        array_push($output, ...$result);
                    }
                }
                $count = !empty($output) ? count($output) : 0;
                if ($count >= $nth) {
                    break;
                }
            }
        }

        return empty($output) && !isset($output[$nth - 1]) ? $output : $output[$nth - 1];
    }
}

if (!function_exists('array_dot_find_first_at_depth_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_find_first_at_depth_recursive($haystack, $needle, $depth = 1, $level = 1, $notation = '')
    {
        $count  = 0;
        $nth    = 1;
        $output = [];

        if ($level < $depth) {
            if (is_array($haystack) && count($haystack) > 0) {
                foreach ($haystack as $key => $value) {
                    if ($value == $needle) {
                        if ($notation != '') {
                            array_push($output, $notation . "." . $key);
                        } else {
                            array_push($output, $key);
                        }
                    } elseif (is_array($value)) {
                        $level += 1;
                        $result = array_dot_find_nth_at_depth_recursive($value, $needle, $depth, $level, $key);
                        $level -= 1;
                        if (!empty($result) && $result != $output) {
                            array_push($output, ...$result);
                        }
                    }
                    $count = !empty($output) ? count($output) : 0;
                    if ($count >= $nth) {
                        break;
                    }
                }
            }
        }

        return empty($output) && !isset($output[$nth - 1]) ? $output : $output[$nth - 1];
    }
}

if (!function_exists('array_dot_find_last_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_find_last_recursive($haystack, $needle, $notation = '')
    {
        $count  = 0;
        $output = [];

        if (is_array($haystack) && count($haystack) > 0) {
            foreach ($haystack as $key => $value) {
                if ($value == $needle) {
                    if ($notation != '') {
                        array_push($output, $notation . "." . $key);
                    } else {
                        array_push($output, $key);
                    }
                } elseif (is_array($value)) {
                    $result = array_dot_find_nth_recursive($value, $needle, $key);
                    if (!empty($result) && $result != $output) {
                        array_push($output, ...$result);
                    }
                }
                $count = !empty($output) ? count($output) : 0;
                if ($count >= $nth) {
                    break;
                }
            }
        }

        return empty($output) && !isset($output[$nth - 1]) ? $output : $output[$nth - 1];
    }
}

if (!function_exists('array_dot_find_last_at_depth_recursive')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_find_last_at_depth_recursive($haystack, $needle, $depth = 1, $level = 1, $notation = '')
    {
        $count  = 0;
        $output = [];

        if ($level < $depth) {
            if (is_array($haystack) && count($haystack) > 0) {
                foreach ($haystack as $key => $value) {
                    if ($value == $needle) {
                        if ($notation != '') {
                            array_push($output, $notation . "." . $key);
                        } else {
                            array_push($output, $key);
                        }
                    } elseif (is_array($value)) {
                        $level += 1;
                        $result = array_dot_find_last_at_depth_recursive($value, $needle, $depth, $level, $key);
                        $level -= 1;
                        if (!empty($result) && $result != $output) {
                            array_push($output, ...$result);
                        }
                    }
                    $count = !empty($output) ? count($output) : 0;
                    if ($count >= $nth) {
                        break;
                    }
                }
            }
        }

        return empty($output) && !isset($output[$nth - 1]) ? $output : $output[$nth - 1];
    }
}

if (!function_exists('array_dot_find_recursive_robust')) {
    /**
     * Get all keys from a variable dimensional array
     * for a given value.
     *
     * @param  array   $haystack
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_dot_find_recursive_robust($haystack, $needle, $ith = null, $nth = null, $depth = null, $level = 0, $notation = '')
    {
        $level += 1;
        $count        = 1;
        $length       = 0;
        $output       = [];
        $maxLength    = is_array($nth) ? count($nth) : $nth;
        $maxIteration = is_array($nth) ? max($nth) : $nth;
        $depthRange   = is_array($depth) ? range(max($depth), min($depth)) : null;

        if (is_null($depth) || (is_numeric($depth) && $level <= $depth) || (is_array($depth) && in_array($depthRange, $level))) {
            if (is_array($haystack) && count($haystack) > 0) {
                foreach ($haystack as $key => $value) {
                    if ($value == $needle) {
                        if ($notation != '') {
                            if (is_null($depth) || (is_numeric($depth) && ($level == $depth)) || (is_array($depth) && in_array($depth, $level))) {
                                array_push($output, $notation . "." . $key);
                            }
                        } else {
                            if (is_null($depth) || (is_numeric($depth) && ($level == $depth)) || (is_array($depth) && in_array($depth, $level))) {
                                array_push($output, $key);
                            }
                        }
                        $count++;
                    } elseif (is_array($value)) {
                        list($result, $atDepth) = array_dot_find_recursive_robust($value, $needle, $ith, $nth, $depth, $level, $key);

                        if (!empty($result) && $result != $output) {
                            if (is_null($depth) || (is_numeric($depth) && ($atDepth == $depth)) || (is_array($depth) && in_array($depth, $atDepth))) {
                                array_push($output, ...$result);
                            }

                            $count++;
                        }
                    }

                    $length = !empty($output) ? count($output) : 0;

                    if (!is_null($nth) && ($length >= $maxLength || $count - 1 == $maxIteration)) {
                        break;
                    }
                }
            }
        }

        if ($level == 1) {
            if (empty($output)) {
                return $output;
            } elseif (is_array($ith)) {
                $final = [];

                foreach ($ith as $instance) {
                    if (isset($output[$instance - 1])) {
                        $final[] = $output[$instance - 1];
                    }
                }

                return $final;
            } else {
                return !is_null($ith) && isset($output[$ith - 1]) ? $output[$ith - 1] : $output;
            }
        } else {
            $level = isset($atDepth) ? $atDepth : $level;
            return [$output, $level];
        }
    }
}

if (!function_exists('array_delete_value')) {
    function array_delete_value($haystack, $needle)
    {
        $output = [];

        foreach ($haystack as $key => $value) {
            if ($needle != $value) {
                $output[$key] = $value;
            }
        }

        return $output;
    }
}

if (!function_exists('array_delete_key')) {
    function array_delete_key($haystack, $needle)
    {
        $output = [];

        foreach ($haystack as $key => $value) {
            if ($needle != $key) {
                $output[$key] = $value;
            }
        }

        return $output;
    }
}

if (!function_exists('array_delete_key_recursive')) {
    /**
     * Remove one or many array items from a given array.
     *
     * @param  array  $array
     * @param  string $key
     * @return void
     */
    function array_delete_key_recursive($haystack, $needle)
    {
        foreach ($haystack as $key => $value) {
            if ($key == $needle) {
                unset($key);
            } elseif (is_array($value)) {
                $output[$key] = array_delete($value, $needle);
            } else {
                $output[$key] = $value;
            }
        }
        return $output;
    }
}

if (!function_exists('array_dot_delete')) {
    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return void
     */
    function array_dot_delete(&$array, $keys)
    {
        $original = &$array;

        $keys = (array) $keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (array_key($array, $key)) {
                unset($array[$key]);

                continue;
            }

            $parts = explode('.', $key);

            // clean up before each pass
            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }
}

if (!function_exists('array_rename_many')) {
    /**
     * This function replaces the keys of an associate array by those supplied in the keys array
     *
     * @param  array $array target associative array in which the keys are intended to be replaced
     * @param  array $keys  associate array where search key => replace by key, for replacing respective keys
     * @return array        results with replaced keys
     */
    function array_rename_many($array, $keys)
    {
        foreach ($keys as $search => $replace) {
            if (isset($array[$search])) {
                $array[$replace] = $array[$search];
                unset($array[$search]);
            }
        }

        return $array;
    }
}

if (!function_exists('array_rename')) {
    /**
     * This function replaces the target key of an associate array by the supplied key in a
     * recursive fashion.
     *
     * @param  array  $haystack target associative array in which the keys are intended to be replaced
     * @param  string $needle   the target key that will be renamed.
     * @param  string $new      the key that will make the replacement.
     * @return array            result with replaced keys
     */
    function array_rename($haystack, $needle, $new)
    {
        foreach ($haystack as $key => $value) {
            if ($key == $needle) {
                $output[$new] = $value;
            } elseif (is_array($value)) {
                $output[$key] = array_rename($value, $needle, $new);
            } else {
                $output[$key] = $value;
            }
        }
        return $output;
    }
}

if (!function_exists('array_swap_many')) {
    function array_swap_many($base, $replacements)
    {
        foreach (array_slice(func_get_args(), 1) as $replacements) {
            $bref_stack = [&$base];
            $head_stack = [$replacements];

            do {
                end($bref_stack);

                $bref = &$bref_stack[key($bref_stack)];
                $head = array_pop($head_stack);

                unset($bref_stack[key($bref_stack)]);

                foreach (array_keys($head) as $key) {
                    if (isset($key, $bref) && is_array($bref[$key]) && is_array($head[$key])) {
                        $bref_stack[] = &$bref[$key];
                        $head_stack[] = $head[$key];
                    } else {
                        $bref[$key] = $head[$key];
                    }
                }
            } while (count($head_stack));
        }

        return $base;
    }
}

if (!function_exists('array_swap_recursive')) {
    /* Find the value of a key for a multidimensional array */
    function array_swap_recursive($haystack, $needle, $new)
    {
        foreach ($haystack as $key => $value) {
            if ($value == $needle) {
                $output[$key] = $new;
            } elseif (is_array($value)) {
                $output[$key] = array_swap($value, $needle, $new);
            } else {
                $output[$key] = $value;
            }
        }
        return $output;
    }
}

if (!function_exists('array_swap_many_recursive')) {
    function array_swap_many_recursive($array, $array1)
    {
        function recurse($array, $array1)
        {
            foreach ($array1 as $key => $value) {
                /* create new key in $array, if it is empty or not an array */
                if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key]))) {
                    $array[$key] = [];
                }

                /* overwrite the value in the base array */
                if (is_array($value)) {
                    $value = recurse($array[$key], $value);
                }
                $array[$key] = $value;
            }
            return $array;
        }

        /* handle the arguments, merge one by one */
        $args  = func_get_args();
        $array = $args[0];
        if (!is_array($array)) {
            return $array;
        }
        for ($i = 1; $i < count($args); $i++) {
            if (is_array($args[$i])) {
                $array = recurse($array, $args[$i]);
            }
        }
        return $array;
    }
}

if (!function_exists('array_dot_add')) {
    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    function array_dot_add($array, $key, $value)
    {
        if (is_null(array_dot_seek($array, $key))) {
            array_dot_set($array, $key, $value);
        }

        return $array;
    }
}

if (!function_exists('array_dot_set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    function array_dot_set($array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}

if (!function_exists('array_fill_in')) {
    /**
     * Fill in missing numeric keys
     *
     * @return array
     */
    function array_fill_in($array, $default = null, $atleast = 0)
    {
        $array = $array + array_fill(0, max($atleast, max(array_keys($array))), $default);
        ksort($array);
        return $array;
    }
}

if (!function_exists('array_build')) {
    /**
     * Build a new array using a callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return array
     *
     */
    function array_build($array, callable $callback)
    {
        $results = [];

        foreach ($array as $key => $value) {
            list($innerKey, $innerValue) = call_user_func($callback, $key, $value);
            $results[$innerKey]          = $innerValue;
        }

        return $results;
    }
}

if (!function_exists('array_dot')) {
    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array   $array
     * @param  string  $prepend
     * @return array
     */
    function array_dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, array_dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }
}

if (!function_exists('array_undot')) {
    /**
     * Expands a dotted associative array. The inverse of array_dot().
     *
     * @param  array $array
     * @return array
     */
    function array_undot(array $array)
    {
        $return = [];
        foreach ($array as $key => $value) {
            array_set($return, $key, $value);
        }
        return $return;
    }
}

if (!function_exists('array_first')) {
    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    function array_first($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return value($default);
            }

            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return value($default);
    }
}

if (!function_exists('array_flatten')) {
    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  Collection|array  $array
     * @param  int               $depth
     * @return array
     */
    function array_flatten($array, $depth = INF)
    {
        return array_reduce($array, function ($result, $item) use ($depth) {
            $item = $item instanceof Collection ? $item->all() : $item;

            if (!is_array($item)) {
                return array_merge($result, [$item]);
            } elseif ($depth === 1) {
                return array_merge($result, array_values($item));
            } else {
                return array_merge($result, array_flatten($item, $depth - 1));
            }
        }, []);
    }
}

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        if (!array_accessible($array)) {
            return value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (array_exists_key($array, $key)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (array_accessible($array) && array_exists_key($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }
}

if (!function_exists('array_last_callable')) {
    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    function array_last_callable($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }

        return array_first_callable(array_reverse($array, true), $callback, $default);
    }
}

if (!function_exists('array_first_callable')) {
    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    function array_first_callable($array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return value($default);
            }

            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return value($default);
    }
}

if (!function_exists('array_has')) {
    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @return bool
     */
    function array_has($array, $key)
    {
        if (is_null($keys)) {
            return false;
        }

        $keys = (array) $keys;

        if (!$array) {
            return false;
        }

        if ($keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;

            if (array_exists_key($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (array_accessible($subKeyArray) && array_exists_key($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }
}

if (!function_exists('array_pluck')) {
    /**
     * Pluck an array of values from an array.
     *
     * @param  array   $array
     * @param  string|array  $value
     * @param  string|array|null  $key
     * @return array
     */
    function array_pluck($array, $value, $key = null)
    {
        $results = [];

        list($value, $key) = array_explode_pluck_parameters($value, $key);

        foreach ($array as $item) {
            $itemValue = data_get($item, $value);

            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = data_get($item, $key);

                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string) $itemKey;
                }

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param  string|array  $value
     * @param  string|array|null  $key
     * @return array
     */
    function array_explode_pluck_parameters($value, $key)
    {
        $value = is_string($value) ? explode('.', $value) : $value;

        $key = is_null($key) || is_array($key) ? $key : explode('.', $key);

        return [$value, $key];
    };
}
if (!function_exists('array_prepend')) {
    /**
     * Push an item onto the beginning of an array.
     *
     * @param  array  $array
     * @param  mixed  $value
     * @param  mixed  $key
     * @return array
     */
    function array_prepend($array, $value, $key = null)
    {
        if (is_null($key)) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }
}

if (!function_exists('array_pull')) {
    /**
     * Get a value from the array, and remove it.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_pull(&$array, $key, $default = null)
    {
        $value = array_get($array, $key, $default);

        array_forget($array, $key);

        return $value;
    }
}

if (!function_exists('array_forget')) {
    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return void
     */
    function array_forget(&$array, $keys)
    {
        $original = &$array;

        $keys = (array) $keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (array_exists_key($array, $key)) {
                unset($array[$key]);

                continue;
            }

            $parts = explode('.', $key);

            // clean up before each pass
            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }
}


if (!function_exists('array_set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    function array_set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}

if (!function_exists('array_sort_recursive')) {
    /**
     * Recursively sort an array by keys and values.
     *
     * @param  array  $array
     * @return array
     */
    function array_sort_recursive($array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = array_sort_recursive($value);
            }
        }

        if (array_associative($array)) {
            ksort($array);
        } else {
            sort($array);
        }

        return $array;
    }
}

if (!function_exists('array_with_filter')) {
    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null  $callback
     * @return static
     */
    function array_with_filter(array $array, callable $callback = null)
    {
        if ($callback) {
            return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
        }

        return array_filter($array);
    }
}

if (!function_exists('array_where')) {
    /**
     * Filter the array using the given callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return array
     */
    function array_where(array $array, $key, $operator, $value = null)
    {
        if (func_num_args() == 3) {
            $value = $operator;

            $operator = '=';
        }

        return array_with_filter($array, operator_for_where($key, $operator, $value));
    }

    /**
     * Get an operator checker callback.
     *
     * @param  string  $key
     * @param  string  $operator
     * @param  mixed  $value
     * @return \Closure
     */
    function operator_for_where($key, $operator, $value)
    {
        if (func_num_args() === 1) {
            $value = true;

            $operator = '=';
        }

        if (func_num_args() === 2) {
            $value = $operator;

            $operator = '=';
        }

        return function ($item) use ($key, $operator, $value) {
            $retrieved = data_get($item, $key);

            $strings = array_filter([$retrieved, $value], function ($value) {
                return is_string($value) || (is_object($value) && method_exists($value, '__toString'));
            });

            if (count($strings) < 2 && count(array_filter([$retrieved, $value], 'is_object')) == 1) {
                return in_array($operator, ['!=', '<>', '!==', 'like', 'not like', 'LIKE', 'NOT LIKE']);
            }

            switch ($operator) {
                default:
                case '=':
                case '==':
                    return $retrieved == $value;
                case '!=':
                case '<>':
                    return $retrieved != $value;
                case '<':
                    return $retrieved < $value;
                case '>':
                    return $retrieved > $value;
                case '<=':
                    return $retrieved <= $value;
                case '>=':
                    return $retrieved >= $value;
                case '===':
                    return $retrieved === $value;
                case '!==':
                    return $retrieved !== $value;
                case 'like':
                case 'LIKE':
                    return str_like($retrieved, $value);
                case 'not like':
                case 'NOT LIKE':
                    return !str_like($retrieved, $value);
                case 'is not null':
                case 'IS NOT NULL':
                    return $retrieved !== null;
                case 'is null':
                case 'IS NULL':
                    return $retrieved === null;
            }
        };
    }
}

if (!function_exists('array_where_not_null')) {
    /**
     * Add a "where not null" clause to the query.
     *
     * @param  string  $column
     * @param  string  $boolean
     * @return array
     */
    function array_where_not_null($array, $column)
    {
        return array_where($array, $column, '!==', null);
    };
}

if (!function_exists('array_where_null')) {
    /**
     * Add an "where null" clause to the query.
     *
     * @param  string  $column
     * @return array
     */
    function array_where_null($array, $column)
    {
        return array_where($array, $column, '===', null);
    };
}

if (!function_exists('array_where_strict')) {
    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return static
     */
    function array_where_strict(array $array, $key, $value)
    {
        return array_where($array, $key, '===', $value);
    };
}

if (!function_exists('array_except')) {
    /**
     * Get all of the given array except for a specified array of items.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    function array_except($array, $keys)
    {
        array_forget($array, $keys);

        return $array;
    }
}

if (!function_exists('array_except_null')) {
    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    function array_except_null(array $array)
    {
        return array_filter($array, function ($value) {
            return !is_null($value);
        });
    }
}

if (!function_exists('array_except_empty')) {
    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    function array_except_empty(array $array)
    {
        return array_filter($array, function ($value) {
            return !empty($value) || $value == 0;
        });
    }
}

if (!function_exists('array_only_keys')) {
    /**
     * Get a subset of the items from the given array.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    function array_only_keys($array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }
}

if (!function_exists('array_only_numeric')) {
    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    function array_only_numeric(array $array)
    {
        return array_filter($array, function ($value) {
            return is_numeric($value);
        });
    }
}

if (!function_exists('array_only_bool')) {
    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    function array_only_bool(array $array)
    {
        return array_filter($array, function ($value) {
            return is_bool($value);
        });
    }
}

if (!function_exists('array_only_int')) {
    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    function array_only_int(array $array)
    {
        return array_filter($array, function ($value) {
            return is_int($value);
        });
    }
}

if (!function_exists('array_only_array')) {
    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    function array_only_array(array $array)
    {
        return array_filter($array, function ($value) {
            return is_array($value);
        });
    }
}

if (!function_exists('array_only_null')) {
    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    function array_only_null(array $array)
    {
        return array_filter($array, function ($value) {
            return is_null($value);
        });
    }
}

if (!function_exists('array_only_object')) {
    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    function array_only_object(array $array)
    {
        return array_filter($array, function ($value) {
            return is_object($value);
        });
    }
}

if (!function_exists('array_only_string')) {
    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    function array_only_string(array $array)
    {
        return array_filter($array, function ($value) {
            return is_string($value);
        });
    }
}

if (!function_exists('array_collapse')) {
    /**
     * Collapse an array of arrays into a single array.
     *
     * @param  array  $array
     * @return array
     */
    function array_collapse($array)
    {
        $results = [];

        foreach ($array as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            } elseif (!is_array($values)) {
                continue;
            }

            $results = array_merge($results, $values);
        }

        return $results;
    }
}

if (!function_exists('array_fission')) {
    /**
     * Divide an array into two arrays. One with keys and the other with values.
     * Opposite of array_combine native php function.
     *
     * @param  array  $array
     * @return array
     */
    function array_fission($array)
    {
        return [array_keys($array), array_values($array)];
    }
}

if (!function_exists('array_fusion')) {
    /**
     * Combines two arrays by using the keys of one
     * and the values of the other.
     *
     * This function also allows the option to keep
     * the first, last, or all values of a subarray.
     *
     * @param array  $keys
     * @param array  $values
     * @param string $keep
     */
    function array_fusion($keys, $values, $keep = 'left')
    {
        $result = [];

        foreach ($keys as $i => $k) {
            $result[$k][] = $values[$i];
        }

        array_walk($result, function (&$v) use ($keep) {
            $v = (is_array($v) && count($v) == 1) ? array_pop($v) : $v;
            if (strtoupper($keep) == 'LEFT') {
                $v = (is_array($v) && count($v) >= 1) ? array_shift($v) : $v;
            } elseif (strtoupper($keep) == 'RIGHT') {
                $v = (is_array($v) && count($v) >= 1) ? array_pop($v) : $v;
            }
        });
        return $result;
    }
}

if (!function_exists('array_multi_search')) {
    /**
     * Iterate a multi-dimensional array
     * @param  string $needle
     * @param  array  $haystack
     * @return mixed
     */
    function array_multi_search($needle, $haystack)
    {
        if (is_array($haystack) && count($haystack) > 0) {
            foreach ($haystack as $hay => $stack) {
                if (is_array($stack) && count($stack) > 0) {
                    return array_multi($needle, $stack);
                }

                return array_search($needle, $haystack);
            }
        }
    }
}

if (!function_exists('array_key')) {
    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  array       $array
     * @param  string|int  $key
     * @return bool
     */
    function array_key($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}

if (!function_exists('array_exists_key')) {
    /**
     * Determine if the given key exists in the provided
     * multidimentsional array.
     *
     * @param  array       $haystack
     * @param  string|int  $needle
     * @return bool
     */
    function array_exists_key($haystack, $needle)
    {
        if ($haystack instanceof ArrayAccess) {
            return $haystack->offsetExists($needle);
        }

        foreach ($haystack as $key => $item) {
            if ($key == $needle) {
                return true;
            } else {
                if (is_array($item) && array_exists_key($item, $needle)) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('array_exists_value')) {
    /**
     * Determine if the given value exists in the provided
     * multidimentsional array.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int  $key
     * @return bool
     */
    function array_exists_value($haystack, $needle)
    {
        foreach ($haystack as $key => $item) {
            if ($item == $needle) {
                return true;
            } else {
                if (is_array($item) && array_exists_value($item, $needle)) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('array_accessible')) {
    function array_accessible()
    {
        /**
         * Determine whether the given value is array accessible.
         *
         * @param  mixed  $value
         * @return bool
         */
        function array_accessible($value)
        {
            return is_array($value) || $value instanceof ArrayAccess;
        }
    }
}

if (!function_exists('array_associative')) {
    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param  array  $array
     * @return bool
     */
    function array_associative(array $array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }
}
if (!function_exists('array_keys_multi')) {
    /**
     * Get list of all keys of a multidimentional array
     *
     * @param array $array Multidimensional array to extract keys from
     * @return array
     */
    function array_keys_multi(array $array)
    {
        $keys = [];

        foreach ($array as $key => $value) {
            $keys[] = $key;

            if (is_array($value)) {
                $keys = array_merge($keys, array_keys_multi($value));
            }
        }

        return $keys;
    }
}

if (!function_exists('array_indexed')) {
    /**
     * Returns a value indicating whether the given array is an indexed array.
     *
     * An array is indexed if all its keys are integers. If `$consecutive` is true,
     * then the array keys must be a consecutive sequence starting from 0.
     *
     * Note that an empty array will be considered indexed.
     *
     * @param array $array the array being checked
     * @param bool $consecutive whether the array keys must be a consecutive sequence
     * in order for the array to be treated as indexed.
     * @return bool whether the array is associative
     */
    function array_indexed(array $array, bool $consecutive = false)
    {
        if (!is_array($array)) {
            return false;
        }
        if (empty($array)) {
            return true;
        }
        if ($consecutive) {
            return array_keys($array) === range(0, count($array) - 1);
        } else {
            foreach ($array as $key => $value) {
                if (!is_int($key)) {
                    return false;
                }
            }
            return true;
        }
    }
}

if (!function_exists('array_reverse_keys')) {
    function array_reverse_keys(array $array)
    {
        return array_reverse(array_reverse($array, true), false);
    }
}

if (!function_exists('array_rotate')) {
    function array_rotate(array &$array)
    {
        $element = array_shift($array);
        array_push($array, $element);
        return $element;
    }
}

if (!function_exists('array_slice_assoc')) {
    function array_slice_assoc(array $array, array $keys)
    {
        return array_intersect_key($array, array_flip($keys));
    }
}

if (!function_exists('array_occurances')) {
    /**
     * Returns an associative array of values from
     * array as keys and their count as value.
     *
     * @param  array $array
     * @param  bool  $insensitive
     * @return array
     */
    function array_occurances(array $array, bool $insensitive = false)
    {
        if ($insensitive) {
            return array_count_values(array_map('strtolower', $array));
        }

        return array_count_values($array);
    }
}

if (!function_exists('array_dot_depth')) {
    function array_dot_depth(string $key)
    {
        $parts = explode('.', $key);
        return count($parts);
    }
}

if (!function_exists('array_depth')) {
    function array_depth($array)
    {
        $max_indentation = 1;

        $array_str = print_r($array, true);
        $lines     = explode("\n", $array_str);

        foreach ($lines as $line) {
            $indentation = (strlen($line) - strlen(ltrim($line))) / 4;

            if ($indentation > $max_indentation) {
                $max_indentation = $indentation;
            }
        }

        return ceil(($max_indentation - 1) / 2) + 1;
    }
}

if (!function_exists('array_depth_recursive')) {
    function array_depth_recursive($array)
    {
        $max_depth = 1;

        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = array_depth($value) + 1;

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }

        return $max_depth;
    }
}

if (!function_exists('array_difference')) {
    /**
     * In a venn diagram between A and B, this function
     * returns exclusive values of both A and B, but
     * not values shared between A and B.
     * (Left and Right Only Venn Diagram)
     *
     * @param  array $a
     * @param  array $b
     * @return array
     */
    function array_difference($a, $b)
    {
        $intersect = array_intersect($a, $b);
        return array_merge(array_diff($a, $intersect), array_diff($b, $intersect));
    }
}

if (!function_exists('array_right_difference')) {
    /**
     * In a venn diagram between A and B, this function
     * returns values found exclusively in B, but not
     * values shared between A and B.
     * (Right Only Venn Diagram)
     */
    function array_right_difference($a, $b)
    {
        $intersect = array_intersect($a, $b);
        return array_diff($b, $intersect);
    }
}

if (!function_exists('array_left_difference')) {
    /**
     * In a venn diagram between A and B, this function
     * returns values found exclusively in A, but not
     * values shared between A and B.
     * (Left Only Venn Diagram)
     *
     * @param  array $a
     * @param  array $b
     * @return array
     */
    function array_left_difference($a, $b)
    {
        $intersect = array_intersect($a, $b); // B in A
        return array_diff($a, $intersect); // A without (B in A)
    }
}

if (!function_exists('array_union')) {
    /**
     * In a venn diagram between A and B, this function
     * returns all values found exclusively in A and
     * exclusively in B and values shared between A and B.
     * (All Venn Diagram)
     */
    function array_union(array $a, array $b)
    {
        return array_merge(
            array_intersect($a, $b), // B that also belong to A
            array_diff($a, $b), // A without B
            array_diff($b, $a) // B without A
        );
    }
}

if (!function_exists('array_intersection')) {
    /**
     * In a venn diagram between A and B, this function
     * returns all values shared between A and B.
     * (Center Venn Diagram)
     */
    function array_intersection(array $a, array $b)
    {
        return array_union(array_intersect($a, $b), array_intersect($b, $a));
    }
}

if (!function_exists('array_right_union')) {
    /**
     * In a venn diagram between A and B, this function
     * returns all values found exclusively in A and
     * values shared between A and B.
     * (Right and Center of Venn Diagram)
     *
     * @param  array $a
     * @param  array $b
     * @return array
     */
    function array_right_union(array $a, array $b)
    {
        return array_merge(
            array_intersect($a, $b), // B that also belong to A
            array_diff($b, $a) // A without B
        );
    }
}

if (!function_exists('array_left_union')) {
    /**
     * In a venn diagram between A and B, this function
     * returns all values found exclusively in A and and
     * commonly shared values.
     * (Left and Center of Venn Diagram)
     *
     * @param  array $a
     * @param  array $b
     * @return array
     */
    function array_left_union(array $a, array $b)
    {
        return array_merge(
            array_intersect($a, $b), // B that also belong to A
            array_diff($a, $b) // A without B
        );
    }
}

if (!function_exists('array_inner_join')) {
    function array_inner_join(array $left, array $right, $on)
    {
        $out = [];
        foreach ($left as $left_record) {
            foreach ($right as $right_record) {
                if ($left_record[$on] == $right_record[$on]) {
                    $out[] = array_merge($left_record, $right_record);
                }
            }
        }
        return $out;
    }
}

if (!function_exists('array_left_join')) {
    function array_left_join(array $left, array $right, $left_join_on, $right_join_on = null)
    {
        $final = [];

        if (empty($right_join_on)) {
            $right_join_on = $left_join_on;
        }

        foreach ($left as $k => $v) {
            $final[$k] = $v;
            foreach ($right as $kk => $vv) {
                if ($v[$left_join_on] == $vv[$right_join_on]) {
                    foreach ($vv as $key => $val) {
                        $final[$k][$key] = $val;
                    }
                } else {
                    foreach ($vv as $key => $val) {
                        $final[$k][$key] = null;
                    }
                }
            }
        }
        return $final;
    }
}

if (!function_exists('array_equal_sizes')) {
    /**
     * Determine whether the size of two arrays
     * are the same.
     *
     * @param  array $a
     * @param  array $b
     *
     * @return bool
     */
    function array_equal_sizes(array $a, array $b)
    {
        $acount = count($a);
        $bcount = count($b);

        return $a == $b;
    }
}

if (!function_exists('array_equalize_sizes')) {
    /**
     * Equalize the size of two arrays.
     *
     * @param  array $a
     * @param  array $b
     * @param  bool  $pad
     * @return array
     */
    function array_equalize_sizes(array $a, array $b, bool $pad = true)
    {
        $acount = count($a);
        $bcount = count($b);

        if ($pad) {
            $size = ($acount > $bcount) ? $bcount : $acount;
            $a    = array_slice($a, 0, $size);
            $b    = array_slice($b, 0, $size);
        } else {
            if ($acount > $bcount) {
                $more = $acount - $bcount;
                for ($i = 0; $i < $more; $i++) {
                    $key     = 'extra_field_' . $i;
                    $b[$key] = "";
                }
            } elseif ($acount < $bcount) {
                $more = $bcount - $acount;
                for ($i = 0; $i < $more; $i++) {
                    $key     = 'extra_field_' . $i;
                    $a[$key] = "";
                }
            }
        }
        return [$a, $b];
    }
}

if (!function_exists('array_merge_sort')) {
    function array_merge_sort($data)
    {
        // Only process if we're not down to one piece of data
        if (count($data) > 1) {
            // Find out the middle of the current data set and split it there to obtain to halfs
            $data_middle = round(count($data) / 2, 0, PHP_ROUND_HALF_DOWN);
            // and now for some recursive magic
            $data_part1 = array_merge_sort(array_slice($data, 0, $data_middle));
            $data_part2 = array_merge_sort(array_slice($data, $data_middle, count($data)));
            // Setup counters so we can remember which piece of data in each half we're looking at
            $counter1 = $counter2 = 0;
            // iterate over all pieces of the currently processed array, compare size & reassemble
            for ($i = 0; $i < count($data); $i++) {
                // if we're done processing one half, take the rest from the 2nd half
                if ($counter1 == count($data_part1)) {
                    $data[$i] = $data_part2[$counter2];
                    ++$counter2;
                    // if we're done with the 2nd half as well or as long as pieces in the first half are still smaller than the 2nd half
                } elseif (($counter2 == count($data_part2)) or ($data_part1[$counter1] < $data_part2[$counter2])) {
                    $data[$i] = $data_part1[$counter1];
                    ++$counter1;
                } else {
                    $data[$i] = $data_part2[$counter2];
                    ++$counter2;
                }
            }
        }
        return $data;
    }
}

if (!function_exists('array_bubble_sort')) {
    function array_bubble_sort($array)
    {
        if (!$length = count($array)) {
            return $array;
        }
        for ($outer = 0; $outer < $length; $outer++) {
            for ($inner = 0; $inner < $length; $inner++) {
                if ($array[$outer] < $array[$inner]) {
                    $tmp           = $array[$outer];
                    $array[$outer] = $array[$inner];
                    $array[$inner] = $tmp;
                }
            }
        }
    }
}

if (!function_exists('array_bidirectional_bubble_sort')) {
    function array_bidirectional_bubble_sort($array)
    {
        if (!$length = count($array)) {
            return $array;
        }
        $start = -1;
        while ($start < $length) {
            ++$start;
            --$length;
            for ($i = $start; $i < $length; ++$i) {
                if ($array[$i] > $array[$i + 1]) {
                    $temp          = $array[$i];
                    $array[$i]     = $array[$i + 1];
                    $array[$i + 1] = $temp;
                }
            }
            for ($i = $length; --$i >= $start;) {
                if ($array[$i] > $array[$i + 1]) {
                    $temp          = $array[$i];
                    $array[$i]     = $array[$i + 1];
                    $array[$i + 1] = $temp;
                }
            }
        }
    }
}

if (!function_exists('array_quick_sort')) {
    function array_quick_sort($my_array)
    {
        $loe = $gt = [];
        if (count($my_array) < 2) {
            return $my_array;
        }
        $pivot_key = key($my_array);
        $pivot     = array_shift($my_array);
        foreach ($my_array as $val) {
            if ($val <= $pivot) {
                $loe[] = $val;
            } elseif ($val > $pivot) {
                $gt[] = $val;
            }
        }
        return array_merge(array_quick_sort($loe), [$pivot_key => $pivot], array_quick_sort($gt));
    }
}

if (!function_exists('array_insertion_sort')) {
    function array_insertion_sort($my_array)
    {
        for ($i = 0; $i < count($my_array); $i++) {
            $val = $my_array[$i];
            $j   = $i - 1;
            while ($j >= 0 && $my_array[$j] > $val) {
                $my_array[$j + 1] = $my_array[$j];
                $j--;
            }
            $my_array[$j + 1] = $val;
        }
        return $my_array;
    }
}

if (!function_exists('array_selection_sort')) {
    function array_selection_sort($data)
    {
        for ($i = 0; $i < count($data) - 1; $i++) {
            $min = $i;
            for ($j = $i + 1; $j < count($data); $j++) {
                if ($data[$j] < $data[$min]) {
                    $min = $j;
                }
            }
            $data = array_swap_positions($data, $i, $min);
        }
        return $data;
    }

    function array_swap_positions($data1, $left, $right)
    {
        $backup_old_data_right_value = $data1[$right];
        $data1[$right]               = $data1[$left];
        $data1[$left]                = $backup_old_data_right_value;
        return $data1;
    }
}

if (!function_exists('array_shell_sort')) {
    function array_shell_sort($my_array)
    {
        $x = round(count($my_array) / 2);
        while ($x > 0) {
            for ($i = $x; $i < count($my_array); $i++) {
                $temp = $my_array[$i];
                $j    = $i;
                while ($j >= $x && $my_array[$j - $x] > $temp) {
                    $my_array[$j] = $my_array[$j - $x];
                    $j -= $x;
                }
                $my_array[$j] = $temp;
            }
            $x = round($x / 2.2);
        }
        return $my_array;
    }
}
if (!function_exists('array_cocktail_sort')) {
    function array_cocktail_sort($my_array)
    {
        if (is_string($my_array)) {
            $my_array = str_split(preg_replace('/\s+/', '', $my_array));
        }

        do {
            $swapped = false;
            for ($i = 0; $i < count($my_array); $i++) {
                if (isset($my_array[$i + 1])) {
                    if ($my_array[$i] > $my_array[$i + 1]) {
                        list($my_array[$i], $my_array[$i + 1]) = [$my_array[$i + 1], $my_array[$i]];
                        $swapped                               = true;
                    }
                }
            }

            if ($swapped == false) {
                break;
            }

            $swapped = false;
            for ($i = count($my_array) - 1; $i >= 0; $i--) {
                if (isset($my_array[$i - 1])) {
                    if ($my_array[$i] < $my_array[$i - 1]) {
                        list($my_array[$i], $my_array[$i - 1]) = [$my_array[$i - 1], $my_array[$i]];
                        $swapped                               = true;
                    }
                }
            }
        } while ($swapped);

        return $my_array;
    }
}

if (!function_exists('array_comb_sort')) {
    function array_comb_sort($my_array)
    {
        $gap  = count($my_array);
        $swap = true;
        while ($gap > 1 || $swap) {
            if ($gap > 1) {
                $gap /= 1.25;
            }

            $swap = false;
            $i    = 0;
            while ($i + $gap < count($my_array)) {
                if ($my_array[$i] > $my_array[$i + $gap]) {
                    list($my_array[$i], $my_array[$i + $gap]) = [$my_array[$i + $gap], $my_array[$i]];
                    $swap                                     = true;
                }
                $i++;
            }
        }
        return $my_array;
    }
}

if (!function_exists('array_gnome_sort')) {
    function array_gnome_sort($my_array)
    {
        $i = 1;
        $j = 2;
        while ($i < count($my_array)) {
            if ($my_array[$i - 1] <= $my_array[$i]) {
                $i = $j;
                $j++;
            } else {
                list($my_array[$i], $my_array[$i - 1]) = [$my_array[$i - 1], $my_array[$i]];
                $i--;
                if ($i == 0) {
                    $i = $j;
                    $j++;
                }
            }
        }
        return $my_array;
    }
}

if (!function_exists('array_counting_sort')) {
    function array_counting_sort($my_array, $min, $max)
    {
        $count = [];
        for ($i = $min; $i <= $max; $i++) {
            $count[$i] = 0;
        }

        foreach ($my_array as $number) {
            $count[$number]++;
        }
        $z = 0;
        for ($i = $min; $i <= $max; $i++) {
            while ($count[$i]-- > 0) {
                $my_array[$z++] = $i;
            }
        }
        return $my_array;
    }
}

if (!function_exists('array_radix_sort')) {
    function array_radix_sort($elements)
    {
        // Array for 10 queues.
        $queues = [
            [], [], [], [], [], [], [], [],
            [], [],
        ];
        // Queues are allocated dynamically. In first iteration longest digits
        // element also determined.
        $longest = 0;
        foreach ($elements as $el) {
            if ($el > $longest) {
                $longest = $el;
            }
            array_push($queues[$el % 10], $el);
        }
        // Queues are dequeued back into original elements.
        $i = 0;
        foreach ($queues as $key => $q) {
            while (!empty($queues[$key])) {
                $elements[$i++] = array_shift($queues[$key]);
            }
        }
        // Remaining iterations are determined based on longest digits element.
        $it = strlen($longest) - 1;
        $d  = 10;
        while ($it--) {
            foreach ($elements as $el) {
                array_push($queues[floor($el / $d) % 10], $el);
            }
            $i = 0;
            foreach ($queues as $key => $q) {
                while (!empty($queues[$key])) {
                    $elements[$i++] = array_shift($queues[$key]);
                }
            }
            $d *= 10;
        }
    }
}

if (!function_exists('array_bead_sort')) {
    function bead_sort_columns($my_array)
    {
        if (count($my_array) == 0) {
            return [];
        } else if (count($my_array) == 1) {
            return array_chunk($my_array[0], 1);
        }

        array_unshift($my_array, null);
        // array_map(NULL, $my_array[0], $my_array[1], ...)
        $transpose = call_user_func_array('array_map', $my_array);
        return array_map('array_filter', $transpose);
    }

    function array_bead_sort($my_array)
    {
        foreach ($my_array as $e) {
            $poles[] = array_fill(0, $e, 1);
        }

        return array_map('count', bead_sort_columns(bead_sort_columns($poles)));
    }
}

if (!function_exists('array_bogo_sort')) {
    function bogo_issorted($list)
    {
        $cnt = count($list);
        for ($j = 1; $j < $cnt; $j++) {
            if ($list[$j - 1] > $list[$j]) {
                return false;
            }
        }
        return true;
    }
    function array_bogo_sort($list)
    {
        do {
            shuffle($list);
        } while (!bogo_issorted($list));
        return $list;
    }
}

if (!function_exists('array_check_sort')) {
    function array_check_sort($array)
    {
        if (!$length = count($array)) {
            return true;
        }
        for ($i = 0; $i < $length; $i++) {
            if (isset($array[$i + 1])) {
                if ($array[$i] > $array[$i + 1]) {
                    return false;
                }
            }
        }
        return true;
    }
}

if (!function_exists('array_collapse_with_keys')) {
    /**
     * Collapse an array of arrays into a single array, avoids using array_merge to preserve the keys.
     *
     * @param $parent
     * @return array
     */
    function array_collapse_with_keys($parent)
    {
        $result = [];

        foreach ($parent as $child) {
            if (is_array($child)) {
                foreach ($child as $key => $value) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}

if (!function_exists('array_filter_recursive')) {
    /**
     * Recursively filter an array
     *
     * @param array $array
     * @param callable $callback
     *
     * @return array
     */
    function array_filter_recursive(array $array, \Closure $closure)
    {

        $array = array_filter($array, $closure, ARRAY_FILTER_USE_BOTH);

        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = array_filter_recursive($value, $closure);
            }
        }

        return $array;
    }
}

if (!function_exists('array_map_with_keys')) {
    /**
     * Run an associative map over each of the items, preserving keys.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param $callback
     * @param $array
     * @return array
     */
    function array_map_with_keys($callback, $array)
    {
        $mapped = array_map($callback, $array);

        return array_collapse_with_keys($mapped);
    }
}

if (!function_exists('array_is_multidimensional')) {
    function array_is_multidimensional(array $array)
    {
        return count($array) != count($array, COUNT_RECURSIVE);
    }
}

if (!function_exists('array_values_to_bool')) {
    function array_values_to_bool(array $array)
    {
        array_map_with_keys(function ($value) {
            (bool) $value;
        }, $array);
    }
}

if (!function_exists('array_implode_recursive')) {
    function array_implode_recursive($array, $glue)
    {
        $ret = '';

        foreach ($array as $item) {
            if (is_array($item)) {
                $ret .= array_implode_recursive($item, $glue) . $glue;
            } else {
                $ret .= $item . $glue;
            }
        }

        $ret = substr($ret, 0, 0 - strlen($glue));

        return $ret;
    }
}

if (!function_exists('array_power_set')) {
    function array_power_set($array)
    {
        // initialize by adding the empty set
        $results = [[]];

        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge([$element], $combination));
            }
        }

        return $results;
    }
}
if (!function_exists('array_combinations')) {
    function array_combinations($array)
    {
        // initialize by adding the empty set
        $results = [[]];

        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge([$element], $combination));
            }
        }

        return $results;
    }
}

if (!function_exists('array_print_permutations')) {
    function array_print_permutations($items, $perms = [])
    {
        if (empty($items)) {
            print join(' ', $perms) . "\n";
        } else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems  = $items;
                $newperms  = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                array_print_permutations($newitems, $newperms);
            }
        }
    }
}

if (!function_exists('array_permutations')) {
    /**
     * Returns all possible permutations of $values containing $n elements using a
     * "draw and place back" algorithm
     *
     * The resulting array will always have pow(count($values), $n) entries.
     *
     * For
     *   $values = array('a', 'b') and $n = 2,
     * the result will contain:
     *   [aa, ab, ba, bb]
     *
     * @param array $values Vector to generate permutations of
     * @param int $n Elements per permutation
     * @return array Possible permutations
     */
    function array_permutations(array $values, $n)
    {
        $rec = function (array $values, &$ret, $n, array $cur = []) use (&$rec) {
            if ($n > 0) {
                foreach ($values as $v) {
                    $newCur   = $cur;
                    $newCur[] = $v;
                    $rec($values, $ret, $n - 1, $newCur);
                }
            } else {
                $ret[] = $cur;
            }
        };

        $ret = [];
        $rec($values, $ret, $n);

        return $ret;
    }
}

if (!function_exists('array_transform')) {
    function array_transform($array, $callback)
    {
        array_map($callback, array_keys($array), $array);
    }
}

if (!function_exists('array_with_size')) {
    function array_with_size(int $size)
    {
        if ($size < 1) {
            return [];
        }
        return range(1, $size);
    }
}

if (!function_exists('array_wrap')) {
    /**
     * If the given value is not an array, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    function array_wrap($value)
    {
        return !is_array($value) ? [$value] : $value;
    }
}

if (!function_exists('array_zip')) {
    function array_zip($array, $items)
    {
        $items = array_slice(func_get_args(), 1);

        $arrayableItems = array_map(function ($item) {
            return get_arrayable_items($item);
        }, $items);

        $params = array_merge([function () use ($items) {
            return $items;
        }, $array], $arrayableItems);

        return call_user_func_array('array_map', $params);
    }
}

if (!function_exists('get_arrayable_items')) {
    function get_arrayable_items($items)
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof Collection) {
            return $items->all();
        } elseif ($items instanceof Arrayable) {
            return $items->array();
        } elseif ($items instanceof Jsonable) {
            return json_decode($items->toJson(), true);
        } elseif ($items instanceof JsonSerializable) {
            return $items->jsonSerialize();
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        }

        return (array) $items;
    }
}
