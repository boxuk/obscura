<?php

namespace BoxUK\Obscura;

/**
 * Contains logic for determining RGBA color coordinates from a mixed value source.
 *
 * @copyright Copyright (c) 2010, Box UK
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @link http://github.com/boxuk/obscura
 * @since 1.0
 */
class ColorParser {
    
    /**
     * Attempts to determine an RGBA color from a mixed variable
     *
     * @param mixed $color
     *
     * @return array
     *
     * @throws InvalidArgumentException if a color cannot be determined
     */
    public function getRGBAValuesFromColor( $color ) {

        if(is_array($color)) {
            $colorArray = $this->parseArrayForRGBAValues( $color );
        }

        else if(is_string($color)) {
            $colorArray = $this->parseStringForRGBAValues( $color );
        }
        else {
            throw new \InvalidArgumentException('Expected a valid color value.');
        }

        if(! is_array($colorArray) || count($colorArray) !== 4) {
            throw new \InvalidArgumentException('Could not parse color from value.');
        }

        return $colorArray;

    }

    /**
     * Examines the content of the given array to determine if it contains valid RGB color data
     *
     * @param array $color
     *
     * @return array | false
     */
    private function parseArrayForRGBAValues( array $color ) {

        if(count($color) < 3 || count($color) > 4) {
            return false;
        }

        // Get rid of any keys
        $color = array_values($color);

        // Assume no transparancy if none has been given
        if(count($color) === 3) {
            $color[] = 0;
        }

        return $color;

    }

    /**
     * Examines the given string to determine if it contains valid RGB data
     *
     * @param string $color
     *
     * @return array | false
     */
    private function parseStringForRGBAValues( $color ) {

        if($this->isHexadecimalColor( $color )) {
            return $this->hexadecimalStringToRGBArray( $color );
        }

        return false;

    }

    /**
     * Uses code from http://uk3.php.net/manual/en/function.imagecolorallocate.php#69751
     *
     * @param string $hexadecimal
     *
     * @return array | false
     */
    private function hexadecimalStringToRGBArray( $hexadecimal ) {

        $hexadecimal = str_replace('#', '', substr($hexadecimal, 0, 7));

        $decimal = hexdec($hexadecimal);

        return array(
            0xFF & ($decimal >> 0x10),
            0xFF & ($decimal >> 0x8),
            0xFF & $decimal,
            0 // Assume no transparency
        );
    }

    /**
     * Determines if the given string is a valid hexadecimal color (like FF9900 or #CCCCCC)
     *
     * Uses code from http://uk3.php.net/manual/en/function.imagecolorallocate.php#83338
     *
     * @param string $color
     *
     * @return boolean
     */
    private function isHexadecimalColor($color) {

        $color = substr($color, 0, 7);

        return preg_match('/#*[0-9a-fA-F]{6}/', $color);

    }

}
