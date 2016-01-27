<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 9/16/14
 * Time: 10:20 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Assert\Assertions;

use NilPortugues\Assert\Exceptions\AssertionException;

class StringAssertions
{
    const ASSERT_STRING = 'Value must be a string.';
    const ASSERT_IS_ALPHANUMERIC = 'Value may only contain letters and digits.';
    const ASSERT_IS_ALPHA = 'Value may only contain letters.';
    const ASSERT_IS_BETWEEN = 'Value must be between %s and %s characters.';
    const ASSERT_IS_CHARSET = 'Value charset is not valid.';
    const ASSERT_IS_ALL_CONSONANTS = 'Value may only have consonants.';
    const ASSERT_CONTAINS = 'Value was not found.';
    const ASSERT_IS_CONTROL_CHARACTERS = 'Value may only have control characters.';
    const ASSERT_IS_DIGIT = 'Value must be all digits.';
    const ASSERT_ENDS_WITH = 'Value does not end with %S';
    const ASSERT_EQUALS = 'Value and %s must match.';
    const ASSERT_IN = 'The selected %s is invalid.';
    const ASSERT_HAS_GRAPHICAL_CHARS_ONLY = 'Value may only have graphical characters.';
    const ASSERT_HAS_LENGTH = 'Value must be %s characters.';
    const ASSERT_IS_LOWERCASE = 'Value may only contain lower-cased letters.';
    const ASSERT_NOT_EMPTY = 'Value is empty.';
    const ASSERT_NO_WHITESPACE = 'Value has white spaces.';
    const ASSERT_HAS_PRINTABLE_CHARS_ONLY = 'Value may only have printable characters.';
    const ASSERT_IS_PUNCTUATION = 'Value may only have punctuation symbols.';
    const ASSERT_MATCHES_REGEX = 'Value format is invalid.';
    const ASSERT_IS_SLUG = 'Value does not match a valid slug expression.';
    const ASSERT_IS_SPACE = 'Value is not a space.';
    const ASSERT_STARTS_WITH = 'Value does not start with %s.';
    const ASSERT_IS_UPPERCASE = 'Value maybe only contain upper-cased letters.';
    const ASSERT_IS_VERSION = 'Value is not a valid version string.';
    const ASSERT_IS_VOWEL = 'Value may only contain vowels.';
    const ASSERT_IS_HEX_DIGIT = 'Value is not a valid hexadecimal value.';
    const ASSERT_HAS_LOWERCASE = 'Value does not have at least %s lower-cased characters.';
    const ASSERT_HAS_UPPERCASE = 'Value does not have at least %s upper-cased characters.';
    const ASSERT_HAS_NUMERIC = 'Value does not have at least %s numeric characters.';
    const ASSERT_HAS_SPECIAL_CHARACTERS = 'Value does not have at least %s special characters.';
    const ASSERT_IS_EMAIL = 'Value must be a valid email address.';
    const ASSERT_IS_URL = 'Value must be a valid URL.';
    const ASSERT_IS_UUID = 'Value must be a valid UUID.';

    /**
     * @param $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isString($value, $message = '')
    {
        if (false === is_string($value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_STRING
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isAlphanumeric($value, $message = '')
    {
        if (false === preg_match('/^[a-z0-9]+$/i', $value) > 0) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_ALPHANUMERIC
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isAlpha($value, $message = '')
    {
        if (false === preg_match('/^[a-z]+$/i', $value) > 0) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_ALPHA
            );
        }
    }

    /**
     * @param string $value
     * @param int    $min
     * @param int    $max
     * @param bool   $inclusive
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isBetween($value, $min, $max, $inclusive = false, $message = '')
    {
        settype($min, 'int');
        settype($max, 'int');
        settype($inclusive, 'bool');

        $length = mb_strlen($value, mb_detect_encoding($value));

        if ($min > $max) {
            throw new AssertionException(sprintf('%s cannot be less than %s for validation', $min, $max));
        }

        if (false === $inclusive) {
            if (false === $min < $length && $length < $max) {
                throw new AssertionException(
                    ($message) ? $message : sprintf(self::ASSERT_IS_BETWEEN, $min, $max)
                );
            }
        }

        if (false === $min <= $length && $length <= $max) {
            throw new AssertionException(
                ($message) ? $message : sprintf(self::ASSERT_IS_BETWEEN, $min, $max)
            );
        }
    }

    /**
     * @param string $value
     * @param string $charset
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isCharset($value, $charset, $message = '')
    {
        $available = mb_list_encodings();
        $charset = is_array($charset) ? $charset : array($charset);

        $charsetList = array_filter(
            $charset,
            function ($charsetName) use ($available) {
                return in_array($charsetName, $available, true);
            }
        );

        $detectedEncoding = mb_detect_encoding($value, $charset, true);

        if (false === in_array($detectedEncoding, $charsetList, true)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_CHARSET
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isAllConsonants($value, $message = '')
    {
        if (false === preg_match('/^(\s|[b-df-hj-np-tv-zB-DF-HJ-NP-TV-Z])+$/', $value) > 0) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_ALL_CONSONANTS
            );
        }
    }

    /**
     * @param string $value
     * @param string $contains
     * @param bool   $identical
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function contains($value, $contains, $identical = false, $message = '')
    {
        if (false === $identical) {
            if (false === (false !== mb_stripos($value, $contains, 0, mb_detect_encoding($value)))) {
                throw new AssertionException(
                    ($message) ? $message : self::ASSERT_CONTAINS
                );
            }
        }

        if (false === (false !== mb_strpos($value, $contains, 0, mb_detect_encoding($value)))) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_CONTAINS
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isControlCharacters($value, $message = '')
    {
        if (false === ctype_cntrl($value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_CONTROL_CHARACTERS
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isDigit($value, $message = '')
    {
        if (false === ctype_digit($value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_DIGIT
            );
        }
    }

    /**
     * @param string $value
     * @param string $contains
     * @param bool   $identical
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function endsWith($value, $contains, $identical = false, $message = '')
    {
        $enc = mb_detect_encoding($value);

        if (false === $identical) {
            if (false === (mb_strripos($value, $contains, -1, $enc) === (mb_strlen($value, $enc) - mb_strlen($contains,
                            $enc)))
            ) {
                throw new AssertionException(
                    ($message) ? $message : sprintf(self::ASSERT_ENDS_WITH, $contains)
                );
            }
        }

        if (false === (mb_strrpos($value, $contains, 0, $enc) === (mb_strlen($value, $enc) - mb_strlen($contains,
                        $enc)))
        ) {
            throw new AssertionException(
                ($message) ? $message : sprintf(self::ASSERT_ENDS_WITH, $contains)
            );
        }
    }

    /**
     * Validates if the input is equal some value.
     *
     * @param string $value
     * @param string $comparedValue
     * @param bool   $identical
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function equals($value, $comparedValue, $identical = false, $message = '')
    {
        if (false === $identical) {
            if (false === ($value == $comparedValue)) {
                throw new AssertionException(
                    ($message) ? $message : sprintf(self::ASSERT_EQUALS, $value)
                );
            }
        }

        if ($value !== $comparedValue) {
            throw new AssertionException(
                ($message) ? $message : sprintf(self::ASSERT_EQUALS, $value)
            );
        }
    }

    /**
     * @param string $value
     * @param string $haystack
     * @param bool   $identical
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function in($value, $haystack, $identical = false, $message = '')
    {
        $haystack = (string) $haystack;
        $enc = mb_detect_encoding($value);

        if (false === $identical) {
            if (false === (false !== mb_stripos($haystack, $value, 0, $enc))) {
                throw new AssertionException(
                    ($message) ? $message : sprintf(self::ASSERT_IN, $value)
                );
            }
        }

        if (false === (false !== mb_strpos($haystack, $value, 0, $enc))) {
            throw new AssertionException(
                ($message) ? $message : sprintf(self::ASSERT_IN, $value)
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function hasGraphicalCharsOnly($value, $message = '')
    {
        if (false === ctype_graph($value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_HAS_GRAPHICAL_CHARS_ONLY
            );
        }
    }

    /**
     * @param string $value
     * @param int    $length
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function hasLength($value, $length, $message = '')
    {
        settype($length, 'int');

        if (mb_strlen($value, mb_detect_encoding($value)) !== $length) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_HAS_LENGTH
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isLowercase($value, $message = '')
    {
        if ($value !== mb_strtolower($value, mb_detect_encoding($value))) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_LOWERCASE
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function notEmpty($value, $message = '')
    {
        $value = trim($value);

        if (empty($value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_NOT_EMPTY
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function noWhitespace($value, $message = '')
    {
        if (0 !== preg_match('/\s/', $value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_NO_WHITESPACE
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function hasPrintableCharsOnly($value, $message = '')
    {
        if (false === ctype_print($value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_HAS_PRINTABLE_CHARS_ONLY
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isPunctuation($value, $message = '')
    {
        if (false === ctype_punct($value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_PUNCTUATION
            );
        }
    }

    /**
     * @param string $value
     * @param string $regex
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function matchesRegex($value, $regex, $message = '')
    {
        if (false === preg_match($regex, $value) > 0) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_MATCHES_REGEX
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isSlug($value, $message = '')
    {
        if ((false !== strstr($value, '--'))
            || (!preg_match('@^[0-9a-z\-]+$@', $value))
            || (preg_match('@^-|-$@', $value))
        ) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_SLUG
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isSpace($value, $message = '')
    {
        if (false === ctype_space($value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_SPACE
            );
        }
    }

    /**
     * @param string $value
     * @param        $contains
     * @param bool   $identical
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function startsWith($value, $contains, $identical = false, $message = '')
    {
        $enc = mb_detect_encoding($value);

        if (false === $identical) {
            if (false === (0 === mb_stripos($value, $contains, 0, $enc))) {
                throw new AssertionException(
                    ($message) ? $message : sprintf(self::ASSERT_STARTS_WITH, $contains)
                );
            }
        }

        if (false === (0 === mb_strpos($value, $contains, 0, $enc))) {
            throw new AssertionException(
                ($message) ? $message : sprintf(self::ASSERT_STARTS_WITH, $contains)
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isUppercase($value, $message = '')
    {
        if ($value != mb_strtoupper($value, mb_detect_encoding($value))) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_UPPERCASE
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isVersion($value, $message = '')
    {
        if (false === preg_match('/^[0-9]+\.[0-9]+(\.[0-9]*)?([+-][^+-][0-9A-Za-z-.]*)?$/', $value) > 0) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_VERSION
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isVowel($value, $message = '')
    {
        if (false === preg_match('/^(\s|[aeiouAEIOU])*$/', $value) > 0) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_VOWEL
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isHexDigit($value, $message = '')
    {
        if (false === ctype_xdigit($value)) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_HEX_DIGIT
            );
        }
    }

    /**
     * @param string $value
     * @param int    $amount
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function hasLowercase($value, $amount = null, $message = '')
    {
        if (false === self::hasStringSubset($value, $amount, '/[a-z]/')) {
            throw new AssertionException(
                ($message) ? $message : sprintf(self::ASSERT_HAS_LOWERCASE, (null === $amount) ? 1 : $amount)
            );
        }
    }

    /**
     * @param string   $value
     * @param int|null $amount
     * @param string   $regex
     *
     * @return bool
     */
    private static function hasStringSubset($value, $amount, $regex)
    {
        $isInvalid = true;
        settype($value, 'string');

        $minMatches = 1;
        if (!empty($amount)) {
            $minMatches = $amount;
        }

        $value = preg_replace('/\s+/', '', $value);
        $length = strlen($value);

        $counter = 0;
        for ($i = 0; $i < $length; ++$i) {
            if (preg_match($regex, $value[$i]) > 0) {
                ++$counter;
            }

            if ($counter === $minMatches) {
                $isInvalid = true;
            }
        }

        return $isInvalid;
    }

    /**
     * @param string $value
     * @param int    $amount
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function hasUppercase($value, $amount = null, $message = '')
    {
        if (false === self::hasStringSubset($value, $amount, '/[A-Z]/')) {
            throw new AssertionException(
                ($message) ? $message : sprintf(self::ASSERT_HAS_UPPERCASE, (null === $amount) ? 1 : $amount)
            );
        }
    }

    /**
     * @param string $value
     * @param int    $amount
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function hasNumeric($value, $amount = null, $message = '')
    {
        if (false === self::hasStringSubset($value, $amount, '/[0-9]/')) {
            throw new AssertionException(
                ($message) ? $message : sprintf(self::ASSERT_HAS_NUMERIC, (null === $amount) ? 1 : $amount)
            );
        }
    }

    /**
     * @param string $value
     * @param int    $amount
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function hasSpecialCharacters($value, $amount = null, $message = '')
    {
        if (false === self::hasStringSubset($value, $amount, '/[^a-zA-Z\d\s]/')) {
            throw new AssertionException(
                ($message) ? $message : sprintf(self::ASSERT_HAS_SPECIAL_CHARACTERS, (null === $amount) ? 1 : $amount)
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isEmail($value, $message = '')
    {
        settype($value, 'string');

        if (false === preg_match('/^[A-Z0-9._%\-+]+@(?:[A-Z0-9\-]+\.)+(?:[A-Z0-9\-]+)$/i', $value) > 0) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_EMAIL
            );
        }
    }

    /**
     * @param string $value
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isUrl($value, $message = '')
    {
        if ($value[0] == $value[1] && $value[0] == '/') {
            $value = 'http:'.$value;
        }

        if (false === filter_var($value, FILTER_VALIDATE_URL, ['options' => ['flags' => FILTER_FLAG_PATH_REQUIRED]])) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_URL
            );
        }
    }

    /**
     * @param string $value
     * @param bool   $strict
     * @param string $message
     *
     * @throws AssertionException
     */
    public static function isUUID($value, $strict = true, $message = '')
    {
        settype($value, 'string');

        $pattern = '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/i';
        if (true !== $strict) {
            $value = trim($value, '[]{}');
            $pattern = '/^[a-f0-9]{4}(?:-?[a-f0-9]{4}){7}$/i';
        }

        if (false === preg_match($pattern, $value) > 0) {
            throw new AssertionException(
                ($message) ? $message : self::ASSERT_IS_UUID
            );
        }
    }
}