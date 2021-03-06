<?php namespace webservices\wddx\transport;

use webservices\rpc\transport\AbstractRpcTransport;
use webservices\wddx\WddxFaultException;
use webservices\wddx\WddxMessage;
use peer\http\HttpConnection;
use peer\http\HttpConstants;


/**
 * Transport for Wddx requests over HTTP.
 *
 * @see      xp://webservices.wddx.WddxClient
 * @purpose  HTTP Transport
 */
class WddxHttpTransport extends AbstractRpcTransport {
  public
    $_conn    = null,
    $_headers = [];
  
  /**
   * Constructor.
   *
   * @param   string url
   * @param   array headers
   */
  public function __construct($url, $headers= []) {
    $this->_conn= new HttpConnection($url);
    $this->_headers= $headers;
  }
  
  /**
   * Create a string representation
   *
   * @return  string
   */
  public function toString() {
    return sprintf('%s { %s }', nameof($this), $this->_conn->request->url->_info['url']);
  }

  /**
   * Send XML-RPC message
   *
   * @param   webservices.wddx.WddxMessage message
   * @return  scriptlet.HttpScriptletResponse
   */
  public function send(WddxMessage $message) {
    
    // Send request
    with ($r= $this->_conn->create(new \peer\http\HttpRequest())); {
      $r->setMethod(HttpConstants::POST);
      $r->setParameters(new \peer\http\RequestData(
        $message->getDeclaration()."\n".
        $message->getSource(0)
      ));

      $r->setHeader('Content-Type', 'text/xml; charset='.$message->getEncoding());
      $r->setHeader('User-Agent', 'XP Framework WDDX Client (http://xp-framework.net)');

      // Add custom headers
      $r->addHeaders($this->_headers);

      $this->cat && $this->cat->debug('>>>', $r->getRequestString());
      return $this->_conn->send($r);
    }
  }
  
  /**
   * Retrieve a WDDX message.
   *
   * @param   scriptlet.HttpScriptletResponse response
   * @return  webservices.wddx.WddxMessage
   */
  public function retrieve($response) {
    $this->cat && $this->cat->debug('<<<', $response->toString());
    
    $code= $response->getStatusCode();
    
    switch ($code) {
      case HttpConstants::STATUS_OK:
      case HttpConstants::STATUS_INTERNAL_SERVER_ERROR:
        $xml= '';
        while ($buf= $response->readData()) $xml.= $buf;

        $this->cat && $this->cat->debug('<<<', $xml);
        if ($answer= WddxMessage::fromString($xml)) {

          // Check encoding
          if (null !== ($content_type= $response->getHeader('Content-Type'))) {
            @list($type, $charset)= explode('; charset=', $content_type);
            if (!empty($charset)) $answer->setEncoding($charset);
          }
        }

        // Fault?
        if (null !== ($fault= $answer->getFault())) {
          throw new WddxFaultException($fault);
        }
        
        return $answer;
      
      case HttpConstants::STATUS_AUTHORIZATION_REQUIRED:
        throw new \lang\IllegalAccessException(
          'Authorization required: '.$response->getHeader('WWW-Authenticate')
        );
      
      default:
        throw new \lang\IllegalStateException(
          'Unexpected return code: '.$response->getStatusCode()
        );
    }
  }    
}
