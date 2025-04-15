<?php
namespace Overdose\LessonOne\Model;

use Psr\Log\LoggerInterface;
use Magento\Framework\Logger\Monolog;

class Logger implements LoggerInterface
{
    /**
     * @var Monolog
     */
    protected $monolog;

    /**
     * Logger constructor.
     * @param Monolog $monolog
     */
    public function __construct(Monolog $monolog)
    {
        $this->monolog = $monolog;
    }

    /**
     * Log emergency message
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function emergency($message, array $context = []): void
    {
        $this->monolog->emergency($message, $context);
    }

    /**
     * Log alert message
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function alert($message, array $context = []): void
    {
        $this->monolog->alert($message, $context);
    }

    /**
     * Log critical message
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function critical($message, array $context = []): void
    {
        $this->monolog->critical($message, $context);
    }

    /**
     * Log error message
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = []): void
    {
        $this->monolog->error($message, $context);
    }

    /**
     * Log warning message
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function warning($message, array $context = []): void
    {
        $this->monolog->warning($message, $context);
    }

    /**
     * Log notice message
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function notice($message, array $context = []): void
    {
        $this->monolog->notice($message, $context);
    }

    /**
     * Log info message
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function info($message, array $context = []): void
    {
        $this->monolog->info($message, $context);
    }

    /**
     * Log debug message
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function debug($message, array $context = []): void
    {
        $this->monolog->debug($message, $context);
    }

    /**
     * Log message with specific level
     * @param mixed $level
     * @param \Stringable|string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = []): void
    {
        $this->monolog->log($level, $message, $context);
    }
}