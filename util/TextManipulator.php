<?php

namespace app\util;


class TextManipulator
{
    private $outputText;

    const PAN_REGEX = "/\d{4}(\s|-)?\d{4}(\s|-)?\d{4}(\s|-)?\d{4}(\s|-)?(\d{2})?/";

    /**
     * TextManipulator constructor.
     * @param $text
     */
    private function __construct($text)
    {
        $this->outputText = $text;
    }

    public function __toString()
    {
        return $this->getOutputText();
    }

    /**
     * @param $text
     * @return static
     */
    public static function createFromString($text)
    {
        return new static($text);
    }

    /**
     * @return $this
     */
    public function lowerCase()
    {
        $this->outputText = mb_strtolower($this->outputText);

        return $this;
    }

    /**
     * @param array $words
     */
    private function createOutput(array $words)
    {
        $this->outputText = implode(' ', $words);
    }

    /**
     * @return string
     * @return string
     */
    public function getOutputText()
    {
        return $this->outputText;
    }

    /**
     * @return array|false|string[]
     * @deprecated for bad split logic
     * @see
     */
    public function getWords()
    {
        return mb_split('[\W+]', $this->outputText);
    }

    /**
     * @return array|false|string[]
     */
    public function getNaturalWords()
    {
        return mb_split('[\s|.|,|!|\-|?]+', $this->outputText);
    }

    /**
     * @return $this
     * removes  "he11o" "asd*#$asd" preserving letter only sequences
     */
    public function removeNonWords()
    {
        $words = $this->getNaturalWords();

        $nonWordFilter = function ($word) {
            return !mb_ereg_match('/[\W|\d]/', $word);
        };

        $filteredWords = array_filter($words, $nonWordFilter);
        $this->createOutput($filteredWords);

        return $this;
    }

    public function removeQuotes()
    {
        $this->outputText = str_replace(["'", '"'], [' ', ' '], $this->outputText);

        return $this;
    }

    public function removeBraces()
    {
        $this->outputText = str_replace(["(", ')'], [' ', ' '], $this->outputText);

        return $this;
    }

    /**
     * @return $this
     */
    public function removeNumbers()
    {
        $this->outputText = preg_replace('/[0-9]/', '', $this->outputText);

        return $this;
    }

    /**
     * @return TextManipulator
     */
    public function uniqueWords()
    {
        $words = $this->getWords();
        $unique = array_unique($words);
        $this->outputText = implode(' ', $unique);

        return $this;
    }

    /**
     * @return TextManipulator
     */
    public function sortWordsByLengthAsc()
    {
        return $this->sortWordsByLength(true);
    }

    /**
     * @return TextManipulator
     */
    public function sortWordsByLengthDesc()
    {
        return $this->sortWordsByLength(false);
    }

    /**
     * @param bool $asc
     * @return $this
     */
    private function sortWordsByLength($asc = true)
    {
        $words = $this->getWords();

        $sorter = function ($a, $b) use ($asc) {
            return $asc
                ? mb_strlen($a) > mb_strlen($b)
                : mb_strlen($a) < mb_strlen($b);
        };

        usort($words, $sorter);
        $this->outputText = implode(' ', $words);

        return $this;
    }

    /**
     * @return $this
     */
    public function removePans()
    {
        $this->outputText = preg_replace(static::PAN_REGEX, '', $this->outputText);

        return $this;
    }

    /**
     * @param string $replacement
     * @return $this
     */
    public function replacePans($replacement = '')
    {
        $this->outputText = preg_replace(static::PAN_REGEX, $replacement, $this->outputText);

        return $this;
    }

    /**
     * @return $this
     */
    public function shakeWords()
    {
        $words = $this->getNaturalWords();
        shuffle($words);
        $this->outputText = implode(' ', $words);

        return $this;
    }

    /**
     * @param $length
     * @param int $start
     * @return $this
     */
    public function crop($length, $start = 0)
    {
        $this->outputText = mb_substr($this->outputText, $start, $length);

        return $this;
    }

    public static function isWord($input)
    {
        return preg_match('/^([a-zA-Zа-яА-Я]+)/i', $input) === 1;

    }
}
