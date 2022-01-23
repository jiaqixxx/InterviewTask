<?php
namespace InterviewTask\Services;

use InterviewTask\Classes\TransactionCollection;

abstract class AbstractFileImportSvc
{
    protected $file;
    protected $collection;
    protected $svc = null;

    public function __construct($file)
    {
        $this->file = $file;
        $this->collection = new TransactionCollection();
        if (isset($this->file['name']) && isset($this->file['tmp_name'])) {
            $ext = pathinfo($this->file['name'], PATHINFO_EXTENSION);

            switch ($ext) {
                case 'csv':
                    $this->svc = new CsvImportSvc($this->file['tmp_name']);
                    break;
                case 'json':
                    //instantiate JsonImportSvc here
                    break;
                default:
                    break;
            }
    
            if ($this->svc) {
                $this->collection = $this->svc->handleFileData();
            }
        }
    }

    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * specific file import service has its own logic to parse data
     */
    abstract public function handleFileData();
}