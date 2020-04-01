<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Reader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\Filesystem\Filesystem;

class XlsProcessor
{
    /**
     * @param String $path
     * @return Spreadsheet
     * @throws Reader\Exception
     */
    public function getSpreadSheet(String $path): Spreadsheet
    {
        $spreadsheet = new Reader\Xlsx();
        return $spreadsheet->load($path);
    }

    /**
     * @return Spreadsheet
     */
    public function createSpreadSheet(): Spreadsheet
    {
        return new Spreadsheet();
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @param String $path
     * @param String $fileName
     * @throws Exception
     */
    public function save(Spreadsheet $spreadsheet, String $path, String $fileName): void
    {
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($path )) {
            $fileSystem->mkdir($path);  //  0777 permissions by default
        }

        $writer = new Writer\Xlsx($spreadsheet);
        $writer->save($path  . '/' . $fileName);
    }
}
