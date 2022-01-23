<?php 
namespace Tests;

use InterviewTask\Classes\BankTransaction;
use PHPUnit\Framework\TestCase;

final class VerifyKeyTest extends TestCase
{   
    use TestTrait;

    public function testVerifyKey()
    {
        $bankTransaction = new BankTransaction();
        $this->assertTrue($this->callMethod($bankTransaction, 'verifyKey', ['U6BD3M75FD']));
        $this->assertFalse($this->callMethod($bankTransaction, 'verifyKey', ['NUF5V6PT3U']));
    }
}

