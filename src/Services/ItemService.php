<?php


namespace App\Services;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ItemService
{
    public function generateKey(string $string)
    {
        $string = str_replace(
            ["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"],
            ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"],
            $string
        );

        return strtolower($string);
    }

    public function validateDescription(string $string)
    {
        $validator = Validation::createValidator();

        $errors = $validator->validate(trim($string), [
            new NotBlank(),
            new Regex([
                'pattern' => "/^[\w\d\s]*$/",
                'message' => 'You are using characters not allowed',
            ])
        ]);

        if (count($errors) > 0) {
            $message = "";
            foreach ($errors as $error)
            {
                $message .= $error->getMessage()."\r";
            }

            throw new \Exception($message);
        }
    }
}