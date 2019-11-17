<?php


namespace App\Service;

class StringUtils
{
    public function removeAccentsAndLowerCase(string $string) : string
    {
        $string = str_replace(
            ["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"],
            ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"],
            $string
        );

        $string = strtolower($string);

        return $string;
    }
}