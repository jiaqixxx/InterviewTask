<?php
namespace InterviewTask\Services;

use InterviewTask\Classes\BankTransaction;

class CsvImportSvc extends AbstractFileImportSvc
{
    public function handleFileData()
    {
        if (($handler = fopen($this->file, "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handler, 0, ",")) !== FALSE) {
                $row++;
                if ($row == 1) {
                    continue;                    
                }
                $this->collection->add(new BankTransaction($data));
            }
            fclose($handler);
        }

        $this->collection = iterator_to_array($this->collection);
        usort($this->collection, [$this, 'sortByDateTime']);
        
        return $this->collection;
    }

    public static function sortByDateTime($a, $b)
    {  
        return strtotime($a->originalDateTime) - strtotime($b->originalDateTime);
    }
}