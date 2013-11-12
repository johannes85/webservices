<?php namespace webservices\unittest\json;

use io\streams\MemoryInputStream;


/**
 * Testcase for JsonDecoder which decodes streams
 *
 * @see   xp://webservices.json.JsonDecoder
 */
class JsonStreamDecodingTest extends JsonDecodingTest {

  /**
   * Returns decoded input
   *
   * @param   string input
   * @return  var
   */
  protected function decode($input, $targetEncoding= 'iso-8859-1') {
    return $this->fixture->decodeFrom(new MemoryInputStream($input), $targetEncoding);
  }
}
