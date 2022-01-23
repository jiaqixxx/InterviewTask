<?php
namespace InterviewTask\Classes;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
use InterviewTask\Interfaces\TransactionInterface;

class TransactionCollection implements IteratorAggregate
{
    protected $transactions = [];

    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->transactions);
    }
 
    public function add(TransactionInterface $transaction)
    {
        $this->transactions[] = $transaction;
    }
}