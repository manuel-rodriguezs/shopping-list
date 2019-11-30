<?php

namespace App\Tests\Service;

use App\Service\StringUtils;
use PHPUnit\Framework\TestCase;

class StringUtilsTest extends TestCase
{
    public function testRemoveAccentsAndLowerCase() : void
    {
        $stringUtils = new StringUtils();

        $this->assertEquals(
            $stringUtils->removeAccentsAndLowerCase("ÁÉÍÓÚ"), "aeiou"
        );
    }
}