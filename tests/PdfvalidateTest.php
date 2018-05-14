<?php

namespace Ottosmops\Pdfvalidate\Test;

use PHPUnit\Framework\TestCase;

use Ottosmops\Pdfvalidate\Validator;

use Ottosmops\Pdfvalidate\Exceptions\FileNotFound;
use Ottosmops\Pdfvalidate\Exceptions\BinaryNotFound;
use Ottosmops\Pdfvalidate\Exceptions\FileFormatNotAllowed;

class PdfvalidateTest extends TestCase
{
    protected $src_path = __DIR__.'/testfiles/';

    protected $correct_file = 'correct.pdf';

    protected $slightly_corrupted = 'last_page_corrupted.pdf'; // we can open the pdf, but the last page is not fully rendered

    protected $corrupted = 'corrupted-pdf.pdf';

    /** @test */
    public function it_validates_pdf()
    {
        putenv('PATH=$PATH:/usr/local/bin/:/usr/bin');
        $validator = new Validator($this->src_path . $this->correct_file);
        $actual = $validator->check();
        $this->assertTrue($actual);
    }

    /** @test */
    public function slightly_corrupted_pdf()
    {
        putenv('PATH=$PATH:/usr/local/bin/:/usr/bin');
        $validator = new Validator($this->src_path . $this->slightly_corrupted);
        $actual = $validator->check();
        $this->assertFalse($actual);
        $this->assertSame($validator->error, "Could open the PDF, but the pdf seems to be corrupted.");
    }

    /** @test */
    public function corrupted_pdf()
    {
        putenv('PATH=$PATH:/usr/local/bin/:/usr/bin');
        $validator = new Validator($this->src_path . $this->corrupted);
        $actual = $validator->check();
        $this->assertFalse($actual);
        $this->assertSame($validator->error, "Could not open the PDF.");
    }



}
