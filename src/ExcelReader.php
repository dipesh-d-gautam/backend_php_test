<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

class ExcelReader
{
    public function readExcel($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            $headerRow = $sheet->getRowIterator(4)->current(); // Get headers from 4th row
            $cellIterator = $headerRow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            // Initialize an array to hold the header names
            $headers = [];
            foreach ($cellIterator as $cell) {
                $headers[] = $cell->getValue();
            }

            $data = []; // Store rows of data
            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($index < 5) {
                    continue;
                }
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // Allow empty cells
                $rowData = []; // Store cell values for the current row
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $data[] = $rowData; // Add row to the data array
            }
            $result = array('headers' => $headers, 'data' => $data);
            return $result; // Return all rows of data
        } catch (ReaderException $error) {
            return 'Error loading file: ' . $error->getMessage();
        }
    }
}
