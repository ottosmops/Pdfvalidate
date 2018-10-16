<?php

namespace Ottosmops\Pdfvalidate;

use Ottosmops\Pdfvalidate\Exceptions\BinaryNotFound;
use Ottosmops\Pdfvalidate\Exceptions\FileNotFound;
use Ottosmops\Pdfvalidate\Exceptions\MimeTypeIsNotPdf;
use Symfony\Component\Process\Process;

class Validator
{

    public $file;

    public $error = '';

    public $process = '';

    public $output;

    public $executable;

    public $timeout;

    public function __construct($file, $executable = '', $timeout = 60)
    {
        $this->executable = $executable ? $executable : 'pdftocairo';
        $this->checkExecutable();
        if (!is_file($file)) {
            throw new FileNotFound($file);
        }
        if (!self::is_pdf($file)) {
            throw new MimeTypeIsNotPdf($file);
        }
        $this->file    = $file;
        $this->timeout = $timeout;
    }

    public static function validate($file, $executable = '', $timeout = 60)
    {
        return (new static($file, $executable, $timeout));
    }

    public function checkExecutable()
    {
        $process = new Process('which ' . $this->executable);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new BinaryNotFound($process);
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
        $command = $this->executable . ' -pdf "' . $this->file . '" - 2>&1 >/dev/null';

        $process = new Process($command);
        $process->setTimeout($this->timeout);
        $process->run();

        $output = $process->getOutput();

        if (!$output) {
            return true;
        };

        $this->error = $output;

        if (mb_strpos($output, 'Error') !== false) {
            $this->error = "Could open the PDF, but the PDF seems to be corrupted.";
        }

        if (mb_strpos($output, 'Error opening') !== false) {
            $this->error = "Could not open the PDF.";
        }

        return false;
    }
}
