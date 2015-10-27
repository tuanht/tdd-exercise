<?php

namespace Calculator;

class StringCalculator implements StringCalculatorInterface
{
    private $str;

    public function setString($str)
    {
        $this->str = $str;
    }

    public function getValue()
    {
        return $this->stringToValue($this->str);
    }

    private function stringToValue($str)
    {
        if (empty($this->str)) {
            return 0;
        }

        if (is_numeric($this->str)) {
            return $this->parseStringToValue($this->str);
        }

        $split = explode(',', $this->str);
        if (count($split) > 1) {
            return $this->sum($split);
        }

        $match = preg_match("/^[\/\/]/", $this->str);
        if ($match === 1) {
            $split = $this->splitByNewLine($this->str);
            $delimiter = $this->extractDelimiter($split[0]);
            return $this->sum(explode($delimiter, $split[1]));
        }

        $pregSplit = preg_split("/[\r|\n]+/", $this->str);
        if (count($pregSplit) > 1) {
            return $this->sum($pregSplit);
        }
    }

    private function extractDelimiter($str)
    {
        if (substr($str, 0, 3) == '//[' && substr($str, count($str) - 1, 1)) {
            return substr($str, 3, count($str) - 2);
        }

        return substr($str, 2, 1);
    }

    private function splitByNewLine($str)
    {
        return preg_split("/[\r|\n]+/", $str);
    }

    private function parseStringToValue($str)
    {
        $val = intval(trim($str));

        if ($val < 0) {
            throw new \InvalidArgumentException();
        }

        if ($val >= 1000) {
            return 0;
        }

        return $val;
    }

    private function sum(array $array)
    {
        $sum = 0;
        foreach ($array as $str) {
            $sum += $this->parseStringToValue($str);
        }
        return $sum;
    }
}
