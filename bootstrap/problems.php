<?php

if (!function_exists('twoSum')) {
    /**
     * Given an array of integers, return indices of the two numbers such that
     * they add up to a specific target.
     * Assume that each input would have exactly one solution, and you may not
     * use the same element twice.
     *
     * Solution: Using hashmap O(n) space and time.
     * Explanation: Use of a hashmap makes sense by taking advantage that you
     * know what value to look for that may equal the target.
     * Also only works because numbers are unique and greater than 0 are unsorted
     * so you must make a map of results until you find one matching set.
     *
     * @param Integer[] $nums
     * @param Integer $target
     * @return Integer[]
     */
    function twoSum($nums, $target)
    {
        $map = [];
        foreach ($nums as $index => $current) {
            $complement = $target - $current;
            if (isset($map[$complement])) {
                return [$map[$complement], $index];
            }
            $map[$current] = $index;
        }
    }
}

if (!function_exists('twoSumSorted')) {
    /**
     * Given an array of integers that is already sorted in ascending order,
     * find two numbers such that they add up to a specific target number.
     * The function twoSum should return indices of the two numbers such that
     * they add up to the target, where index1 must be less than index2.
     *
     * Solution: Using two moving pointers O(n) space and time.
     * Explanation: Because it is SORTED and a solution EXISTS, use of two
     * moving pointers that work their way up together makes sense to save
     * space. It is still O(n) but you really at most go through only half the
     * numbers as compared to the hash way.
     *
     * @param Integer[] $nums
     * @param Integer $target
     * @return Integer[]
     */
    function twoSumSorted($numbers, $target)
    {
        $length = count($numbers);
        $start = 0;
        $end = $length - 1;
        while ($start < $end) {
            $sum = $numbers[$start] + $numbers[$end];

            if ($sum == $target) {
                return [$start + 1, $end + 1];
            } elseif ($sum > $target) {
                $end = $end - 1;
            } else {
                $start = $start + 1;
            }
        }
    }
}

if (!function_exists('twoSumLessThanK')) {
    /**
     * @param Integer[] $A
     * @param Integer $K
     * @return Integer
     */
    function twoSumLessThanK($A, $K)
    {
        return -1;
    }
}

if (!function_exists('numUniqueEmails')) {
    /**
     * @param String[] $emails
     * @return Integer
     */
    function numUniqueEmails($emails)
    {
        $map = [];
        foreach ($emails as $email) {
            $local = str_replace(".", "", $email); // replace all '.'
            $local = strstr($local, '+', true) ?: strstr($local, '@', true); // find the local without a +
            $domain = substr(strrchr($email, '@'), 1); // get the domain
            $map[$local . '@' . $domain] = 1; // create the true email
        }

        return count($map); // add up the results
    }
}

if (!function_exists('numUniqueEmailsRegex')) {
    /**
     * @param String[] $emails
     * @return Integer
     */
    function numUniqueEmailsRegex($emails)
    {
        $emails = preg_replace('/\.(?=(.*@))|\+[\w\.]*/i', '', $emails);
        return count(array_unique($emails));
    }
}

if (!function_exists('removeVowels')) {
    /**
     * Given a string S, remove the vowels 'a', 'e', 'i',
     * 'o', and 'u' from it, and return the new string.
     *
     * @param String $S
     * @return String
     */
    function removeVowels($S)
    {
        $vowels = ['a' => 'a', 'e' => 'e', 'i' => 'i', 'o' => 'o', 'u' => 'u', 'A' => 'A', 'E' => 'E', 'I' => 'I', 'O' => 'O', 'U' => 'U'];
        return str_replace($vowels, '', $S);
    }
}

if (!function_exists('reverseVowels')) {
    /**
     * Write a function that takes a string as input and reverse only the
     * vowels of a string.
     *
     * @param String $s
     * @return String
     */
    function reverseVowels($s)
    {
        $vowels = ['a' => 'a', 'e' => 'e', 'i' => 'i', 'o' => 'o', 'u' => 'u', 'A' => 'A', 'E' => 'E', 'I' => 'I', 'O' => 'O', 'U' => 'U'];

        $length = strlen($s);
        $i = 0;
        $j = $length - 1;

        $firstVowel = null;
        $lastVowel = null;

        while ($j > $i) {
            if (isset($vowels[$s[$i]])) {
                $firstVowel = $i;
            }
            if (isset($vowels[$s[$j]])) {
                $lastVowel = $j;
            }

            if (isset($firstVowel) && isset($lastVowel)) {
                $temp = $s[$firstVowel];
                $s[$firstVowel] = $s[$lastVowel];
                $s[$lastVowel] = $temp;
                unset($firstVowel);
                unset($lastVowel);
                $j--;
                $i++;
            } elseif (isset($firstVowel)) {
                $j--;
            } else {
                $i++;
            }
        }
        return $s;
    }
}

if (!function_exists('reverseStr')) {
    /**
     * PART I
     * Write a function that reverses a string. The input string is given as an
     * array of characters char[].
     * Do not allocate extra space for another array, you must do this by
     * modifying the input array in-place with O(1) extra memory.
     *
     * @param String[] $s
     * @return NULL
     */
    function reverseString(&$s)
    {
        $length = count($s);
        $start = 0;
        $end = $length - 1;

        while ($end > $start) {
            $temp = $s[$start];
            $s[$start] = $s[$end];
            $s[$end] = $temp;

            $end--;
            $start++;
        }

        return $s;
    }

    /**
     * PART II
     *
     * Given a string and an integer k, you need to reverse the first k
     * characters for every 2k characters counting from the start of the
     * string. If there are less than k characters left, reverse all of them.
     * If there are less than 2k but greater than or equal to k characters,
     * then reverse the first k characters and left the other as original.
     *
     * Solution: Convert the string into an array by splitting the string into
     *           K parts. Iterate through each other part and reverse it,
     *           append the next block with the current reverse block to the
     *           overall result string and continue.
     *
     * @param String $s
     * @param Integer $k
     * @return String
     */
    function reverseStr($s, $k)
    {
        $str = str_split($s, $k);
        for ($i = 0; $i < count($str); $i += 2) {
            $res .= strrev($str[$i]) . $str[$i + 1];
        }

        return $res;
    }

    /**
     * PART III
     *
     * Given a string, you need to reverse the order of characters in each word
     * within a sentence while still preserving whitespace and initial word
     * order.
     *
     * @param String $s
     * @return String
     */
    function reverseWords($s)
    {
        $str = explode(" ", $s);
        $res = [];
        foreach ($str as $word) {
            $res[] = strrev($word);
        }
        return implode(" ", $res);
    }
}

if (!function_exists('firstUniqChar')) {
    /**
     * Given a string, find the first non-repeating
     * character in it and return
     * it's index. If it doesn't exist, return -1.
     *
     * @param String $s
     * @return Integer
     */
    function firstUniqChar($s)
    {
        $hash = [];
        for ($i = 0; $i < strlen($s); $i++) {
            if (isset($hash[$s[$i]])) {
                $hash[$s[$i]] = -1;
            } else {
                $hash[$s[$i]] = $i;
            }
        }

        foreach ($hash as $char) {
            if ($char >= 0) return $char;
        }

        return -1;
    }
}

if (!function_exists('frequencySort')) {
    /**
     * Given a string, sort it in decreasing order based on
     * the frequency of characters.
     *
     * @param string $s
     *
     * @return void
     */
    function frequencySort($s)
    {
        $chars = str_split($s, 1);
        $frequencies = array_count_values($chars);
        arsort($frequencies);
        $result = "";
        foreach ($frequencies as $char => $frequency) {
            $result .= str_repeat($char, $frequency);
        }
        return $result;
    }
}

if (!function_exists('topKElements')) {
    /**
     * Given a non-empty array of integers, return the k most
     * frequent elements. (Top k most frequent)
     *
     * @param Integer[] $nums
     * @param Integer $k
     * @return Integer[]
     */
    function topKFrequent($nums, $k)
    {
        $frequencies = array_count_values($nums);
        arsort($frequencies);
        $topKFrequencies = array_slice($frequencies, 0, $k, true);
        $topKFrequentElements = array_keys($topKFrequencies);
        return $topKFrequentElements;
    }
}

if (!function_exists('wordFrequency')) {
    function wordFrequency($string)
    {
        $words = explode(" ", $string);
        $frequencies = array_count_values($words);
        arsort($frequencies);
        return $frequencies;
    }
}

if (!function_exists('findKthLargest')) {
    /**
     * @param Integer[] $nums
     * @param Integer $k
     * @return Integer
     */
    function findKthLargest($nums, $k)
    {
        rsort($nums);
        return $nums[$k - 1];
    }
}

if (!function_exists('quickSort')) {
    /**
     * @param array $arr
     * @return array
     */
    function quickSort($arr, $comparisonFunc = null)
    {
        $lowerThanPivot = $greaterThanPivot = [];

        if (count($arr) < 2) {
            return $arr;
        }

        $pivotKey = key($arr);
        $pivotValue = array_shift($arr);

        foreach ($arr as $key => $value) {
            $comparison = $comparisonFunc ? $comparisonFunc($value, $pivotValue) : $value <=> $pivotValue;
            if ($comparison <= 0) {
                $lowerThanPivot[$key] = $value;
            } elseif ($comparison === 1) {
                $greaterThanPivot[$key] = $value;
            }
        }

        return array_merge(
            quickSort($lowerThanPivot),
            [$pivotKey => $pivotValue],
            quickSort($greaterThanPivot)
        );
    }
}
