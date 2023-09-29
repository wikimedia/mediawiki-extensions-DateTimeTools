<?php

return [
	'DateTimeToolParser' => static function ( \MediaWiki\MediaWikiServices $services ) {
		return new \DateTimeTools\DateTimeParser();
	},
];
