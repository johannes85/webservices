<?php namespace webservices\json\rpc;
 
use webservices\rpc\AbstractRpcRequest;
  
/**
 * Wraps Json Rpc Router request
 *
 * @see xp://scriptlet.rpc.AbstractRpcRequest
 */
class JsonRpcRequest extends AbstractRpcRequest {

  /**
   * Retrieve Json message from request
   *
   * @return  webservices.xmlrpc.XmlRpcMessage message object
   */
  public function getMessage() {
    $this->cat && $this->cat->debug('<<< ', $this->getData());
    $m= JsonRequestMessage::fromString($this->getData());
    $m->setEncoding($this->getEncoding());
    return $m;
  }
}
