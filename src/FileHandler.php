<?php

require 'FileDownloader.php';
require 'ExcelReader.php';
require 'CurrencyConverter.php';

class FileHandler
{
    private $fileDownloader;
    private $excelReader;
    private $currencyConverter;
    private $crud;

    public function __construct()
    {
        $this->fileDownloader = new FileDownloader();
        $this->excelReader = new ExcelReader();
        $this->currencyConverter = new CurrencyConverter();
        $this->crud = new CRUD();
    }

    public function processExcelFile($url, $savePath)
    {
        // Download the file
        $this->fileDownloader->downloadFile($url, $savePath);

        // Read the downloaded Excel file
        $result = $this->excelReader->readExcel($savePath);
        if (is_array($result)) {
            $headers = $result['headers'];
            $data = $result['data'];
            $priceIndex = array_search('Hinta', $headers);
            $euroToGbp = $this->currencyConverter->convertEurToGbp();
            $this->crud->batchInsertUpdate($data, $priceIndex, $euroToGbp);
        } else {
            echo $result;
        }
    }
}
