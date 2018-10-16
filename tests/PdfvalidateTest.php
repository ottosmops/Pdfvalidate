<?php

namespace Ottosmops\Pdfvalidate\Test;

use Ottosmops\Pdfvalidate\Exceptions\BinaryNotFound;
use Ottosmops\Pdfvalidate\Exceptions\FileNotFound;
use Ottosmops\Pdfvalidate\Exceptions\MimeTypeIsNotPdf;
use Ottosmops\Pdfvalidate\Validator;
use PHPUnit\Framework\TestCase;

class PdfvalidateTest extends TestCase
{
    protected $src_path = __DIR__ . '/testfiles/';

    protected $correct_file = 'correct.pdf';

    protected $slightly_corrupted = 'last_page_corrupted.pdf'; // we can open the pdf, but the last page is not fully rendered

    protected $corrupted = 'corrupted-pdf.pdf';

    /** @test */
    public function it_validates_pdf()
    {
        putenv('PATH=$PATH:/usr/local/bin/:/usr/bin');
        $validator = new Validator($this->src_path . $this->correct_file);
        $actual    = $validator->check();
        $this->assertTrue($actual);
    }

    /** @test */
    public function it_validates_pdf_with_execution_time()
    {
        putenv('PATH=$PATH:/usr/local/bin/:/usr/bin');
        $validator = new Validator($this->src_path . $this->correct_file, '', 33);
        $actual    = $validator->check();
        $this->assertTrue($actual);
    }

    /** @test */
    public function it_static_validates_pdf()
    {
        putenv('PATH=$PATH:/usr/local/bin/:/usr/bin');
        $actual = Validator::validate($this->src_path . $this->correct_file)->check();
        $this->assertTrue($actual);
    }

    /** @test */
    public function slightly_corrupted_pdf()
    {
        putenv('PATH=$PATH:/usr/local/bin/:/usr/bin');
        $validator = new Validator($this->src_path . $this->slightly_corrupted);
        $actual    = $validator->check();
        $this->assertFalse($actual);
        $this->assertSame($validator->error, "Could open the PDF, but the PDF seems to be corrupted.");
    }

    /** @test */
    public function corrupted_pdf()
    {
        putenv('PATH=$PATH:/usr/local/bin/:/usr/bin');
        $validator = new Validator($this->src_path . $this->corrupted);
        $actual    = $validator->check();
        $this->assertFalse($actual);
        $this->assertSame($validator->error, "Could not open the PDF.");
    }

    /** @test */
    public function it_will_throw_an_exception_when_the_file_is_not_found()
    {
        $this->expectException(FileNotFound::class);
        $validator = new Validator($this->src_path . 'xyz');
    }

    /** @test */
    public function it_will_throw_an_exception_when_the_binary_is_not_found()
    {
        $this->expectException(BinaryNotFound::class);
        (new Validator($this->src_path . $this->corrupted, '/there/is/no/place/like/home/pdftotext'))
            ->check();
    }

    /** @test */
    public function it_will_throw_an_exception_when_the_mime_type_is_not_correct()
    {
        $this->expectException(MimeTypeIsNotPdf::class);
        (new Validator($this->src_path . 'corrupted-pdf.jpg'))
            ->check();
    }

}
