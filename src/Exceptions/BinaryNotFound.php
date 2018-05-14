<?php

namespace Ottosmops\Pdfvalidate\Exceptions;

use Symfony\Component\Process\Exception\ProcessFailedException;

class BinaryNotFound extends ProcessFailedException
{
}
