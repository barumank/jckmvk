<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 28.05.19
 * Time: 15:16
 */

namespace Backend\Library;


class HtmlHelper
{

    function printTruncated($maxLength, $html, $isUtf8 = true)
    {
        $printedLength = 0;
        $position = 0;
        $tags = array();

        if (mb_strlen($html) > $maxLength) {
            $maxLength = mb_stripos(strip_tags($html), ' ', $maxLength);
        }

        // For UTF-8, we need to count multibyte sequences as one character.
        $re = $isUtf8
            ? '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;|[\x80-\xFF][\x80-\xBF]*}'
            : '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}';

        $result = '';
        while ($printedLength < $maxLength && preg_match($re, $html, $match, PREG_OFFSET_CAPTURE, $position)) {
            list($tag, $tagPosition) = $match[0];

            // Print text leading up to the tag.
            $str = mb_substr($html, $position, $tagPosition - $position);
            if ($printedLength + mb_strlen($str) > $maxLength) {
                $result .= mb_substr($str, 0, $maxLength - $printedLength);
                $printedLength = $maxLength;
                break;
            }

            $result .= $str;
            $printedLength += mb_strlen($str);
            if ($printedLength >= $maxLength) break;

            if ($tag[0] == '&' || ord($tag) >= 0x80) {
                // Pass the entity or UTF-8 multibyte sequence through unchanged.
                $result .= $tag;
                $printedLength++;
            } else {
                // Handle the tag.
                $tagName = $match[1][0];
                if ($tag[1] == '/') {
                    // This is a closing tag.
                    $openingTag = array_pop($tags);
                    assert($openingTag == $tagName); // check that tags are properly nested.

                    $result .= $tag;
                } else if ($tag[mb_strlen($tag) - 2] == '/') {
                    // Self-closing tag.
                    $result .= $tag;
                } else {
                    // Opening tag.
                    $result .= $tag;
                    $tags[] = $tagName;
                }
            }

            // Continue after the tag.
            $position = $tagPosition + mb_strlen($tag);
        }

        // Print any remaining text.
        if ($printedLength < $maxLength && $position < mb_strlen($html))
            $result .= mb_substr($html, $position, $maxLength - $printedLength);

        // Close any open tags.
        while (!empty($tags))
            $result .= sprintf('</%s>', array_pop($tags));

        return $result;
    }

    function printTruncatedText($maxLength, $text)
    {
        $text = strip_tags($text);
        if (mb_strlen($text) > $maxLength) {
            $maxLength = mb_stripos($text, ' ', $maxLength);
        }
        return mb_substr($text, 0, $maxLength);
    }

    function printTruncatedTextFromIndex($maxLength, $text)
    {
        $text = strip_tags($text);
        $len = mb_strlen($text);
        if ($len > $maxLength) {
            $maxLength = mb_stripos($text, ' ', $maxLength);
        }
        return mb_substr($text, $maxLength, $len-$maxLength);
    }
}