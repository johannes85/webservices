<?php namespace webservices\unittest\rpc;

use lang\IllegalArgumentException;
use xml\Tree;
use webservices\xmlrpc\XmlRpcEncoder;
use util\Bytes;

/**
 * TestCase
 *
 * @see   xp://webservices.xmlrpc.XmlRpcEncoder
 */
class XmlRpcEncoderTest extends \unittest\TestCase {

  /**
   * Encode a value
   *
   * @param   mixed value
   * @return  string
   */
  protected function encode($value) {
    return (new XmlRpcEncoder())->encode($value)->getSource(INDENT_NONE);
  }
  
  /**
   * Normalizes expected XML and returns whether that is equal to the 
   * actual XML.
   *
   * @param   string expected
   * @param   string actual
   * @throws  unittest.AssertionFailedError
   */
  protected function assertXmlEquals($expected, $actual) {
    $this->assertEquals(preg_replace('#>[\s\r\n]+<#', '><', trim($expected)), $actual);
  }

  /**
   * Test encoding an integer.
   *
   */
  #[@test]
  public function int() {
    $this->assertEquals('<value><int>1</int></value>', $this->encode(1));
  }

  /**
   * Test encoding an double.
   *
   */
  #[@test]
  public function double() {
    $this->assertEquals('<value><double>1.1</double></value>', $this->encode(1.1));
  }

  /**
   * Test encoding a string.
   *
   */
  #[@test]
  public function string() {
    $this->assertEquals('<value><string>Hello</string></value>', $this->encode('Hello'));
  }

  /**
   * Test encoding a string
   *
   */
  #[@test]
  public function stringWithUmlauts() {
    $this->assertEquals('<value><string>Hällo</string></value>', $this->encode('Hällo'));
  }

  /**
   * Test encoding a string.
   *
   */
  #[@test]
  public function emptyString() {
    $this->assertEquals('<value><string></string></value>', $this->encode(''));
  }

  /**
   * Test encoding a boolean FALSE.
   *
   */
  #[@test]
  public function false() {
    $this->assertEquals('<value><boolean>0</boolean></value>', $this->encode(false));
  }

  /**
   * Test encoding a boolean FALSE.
   *
   */
  #[@test]
  public function true() {
    $this->assertEquals('<value><boolean>1</boolean></value>', $this->encode(true));
  }

  /**
   * Test encoding NULL.
   *
   */
  #[@test]
  public function nil() {
    $this->assertEquals('<value><nil></nil></value>', $this->encode(null));
  }

  /**
   * Test encoding an empty array.
   *
   */
  #[@test]
  public function emptyArray() {
    $this->assertEquals('<value><array><data></data></array></value>', $this->encode([]));
  }

  /**
   * Test encoding an array with one element
   *
   */
  #[@test]
  public function oneElementArray() {
    $this->assertXmlEquals('
      <value>
        <array>
          <data>
            <value><int>1</int></value>
          </data>
        </array>
      </value>
      ',
      $this->encode([1])
    );
  }

  /**
   * Test encoding the example array from the XMLRPC spec
   *
   * @see   http://www.xmlrpc.com/spec
   */
  #[@test]
  public function exampleArray() {
    $this->assertXmlEquals('
      <value>
        <array>
          <data>
            <value><int>12</int></value>
            <value><string>Egypt</string></value>
            <value><boolean>0</boolean></value>
            <value><int>-31</int></value>
          </data>
        </array>
      </value>
      ',
      $this->encode([12, 'Egypt', false, -31])
    );
  }

  /**
   * Test encoding the example struct from the XMLRPC spec
   *
   * @see   http://www.xmlrpc.com/spec
   */
  #[@test]
  public function exampleStruct() {
    $this->assertXmlEquals('
      <value>
        <struct>
          <member>
            <name>lowerBound</name>
            <value><int>18</int></value>
          </member>
          <member>
            <name>upperBound</name>
            <value><int>139</int></value>
          </member>
        </struct>
      </value>
      ', 
      $this->encode(['lowerBound' => 18, 'upperBound' => 139])
    );
  }

  /**
   * Test encoding a struct with our special __xp_class member.
   *
   */
  #[@test]
  public function valueObject() {
    $vo= new ValueObject();
    $vo->setName('Timm');
    $vo->setAge(30);
    $vo->setNew(true);
    
    $this->assertXmlEquals('
      <value>
        <struct>
          <member>
            <name>__xp_class</name>
            <value><string>webservices.unittest.rpc.ValueObject</string></value>
          </member>
          <member>
            <name>name</name>
            <value><string>Timm</string></value>
          </member>
          <member>
            <name>age</name>
            <value><int>30</int></value>
          </member>
          <member>
            <name>_new</name>
            <value><boolean>1</boolean></value>
          </member>
        </struct>
      </value>
      ', 
      $this->encode($vo)
    );
  }

  /**
   * Test encoding dates.
   *
   */
  #[@test]
  public function date() {
    $iso8601String= '20051129T18:13:48';
    $this->assertEquals(
      '<value><dateTime.iso8601>'.$iso8601String.'</dateTime.iso8601></value>',
      $this->encode(new \util\Date($iso8601String))
    );
  }

  /**
   * Test encoding base64 data.
   *
   */
  #[@test]
  public function base64() {
    $this->assertEquals('<value><base64>VW5pdHRlc3Q=</base64></value>', $this->encode(new Bytes('Unittest')));
  }
 
  /**
   * Test encoding unsupported types will raise an exception.
   *
   */
  #[@test, @expect(IllegalArgumentException::class)]
  public function unsupported() {
    $this->encode(STDIN);
  }
}
