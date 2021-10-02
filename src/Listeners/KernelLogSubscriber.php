<?php

namespace Greenpacket\KiplePay\Listeners;

use Greenpacket\KiplePay\Events;
use Greenpacket\KiplePay\Supports\Log;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KernelLogSubscriber implements EventSubscriberInterface
{
  /**
   * Returns an array of event names this subscriber wants to listen to.
   *
   * The array keys are event names and the value can be:
   *
   *  * The method name to call (priority defaults to 0)
   *  * An array composed of the method name to call and the priority
   *  * An array of arrays composed of the method names to call and respective
   *    priorities, or 0 if unset
   *
   * For instance:
   *
   *  * array('eventName' => 'methodName')
   *  * array('eventName' => array('methodName', $priority))
   *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
   *
   * @return array The event names to listen to
   */
  public static function getSubscribedEvents()
  {
    return [
      Events\SignFailed::class          => ['writeSignFailedLog', 256],
      Events\MethodCalled::class        => ['writeMethodCalledLog', 256],
      Events\ApiRequested::class        => ['writeApiRequestedLog', 256],
      Events\ApiRequesting::class       => ['writeApiRequestingLog', 256],
      Events\RequestStarted::class      => ['writeRequestStartedLog', 256],
      Events\RequestReceived::class     => ['writeRequestReceivedLog', 256],
      Events\RequestStarting::class     => ['writeRequestStartingLog', 256],
    ];
  }

  /**
   * writeRequestStartingLog.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param Events\RequestStarting $event
   *
   * @return void
   */
  public function writeRequestStartingLog(Events\RequestStarting $event)
  {
    Log::debug("Starting To", [$event->gateway, $event->params]);
  }

  /**
   * writeRequestStartedLog.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param Events\RequestStarted $event
   *
   * @return void
   */
  public function writeRequestStartedLog(Events\RequestStarted $event)
  {
    Log::info("{$event->gateway} Has Started",[$event->endpoint, $event->payload]);
  }

  /**
   * writeApiRequestingLog.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param Events\ApiRequesting $event
   *
   * @return void
   */
  public function writeApiRequestingLog(Events\ApiRequesting $event)
  {
    Log::debug("Requesting To Api", [$event->endpoint, $event->payload]);
  }

  /**
   * writeApiRequestedLog.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param Events\ApiRequested $event
   *
   * @return void
   */
  public function writeApiRequestedLog(Events\ApiRequested $event)
  {
    Log::debug("Result Of Api", $event->result);
  }

  /**
   * writeSignFailedLog.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param Events\SignFailed $event
   *
   * @return void
   */
  public function writeSignFailedLog(Events\SignFailed $event)
  {
    Log::warning("Sign Verify FAILED", $event->data);
  }

  /**
   * writeRequestReceivedLog.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param Events\RequestReceived $event
   *
   * @return void
   */
  public function writeRequestReceivedLog(Events\RequestReceived $event)
  {
    Log::info("Received Request", $event->data);
  }

  /**
   * writeMethodCalledLog.
   *
   * @author Evans <evans.yang@greenpacket.com.cn>
   *
   * @param Events\MethodCalled $event
   *
   * @return void
   */
  public function writeMethodCalledLog(Events\MethodCalled $event)
  {
    Log::info("{$event->gateway} Method Has Called", [$event->endpoint, $event->payload]);
  }
}
