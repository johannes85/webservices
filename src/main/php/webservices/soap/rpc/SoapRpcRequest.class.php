<?php namespace webservices\soap\rpc;
 
use scriptlet\rpc\AbstractRpcRequest;
use webservices\soap\xp\XPSoapMessage;


/**
 * Wraps SOAP Rpc Router request
 *
 * @see webservices.soap.rpc.SoapRpcRouter
 * @see scriptlet.HttpScriptletRequest
 */
class SoapRpcRequest extends AbstractRpcRequest {
  private $mapping= null;

  /**
   * Constructor
   *
   * @param   webservices.soap.xp.XPSoapMapping mapping
   */
  public function __construct($mapping) {
    $this->mapping= $mapping;
  }

  /**
   * Retrieve SOAP message from request
   *
   * @return  webservices.soap.xp.XPSoapMessage message object
   */
  public function getMessage() {
    $m= XPSoapMessage::fromString($this->getData());
    list(
      $class, 
      $method
    )= explode('#', str_replace('"', '', $this->getHeader('SOAPAction')));
    
    $m->setHandlerClass($class);
    $m->setMethod($method);
    $m->setMapping($this->mapping);
    return $m;
  }
}
