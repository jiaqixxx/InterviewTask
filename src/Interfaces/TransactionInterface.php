<?php
namespace InterviewTask\Interfaces;

interface TransactionInterface
{
    public function setAttributes($data);
    public function parseAttributes();
}