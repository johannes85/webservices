<?php namespace scriptlet\rpc\transport;

use util\log\Traceable;


/**
 * Base class for RPC transports.
 *
 * @purpose  Base class.
 */
class AbstractRpcTransport extends \lang\Object implements Traceable {
  public
    $cat  = null;
    
  /**
   * Set trace for debugging
   *
   * @param   util.log.LogCategory cat
   */
  public function setTrace($cat) {
    $this->cat= $cat;
  }
 
  /**
   * Send XML-RPC message
   *
   * @param   scriptlet.rpc.AbstractRpcMessage message
   * @return  scriptlet.HttpScriptletResponse
   */
  public function send($message) { }
  
  /**
   * Retrieve a XML-RPC message.
   *
   * @param   scriptlet.rpc.AbstractRpcResponse response
   * @return  scriptlet.rpc.AbstractRpcMessage
   */
  public function retrieve($response) { }    

} 
