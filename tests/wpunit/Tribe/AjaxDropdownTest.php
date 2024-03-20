<?php

namespace Tribe;

use Tribe__Ajax__Dropdown;

class AjaxDropdownTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test
	 */
	public function should_default_to_only_published_posts() {
		$title = 'post_title' . uniqid();
		$post1 = $this->factory->post->create(
			[
				'post_content' => 'Event Content',
				'post_title'   => $title,
				'post_status'  => 'draft',
			]
		);
		$post2 = $this->factory->post->create(
			[
				'post_content' => 'Event Content',
				'post_title'   => $title,
				'post_status'  => 'publish',
			]
		);

		$dropdown = new Tribe__Ajax__Dropdown();
		$args     = $dropdown->parse_params( [] );
		$data     = $dropdown->search_posts( $title, 1, $args->args, $args->source );
		$ids      = array_map(
			function ( $item ) {
				return $item['id'];
			},
			$data['posts']
		);

		$this->assertContains( $post2, $ids );
		$this->assertNotContains( $post1, $ids );
	}

	/**
	 * @dataProvider parse_params_data_provider
	 * @test
	 */
	public function should_clean_query_args( $dirty_params, $expected_params ) {
		$dropdown     = new Tribe__Ajax__Dropdown();
		$clean_params = $dropdown->parse_params( $dirty_params );
		$this->assertEquals( $expected_params, (array) $clean_params );
	}

	public function parse_params_data_provider() {
		return [
			'dirty args'    => [
				[
					'cat'         => 'b',
					'copy'        => 1,
					'post_status' => 'draft',
					'args'        => [ 'post_status' => 'draft' ],
					'faux'        => null,
				],
				[
					'search' => null,
					'page'   => 0,
					'source' => null,
					'args'   => [ 'post_status' => 'publish' ],
				],
			],
			'expected args' => [
				[
					'args' => [
						'taxonomy'  => 'abc',
						'post_type' => 'post',
					],
				],
				[
					'search' => null,
					'page'   => 0,
					'source' => null,
					'args'   => [
						'post_status' => 'publish',
						'taxonomy'    => 'abc',
						'post_type'   => 'post',
					],
				],
			],
		];
	}
}
