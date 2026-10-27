<?php

namespace TEC\Event_Automator\Zapier;

use Codeception\TestCase\WPTestCase;
use TEC\Event_Automator\Zapier\Admin\Endpoints_Manager;

class Template_Modifications_Test extends WPTestCase {
	/**
	 * @test
	 * It should link the API keys intro text to the Zapier knowledgebase article.
	 */
	public function should_link_intro_text_to_zapier_article(): void {
		$html = tribe( Template_Modifications::class )->get_intro_text();

		$this->assertStringContainsString( 'href="https://evnt.is/zapier"', $html );
		$this->assertStringNotContainsString( 'https://evnt.is/1bc8', $html );
	}

	/**
	 * @test
	 * It should state the queue limit on the endpoint dashboard without a knowledgebase link.
	 */
	public function should_state_queue_limit_without_knowledgebase_link(): void {
		$html = tribe( Template_Modifications::class )->get_dashboard( [], tribe( Endpoints_Manager::class ), tribe( Url::class ) );

		$this->assertStringContainsString( 'The Zapier queue is currently limited to 15 items for each endpoint on your site.', $html );
		$this->assertStringNotContainsString( 'Increasing the Zapier Queue Limit', $html );
		$this->assertStringNotContainsString( 'https://evnt.is/zapier-endpoints', $html );
	}
}
