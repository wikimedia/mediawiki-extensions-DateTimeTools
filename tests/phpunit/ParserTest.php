<?php

namespace DateTimeTools\Tests;

use DateTimeTools\DateTimeParser;
use Monolog\Test\TestCase;

class ParserTest extends TestCase {

	/**
	 * @param string $text
	 *
	 * @covers \DateTimeTools\DateTimeParser::parse
	 * @dataProvider provideData
	 * @return void
	 */
	public function testParse( $text ) {
		$parser = new DateTimeParser();
		$dates = $parser->parse( $text );
		$expected = \DateTime::createFromFormat( 'YmdHis', '20190202061245' );
		$this->assertEquals( $expected, $dates[0]['datetime'] );
	}

	public function provideData() {
		return [
			[
				'<datetime>20190202061245</datetime>',
			],
			[
				'<datetime>2019-02-02 06:12:45</datetime>',
			],
			[
				'<datetime>6:12:45 02/02/2019</datetime>',
			],
			[
				'<datetime>February 2, 2019 06:12:45</datetime>',
			],
		];
	}
}
