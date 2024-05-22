<?php

namespace Tribe\Repository;

require_once __DIR__ . '/ReadTestBase.php';

class Custom_OrderbyTest extends ReadTestBase {
	public function setUp() {
		parent::setUp();
		$this->class = new class extends \Tribe__Repository {
			protected $default_args = [ 'post_type' => 'book', 'orderby' => 'ID', 'order' => 'ASC' ];

			public function set_query_arg( $key, $value ) {
				$this->query_args    [ $key ] = $value;

				return $this;
			}

			public function orderby_meta( $orderby, $order ) {
				global $wpdb;
				$join_clause = "LEFT JOIN {$wpdb->postmeta} AS {$orderby} ON ( {$wpdb->posts}.ID = {$orderby}.post_id" .
				               " AND {$orderby}.meta_key = '{$orderby}')";
				$this->filter_query->join( $join_clause );
				$this->filter_query->orderby( [ $orderby => $order ], $orderby, true, false );
				$this->filter_query->fields( "CAST( {$orderby}.meta_value AS DECIMAL ) AS {$orderby}", $orderby );

				return $this;
			}

			public function orderby_meta_after( $orderby, $order ) {
				global $wpdb;
				$join_clause = "LEFT JOIN {$wpdb->postmeta} AS {$orderby} ON ( {$wpdb->posts}.ID = {$orderby}.post_id" .
				               " AND {$orderby}.meta_key = '{$orderby}')";
				$this->filter_query->join( $join_clause );
				$this->filter_query->orderby( [ $orderby => $order ], $orderby . '_after', true, true );
				$this->filter_query->fields( "CAST( {$orderby}.meta_value AS DECIMAL ) AS {$orderby}", $orderby );

				return $this;
			}

			public function multi_meta_orderby_after( $orderby_array ) {
				global $wpdb;

				$this->filter_query->orderby( $orderby_array, 'multi_meta', true, true );

				foreach ($orderby_array as $orderby => $order){
					$join_clause = "LEFT JOIN {$wpdb->postmeta} AS {$orderby} ON ( {$wpdb->posts}.ID = {$orderby}.post_id" .
					               " AND {$orderby}.meta_key = '{$orderby}')";
					$this->filter_query->join( $join_clause );
					$this->filter_query->fields( "CAST( {$orderby}.meta_value AS DECIMAL ) AS {$orderby}", $orderby );
				}

				return $this;
			}
		};
	}

	/**
	 * It should allow prepending orderby criteria to WordPress defaults
	 *
	 * @test
	 */
	public function should_allow_prepending_orderby_criteria_to_word_press_defaults() {
		$book_1 = static::factory()->post->create( [
			'post_type'  => 'book',
			'menu_order' => 2,
			'meta_input' => [
				'_active_readers' => 23,
				'_release_year'   => '2011',
			],
		] );
		$book_2 = static::factory()->post->create( [
			'post_type'  => 'book',
			'menu_order' => 2,
			'meta_input' => [
				'_active_readers' => 2,
				'_release_year'   => '2019',
			],
		] );
		$book_3 = static::factory()->post->create( [
			'post_type'  => 'book',
			'menu_order' => 3,
			'meta_input' => [
				'_active_readers' => 89,
				'_release_year'   => '2019'
			],
		] );

		$repo = $this->repository();
		$results = $repo->orderby_meta( '_active_readers', 'DESC' )
			->set_query_arg( 'orderby', [ 'menu_order' => 'ASC' ] )
			->get_ids();
		codecept_debug('Query:');
		codecept_debug($repo->get_query()->request);
		$this->assertEquals( [ $book_3, $book_1, $book_2 ],$results );
		$this->assertEquals(
			[ $book_2, $book_1, $book_3 ],
			$this->repository()
			     ->orderby_meta( '_active_readers', 'ASC' )
			     ->set_query_arg( 'orderby', [ 'menu_order' => 'ASC' ] )
			     ->get_ids()
		);
		$this->assertEquals(
			[ $book_3, $book_2, $book_1 ],
			$this->repository()->orderby_meta( '_release_year', 'DESC' )
			     ->set_query_arg( 'orderby', [ 'menu_order' => 'ASC' ] )
			     ->orderby_meta( '_active_readers', 'DESC' )->get_ids()
		);
	}

	/**
	 * It should allow appending orderby criteria after WordPress ones
	 *
	 * @test
	 */
	public function should_allow_appending_orderby_criteria_after_word_press_ones() {
		$book_1 = static::factory()->post->create( [
			'post_type'  => 'book',
			'menu_order' => 2,
			'meta_input' => [
				'_active_readers' => 23,
				'_release_year'   => '2011',
			],
		] );
		$book_2 = static::factory()->post->create( [
			'post_type'  => 'book',
			'menu_order' => 2,
			'meta_input' => [
				'_active_readers' => 2,
				'_release_year'   => '2019',
			],
		] );
		$book_3 = static::factory()->post->create( [
			'post_type'  => 'book',
			'menu_order' => 3,
			'meta_input' => [
				'_active_readers' => 89,
				'_release_year'   => '2019'
			],
		] );

		$this->assertEquals(
			[ $book_2, $book_1, $book_3 ],
			$this->repository()
			     ->orderby_meta_after( '_release_year', 'DESC' )
			     ->set_query_arg( 'orderby', [ 'menu_order' => 'ASC' ] )
			     ->get_ids()
		);
		$this->assertEquals(
			[ $book_2, $book_1, $book_3 ],
			$this->repository()
			     ->orderby_meta_after( '_active_readers', 'ASC' )
			     ->set_query_arg( 'orderby', [ 'menu_order' => 'ASC' ] )->get_ids()
		);
		$this->assertEquals(
			[ $book_3, $book_1, $book_2 ],
			$this->repository()->orderby_meta_after( '_active_readers', 'DESC' )
			     ->set_query_arg( 'orderby', [ 'menu_order' => 'DESC' ] )
			     ->get_ids()
		);
		$ids = $this->repository()->multi_meta_orderby_after( [
			'_release_year'   => 'DESC',
			'_active_readers' => 'ASC'
		] )->set_query_arg( 'orderby', [ 'menu_order' => 'ASC' ] )->get_ids();
		$this->assertEquals( [ $book_2, $book_1, $book_3 ], $ids );
	}
}
