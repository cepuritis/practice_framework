<?php

use PHPUnit\Framework\TestCase;

class DataCollectionTest extends TestCase
{
    public function testValueInsertedAndRetrievedAsExpected()
    {
        $dc = new \Core\Models\Data\DataCollection(['first_name' => 'Aigars', 'lastName' => 'Cepuritis']);

        $this->assertSame('Aigars', $dc->getFirstName());
        $this->assertSame('Cepuritis', $dc->getLastName());

        $dc->setFirstName('test');

        $this->assertSame(2, count($dc->getArray()));

        $dc->setLanguage('Latvian');

        $this->assertSame('Latvian', $dc->getLanguage());
        $this->assertSame('test', $dc['first_name']);
    }

    public function testEscapeFunctionWorks()
    {
        $script = '<script>alert("Hello World" </script>';
        $dc = new \Core\Models\Data\DataCollection(['html' => $script]);

        $this->assertSame(htmlspecialchars($script), $dc->e('html'));
    }
}