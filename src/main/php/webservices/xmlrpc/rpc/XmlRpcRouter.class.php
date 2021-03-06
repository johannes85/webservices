<?php namespace webservices\xmlrpc\rpc;

use webservices\xmlrpc\XmlRpcResponseMessage;
use webservices\rpc\AbstractRpcRouter;

/**
 * XML-RPC Router class. You can use this class to implement
 * a XML-RPC webservice.
 *
 * <code>
 *   $s= new XmlRpcRouter('net.xp_framework.webservices.xmlrpc');
 * 
 *   try {
 *     $s->init();
 *     $response= $s->process();
 *   } catch(HttpScriptletException $e) {
 * 
 *     // Retrieve standard "Internal Server Error"-Document
 *     $response= $e->getResponse();
 *   }
 * 
 *   $response->sendHeaders();
 *   $response->sendContent();
 * 
 *   $s->finalize();
 * </code>
 *
 * The default implementation of the XmlRpcRouter takes the given methodName from the
 * XML-RPC request, splits it at the '.' and takes the first part as the class name,
 * the second part as the method name. A request on a server with the setup given above
 * and a requested methodName of 'XmlRpcTest.runTest' would try to instanciate class
 * net.xp_framework.webservices.xmlrpc.XmlRpcTestHandler and run methon 'runTest'
 * on it.
 *
 * @ext      xml
 * @see      xp://webservices.xmlrpc.XmlRpcClient
 * @purpose  XML-RPC-Service
 */
class XmlRpcRouter extends AbstractRpcRouter {

  /**
   * Create a request object.
   *
   * @return  webservices.xmlrpc.rpc.XmlRpcRequest
   */
  protected function _request() {
    return new XmlRpcRequest();
  }

  /**
   * Create a response object.
   *
   * @return  webservices.xmlrpc.rpc.XmlRpcResponse
   */
  protected function _response() {
    return new XmlRpcResponse();
  }
  
  /**
   * Create a message object.
   *
   * @return  webservices.xmlrpc.XmlRpcResponseMessage
   */
  protected function _message() {
    return new XmlRpcResponseMessage();
  }
}
