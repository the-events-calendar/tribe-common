<?php

namespace Tribe\Editor;

use Codeception\Test\Unit;
use Tribe__Editor__Utils;

class Utils_Test extends Unit {

	public function content_provider() {
		return [
			'empty content'        => [ '', '' ],
			'plain content'        => [ '<p>Description.</p>', '<p>Description.</p>' ],
			'legacy dynamic block' => [ '<!-- wp:tribe/event-datetime /-->', '' ],
			'single event block'   => [ '<!-- wp:tec/single-event /-->', '' ],
			'archive events block' => [ '<!-- wp:tec/archive-events /-->', '' ],
			'paired block'         => [ '<!-- wp:tec/single-event --><p>Saved content.</p><!-- /wp:tec/single-event -->', '<p>Saved content.</p>' ],
			'adjacent content'     => [ '<!-- wp:tec/single-event /--><!-- wp:paragraph --><p>Description.</p><!-- /wp:paragraph -->', '<!-- wp:paragraph --><p>Description.</p><!-- /wp:paragraph -->' ],
			'nested block'         => [ '<!-- wp:group --><div><!-- wp:tec/single-event /--></div><!-- /wp:group -->', '<!-- wp:group --><div></div><!-- /wp:group -->' ],
			'other namespace'      => [ '<!-- wp:example/single-event /-->', '<!-- wp:example/single-event /-->' ],
			'similar namespace'    => [ '<!-- wp:technology/single-event /-->', '<!-- wp:technology/single-event /-->' ],
		];
	}

	/**
	 * @dataProvider content_provider
	 */
	public function test_exclude_tribe_blocks_preserves_other_content( $content, $expected ) {
		$this->assertSame( $expected, ( new Tribe__Editor__Utils() )->exclude_tribe_blocks( $content ) );
	}
}
