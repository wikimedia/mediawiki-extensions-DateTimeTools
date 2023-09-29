<?php

namespace DateTimeTools\Hook;

use DateTimeTools\DateTimeParser;
use Language;
use MediaWiki\Hook\ParserFirstCallInitHook;

class HandleTag implements ParserFirstCallInitHook {
	/** @var DateTimeParser */
	private $dateTimeParser;

	/** @var Language */
	private $language;

	/**
	 * @var array
	 */
	private static $counter = [];

	/**
	 * @param DateTimeParser $parser
	 * @param Language $language
	 */
	public function __construct( DateTimeParser $parser, Language $language ) {
		$this->dateTimeParser = $parser;
		$this->language = $language;
	}

	/**
	 * @inheritDoc
	 */
	public function onParserFirstCallInit( $parser ) {
		$parser->setHook( 'datetime', [ $this, 'handleTag' ] );
	}

	/**
	 * @param string $input
	 * @param array $args
	 * @param Parser $parser
	 * @param PPFrame $frame
	 *
	 * @return string
	 */
	public function handleTag( $input, array $args, \Parser $parser, \PPFrame $frame ) {
		if ( isset( static::$counter[spl_object_id( $parser )] ) ) {
			static::$counter[spl_object_id( $parser )]++;
		} else {
			static::$counter[spl_object_id( $parser )] = 0;
		}

		$dt = $this->dateTimeParser->parseTimestamp( $input );
		if ( !$dt ) {
			return 'Invalid date format';
		}
		if ( $this->dateTimeParser->isMidnight( $dt ) ) {
			$formatted = $this->language->userDate( $dt->format( 'YmdHid' ), $parser->getUserIdentity() );
		} else {
			$formatted = $this->language->userTimeAndDate( $dt->format( 'YmdHid' ), $parser->getUserIdentity() );
		}
		$parser->getOutput()->addModuleStyles( [ 'ext.dateTimeTools.styles' ] );
		return \Html::element( 'span', [
			'class' => 'datetime'
		], $formatted );
	}
}
