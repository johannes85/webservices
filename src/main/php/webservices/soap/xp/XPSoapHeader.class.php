<?php namespace webservices\soap\xp;

/**
 * SOAP Header interface
 *
 * @see      xp://webservices.soap.SOAPHeaderElement
 * @purpose  Interface
 */
interface XPSoapHeader {

  /**
   * Retrieve XML representation of this header for use in a SOAP
   * message.
   *
   * @param   [:string] ns list of namespaces
   * @return  xml.Node
   */
  public function getNode($ns);
}
