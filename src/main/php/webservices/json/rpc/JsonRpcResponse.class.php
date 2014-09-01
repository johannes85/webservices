<?php namespace webservices\json\rpc;
 
use webservices\rpc\AbstractRpcResponse;
use peer\http\HttpConstants;


/**
 * Wraps JSON response
 *
 * @see scriptlet.HttpScriptletResponse  
 */
class JsonRpcResponse extends AbstractRpcResponse {
  
  /**
   * Sets JSON message
   *
   * @param   webservices.json.rpc.JsonRpcMessage msg
   */
  public function setMessage($msg) {
    $this->message= $msg;
  }
  
  /**
   * Make sure a fault is passed as "500 Internal Server Error"
   *
   * @see     scriptlet.HttpScriptletResponse#process
   */
  public function process() {
    if (!$this->message) return;

    if (null !== $this->message->getFault()) {
      $this->setStatus(HttpConstants::STATUS_INTERNAL_SERVER_ERROR);
    }
    
    $this->content= $this->message->serializeData();
    $this->cat && $this->cat->debug('>>> ', $this->content);
  }
}
