<?php

namespace App\Exception;

use Exception;
use Throwable;

class SheetsException extends Exception
{
    /** @var string $sheetId */
    protected $sheetId;

    /**
     * @param string $sheetId
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $sheetId, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->sheetId = $sheetId;
    }
}