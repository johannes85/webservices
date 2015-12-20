<?php namespace webservices\soap\types;

use webservices\soap\xp\XPSoapNode;


/**
 * Hashmap type as serialized and recogned by Apache SOAP.
 *
 * @see      xp://webservices.soap.types.SoapType
 * @purpose  HashMap type
 */
class SOAPHashMap extends \lang\Object implements SoapType {

  /**
   * Constructor
   *
   * @param   array params
   */
  public function __construct($params) {
    $this->item= new XPSoapNode('hash', null, [
      'xmlns:hash'  => 'http://xml.apache.org/xml-soap',
      'xsi:type'    => 'hash:Map'
    ]);
    foreach ($params as $key => $value) {
      $this->item->addChild(XPSoapNode::fromArray([
        'key'   => $key,
        'value' => $value
      ], 'item'));
    }
  }
  
  /**
   * Return a string representation for use in SOAP
   *
   * @return  var
   */
  public function toString() {
    return '';
  }
  
  /**
   * Returns this type's name
   *
   * @return  string
   */
  public function getType() {
    return 'hash:Map';
  }

  /**
   * Retrieve item name
   *
   * @return  mixed
   */
  public function getItemName() {
    return false;
  }

  /**
   * Retrieve type as native SOAP type
   *
   * @return  php.SoapVar
   */
  public function asSoapType() {
    return $this->value;
  }
}
