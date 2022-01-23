<?php
namespace InterviewTask\Classes;

use InterviewTask\Interfaces\TransactionInterface;

class BankTransaction implements TransactionInterface
{
    const VALID_CHARS = [
        2, 3, 4, 5, 6, 7, 8, 9,
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 
        'H', 'J', 'K', 'L', 'M', 'N', 'P',
        'Q', 'R', 'S', 'T', 'U', 'V', 'W',
        'X', 'Y', 'Z'
    ];

    public $originalDateTime; //use for sort, strtotime only works with 'Y-m-d' format
    public $dateTime;
    public $transactionCode;
    public $customerNumber;
    public $reference;
    public $amount;

    //extra attributes will be used in front end
    public $codeValid = false;
    public $isDebit = false;

    public function __construct($data = [])
    {
        if ($data) {
            $this->setAttributes($data);
            $this->parseAttributes();
        }
    }

    public function setAttributes($data)
    {
        $this->originalDateTime = $data[0];
        $this->dateTime = $data[0];
        $this->transactionCode = $data[1];
        $this->customerNumber = $data[2];
        $this->reference = $data[3];
        $this->amount = $data[4];
    }

    public function parseAttributes()
    {
        $this->dateTime = $this->dateTime ? date('d/m/Y g:iA', strtotime($this->dateTime)) : '';
        $this->codeValid = $this->transactionCode ? $this->verifyKey($this->transactionCode) : false;
        if ($this->amount) {
            $this->amount = number_format($this->amount / 100, '2', '.', '');
            if ($this->amount < 0) {
                $this->amount = str_replace('-', '-$', $this->amount);
                $this->isDebit = true;
            } else {
                $this->isDebit = false;
                $this->amount = sprintf('$%s', $this->amount);
            }
        }
    }

    private function verifyKey($key)
    {
        if (strlen($key) != 10) {
            return false;
        }
        $checkDigit = $this->generateCheckCharacter(substr(strtoupper($key), 0, 9));
        return $key[9] == $checkDigit;
    }

    private function generateCheckCharacter($input)
    {
        $factor = 2;
        $sum = 0;
        $n = count(self::VALID_CHARS);
        
        for ($i = strlen($input) - 1; $i >= 0; $i--) {
            $codePoint = array_search($input[$i], self::VALID_CHARS);
            $codePoint = $codePoint === false ? -1 : $codePoint;
            $addend = $factor * $codePoint;
            $factor = $factor == 2 ? 1 : 2;
            $addend = ($addend / $n) + ($addend % $n);
            $sum += $addend;
        }

        $remainder = $sum % $n;
        $checkCodePoint = ($n - $remainder) % $n;
        return self::VALID_CHARS[$checkCodePoint];
    }
}