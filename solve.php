<?php
/**
 * GNOM + GNOM = SCALA; M = 3;
 * Words, such as "gnom", are Whole Numbers without leading zeros.
 * Each Character is a Unique Digit
 */
$word1 = 'gnom';
$word2 = 'scala';

$word1_len = strlen($word1);
$word2_len = strlen($word2);

$word1_map = makeCharacterMap($word1);
$word2_map = makeCharacterMap($word2);


$posibilities = getValidDoubledDigits($word1_len, $word2_len);

$posibilities = reduceByKnown('m', 3, $word1, $posibilities);

foreach ($word2_map as $char) {
    $posibilities = reduceByKnownPositions($char, $word2, $posibilities);
}

$posibilities = gnomgnomscala($posibilities);

$posibilities = reduceByCompare($posibilities);

$posibilities = reduceByCharSum($posibilities, $word1, $word2);

foreach ($posibilities as $gnom => $scala) {
    echo "GNOM = $gnom and SCALA = $scala";
}
/**
 * *****************************METHODS*********************************************
 */

/**
 * Turns a String to An  Array for each charcter. 
 * @param string $string
 * @return array
 */
function makeCharacterMap($string) {
    $len = strlen($string);
    $cmap = [];
    for ($i = 0; $i < $len; $i++) {

        $letter = substr($string, $i, 1);
        $cmap[] = $letter;
    }
    return $cmap;
}

/**
 * Returns an Array of Numbers where when added together have the correct number of digits in both.
 * @param int $digits
 * @param int $answerDigits
 * @return array
 */
function getValidDoubledDigits($digits, $answerDigits) {

    $min = str_pad('1', $digits, '0');
    $max = str_pad('9', $digits, '9');
    $valid = [];

    for ($i = $min; $i <= $max; $i++) {
        $double = $i * 2;

        if (strlen("$double") == $answerDigits) {
            $valid[] = $i;
        }
    }
    return $valid;
}

/**
 * Reduces an Array by removing values that are not compatible with a known character value.
 * @param type $char
 * @param type $num
 * @param type $word
 * @param type $array
 * @return type
 */
function reduceByKnown($char, $num, $word, $array) {
    $pos = getPositions($word, $char);
    foreach ($pos as $p) {


        foreach ($array as $key => $value) {
            if (substr($value, $p, 1) != $num) {

                unset($array[$key]);
            }

            if (substr_count($value, $p, 1) != count($pos)) {

                unset($array[$key]);
            }
        }
    }
    return \array_values($array);
}

function reduceByKnownPositions($char, $word, $array) {
    $pos = getPositions($word, $char);

    foreach ($array as $key => $value) {

        $value = $value * 2;
        foreach ($pos as $p) {
            if (!isset($answer)) {
                $answer = substr($value, $p, 1);
            }
            //echo $answer;
            //echo $p;
            if (substr($value, $p, 1) != $answer) {

                unset($array[$key]);
            }
        }
        unset($answer);
    }
    return \array_values($array);
}

function getPositions($word, $char) {
    $pos = [];
    for ($i = 0; $i <= \strlen($word) - 1; $i++) {

        if (\substr($word, $i, 1) == $char) {
            $pos[] = $i;
        }
    }
    return $pos;
}

/**
 * Creates a keyed array for gnom+gnom=scala. 
 * @param type $array
 * @return type
 */
function gnomgnomscala($array) {

    $new = [];

    foreach ($array as $a) {
        $new[$a] = $a * 2;
    }

    return $new;
}

/**
 * Check to see if a word has characters that are repeated in another word.
 * @param type $value1
 * @param type $value2
 * @return boolean
 */
function inTheOther($value1, $value2) {
    $value1 = makeCharacterMap($value1);
    $value2 = makeCharacterMap($value2);

    foreach ($value1 as $a) {
        foreach ($value2 as $b) {
            if ($a == $b) {
                return true;
            }
        }
    }
    return false;
}

/**
 * 
 * reduces an array by removing elements with keys have characters that are used the value.
 */
function reduceByCompare($array) {

    foreach ($array as $a => $b) {
        if (inTheOther($a, $b)) {
            unset($array[$a]);
        }
    }
    return $array;
}

/**
 * This method returns the sum of each characters count added together for all characters.
 * example: abbc = 6 = 1+2+2+1; b is used twice and appears twice.
 * @param string $string
 * @return int
 */
function getCharacterSum($string) {
    $stringMap = makeCharacterMap($string);
    $cnt = 0;
    foreach ($stringMap as $char) {
        $cnt = $cnt + substr_count($string, $char);
    }
    return $cnt;
}

/**
 * This checks that the patern of GNOM and SCALA in a keyed gnomgnomscala array have valid character counts and throws them out if not.
 */
function reduceByCharSum($array, $word1, $word2) {
    foreach ($array as $a => $b) {
        if (getCharacterSum($a) != getCharacterSum($word1)) {
            unset($array[$a]);
        }

        if (getCharacterSum($b) != getCharacterSum($word2)) {
            unset($array[$a]);
        }
    }
    return $array;
}
