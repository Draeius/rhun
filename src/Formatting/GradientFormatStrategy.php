<?php

namespace App\Formatting;

/**
 * Description of GradientFormatStrategy
 *
 * @author Draeius
 */
class GradientFormatStrategy implements FormatStrategy {

    public function start(string $string): string {
        if (!empty($string)) {
            if ($string[0] != '`' || mb_substr($string, 1, 1) != '°') {
                return $string;
            }
        }
        if (strlen($string) < 16) {
            return $string;
        }
        $colorStart = mb_substr($string, 2, 6);
        $colorEnd = mb_substr($string, 9, 6);

        $string = mb_substr($string, 15);

        $positionTag = mb_strpos($string, '<');

        $steps = $positionTag === false ? mb_strlen($string) : $positionTag;
        if ($steps <= 1) {
            return $string;
        }

        $startRGB = $this->hex2RGB($colorStart);
        $endRGB = $this->hex2RGB($colorEnd);

        $stepR = ($endRGB['red'] - $startRGB['red']) / ($steps - 1);
        $stepG = ($endRGB['green'] - $startRGB['green']) / ($steps - 1);
        $stepB = ($endRGB['blue'] - $startRGB['blue']) / ($steps - 1);

        return $this->processString($string, $startRGB, $stepR, $stepG, $stepB, $positionTag === false ? -1 : $positionTag + 1);
    }

    public function closeForStrategy(FormatStrategy $nextStrategy): bool {
        return false;
    }

    public function end(string $string): string {
        return 'test'; //$string;
    }

    public function needsClosing(): bool {
        return false;
    }

    public function openAfterStrategy(FormatStrategy $strategy): bool {
        return true;
    }

    private function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }

    private function processString($string, $startRGB, $stepR, $stepG, $stepB, $limit) {
        $R = $startRGB['red'];
        $G = $startRGB['green'];
        $B = $startRGB['blue'];

        $array = preg_split('//u', $string);
        $length = count($array);

        for ($index = 1; $index < $length - 1 && $index != $limit; $index++) {
            if ($array[$index] !== " ") {
                $array[$index] = '<span style="color: ' . sprintf("#%02x%02x%02x", round($R), round($G), round($B)) . ';">' . $array[$index] . "</span>";
                $R += $stepR;
                $G += $stepG;
                $B += $stepB;
            }
        }
        return implode('', $array);
    }

}
