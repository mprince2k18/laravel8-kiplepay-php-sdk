<?php

namespace Greenpacket\KiplePay\Supports;

use Exception;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as BaseLogger;
use Psr\Log\LoggerInterface;

/**
 * @method void emergency($message, array $context = array())
 * @method void alert($message, array $context = array())
 * @method void critical($message, array $context = array())
 * @method void error($message, array $context = array())
 * @method void warning($message, array $context = array())
 * @method void notice($message, array $context = array())
 * @method void info($message, array $context = array())
 * @method void debug($message, array $context = array())
 * @method void log($message, array $context = array())
 */
class Logger
{
    /**
     * Logger instance.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * formatter.
     *
     * @var \Monolog\Formatter\FormatterInterface
     */
    protected $formatter;

    /**
     * handler.
     *
     * @var AbstractHandler
     */
    protected $handler;

    /**
     * config.
     *
     * @var array
     */
    protected $config = [
        'file' => null,
        'identify' => 'greenpacket.kiple-sdk',
        'level' => BaseLogger::DEBUG,
        'type' => 'daily',
        'max_files' => 30,
    ];

    /**
     * Forward call.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @param string $method
     * @param array  $args
     *
     * @throws Exception
     */
    public function __call($method, $args): void
    {
        call_user_func_array([$this->getLogger(), $method], $args);
    }

    /**
     * Set logger.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     */
    public function setLogger(LoggerInterface $logger): Logger
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Return the logger instance.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @throws Exception
     */
    public function getLogger(): LoggerInterface
    {
        if (is_null($this->logger)) {
            $this->logger = $this->createLogger();
        }

        return $this->logger;
    }

    /**
     * Make a default log instance.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @throws Exception
     */
    public function createLogger(): BaseLogger
    {
        $handler = $this->getHandler();

        $handler->setFormatter($this->getFormatter());

        $logger = new BaseLogger($this->config['identify']);

        $logger->pushHandler($handler);

        return $logger;
    }

    /**
     * setFormatter.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @return $this
     */
    public function setFormatter(FormatterInterface $formatter): self
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * getFormatter.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     */
    public function getFormatter(): FormatterInterface
    {
        if (is_null($this->formatter)) {
            $this->formatter = $this->createFormatter();
        }

        return $this->formatter;
    }

    /**
     * createFormatter.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     */
    public function createFormatter(): LineFormatter
    {
        return new LineFormatter(
            "[%datetime%]  %channel%.%level_name% : %message% %context% %extra%\n",
            null,
            false,
            true
        );
    }

    /**
     * setHandler.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @return $this
     */
    public function setHandler(AbstractHandler $handler): self
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * getHandler.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @throws \Exception
     */
    public function getHandler(): AbstractHandler
    {
        if (is_null($this->handler)) {
            $this->handler = $this->createHandler();
        }

        return $this->handler;
    }

    /**
     * createHandler.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @throws \Exception
     *
     * @return \Monolog\Handler\RotatingFileHandler|\Monolog\Handler\StreamHandler
     */
    public function createHandler(): AbstractHandler
    {
        $file = $this->config['file'] ?? sys_get_temp_dir().'/logs/'.$this->config['identify'].'.log';

        if ('single' === $this->config['type']) {
            return new StreamHandler($file, $this->config['level']);
        }

        return new RotatingFileHandler($file, $this->config['max_files'], $this->config['level']);
    }

    /**
     * setConfig.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     *
     * @return $this
     */
    public function setConfig(array $config): self
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * getConfig.
     *
     * @author Evans <evans.yang@greenpacket.com.cn>
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
