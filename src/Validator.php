<?php

namespace Ottosmops\Pdfvalidate;

use Ottosmops\Pdfvalidate\Exceptions\PdfNotValide;
use Ottosmops\Pdfvalidate\Exceptions\FileNotFound;
use Ottosmops\Pdfvalidate\Exceptions\BinaryNotFound;
use Ottosmops\Pdfvalidate\Exceptions\MimeTypeIsNotPdf;
use Symfony\Component\Process\Process;

class Validator {

    public $file;

    public $error = '';

    public $process = '';

    public $output;

    public $executable;

    public function __construct($file, $executable = 'pdftocairo')
    {
        $this->executable = $executable;
        $this->checkExecutable();
        if (!is_file($file)) {
            throw new FileNotFound($file);
        }
        if (!self::is_pdf($file)) {
            throw new MimeTypeIsNotPdf($file);
        }
        $this->file = $file;
    }

    public function checkExecutable()
    {
        $process = new Process('which ' . $this->executable);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new BinaryNotFound($process);
            return false;
        }

        return true;
    }


    public static function is_pdf($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return finfo_file($finfo, $file) == 'application/pdf';
    }

    public function check()
    {
        $command = $this->executable . ' -pdf '. $this->file .' - 2>&1 >/dev/null';

        $process = new Process($command);
        $process->run();

        $output = $process->getOutput();

        if (!$output) {
            return true;
        };

        $error = 'Error opening';
        if (mb_strpos($output, $error) !== false) {
            $this->error = "Could not open the PDF.";
            $this->output = $output;
            return false;
        }

        $error = 'Error';
        if (mb_strpos($output, $error) !== false) {
            $this->error = "Could open the PDF, but the pdf seems to be corrupted.";
            $this->output = $output;
            return false;
        }

        return false;
    }

}