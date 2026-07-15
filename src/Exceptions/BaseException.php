<?php
namespace OpaReklama\Booking\Exceptions;

use Exception;

/**
 * Base Exception class for the Opa Booking Engine.
 * All custom exceptions should extend this class.
 */
abstract class BaseException extends Exception {

    /**
     * @var string|null An optional structured error code (e.g. ERR_INVALID_DATE)
     */
    protected ?string $errorCode = null;

    /**
     * BaseException constructor.
     *
     * @param string         $message   The exception message.
     * @param string|null    $errorCode A structured error code.
     * @param int            $code      The exception code.
     * @param Exception|null $previous  The previous exception used for the exception chaining.
     */
    public function __construct( string $message = '', ?string $errorCode = null, int $code = 0, ?Exception $previous = null ) {
        $this->errorCode = $errorCode;
        parent::__construct( $message, $code, $previous );
    }

    /**
     * Get the structured error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string {
        return $this->errorCode;
    }
}
