<?php
namespace Tribe\Events\Importer;

use Symfony\Component\DomCrawler\Image;
use Tribe__Image__Uploader as Image_Uploader;

class Tribe__Image__UploaderTest extends \Codeception\TestCase\WPTestCase {

	public function setUp(): void {
		// before
		parent::setUp();

		// your set up methods here
		Image_Uploader::reset_cache();
	}

	function get_image_url( $extension = 'jpg' ) {
		return plugins_url( 'common/tests/_data/images/featured-image.' . $extension, \Tribe__Events__Main::instance()->plugin_file );
	}

	function get_image_path( $extension = 'jpg' ) {
		return codecept_data_dir( 'images/featured-image.' . $extension );
	}

	public function tearDown(): void {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$this->assertInstanceOf( Image_Uploader::class, new Image_Uploader() );
	}

	/**
	 * @test
	 * it should return false if record does not contain featured image
	 */
	public function it_should_return_false_if_record_does_not_contain_featured_image() {
		$sut = new Image_Uploader( 'some_value' );

		$out = $sut->upload_and_get_attachment_id();

		$this->assertFalse( $out );
	}

	/**
	 * @test
	 * it should return false when trying to upload non ID and non URL
	 */
	public function it_should_return_false_when_trying_to_upload_non_id_and_non_url() {
		$image_url = 'redneck url';

		$sut = new Image_Uploader( $image_url );
		$id  = $sut->upload_and_get_attachment_id();

		$this->assertFalse( $id );
	}

	/**
	 * @test
	 * it should return false when trying to upload non int ID
	 */
	public function it_should_return_false_when_trying_to_upload_non_int_id() {
		$image_url = 33.2;

		$sut = new Image_Uploader( $image_url );
		$id  = $sut->upload_and_get_attachment_id();

		$this->assertFalse( $id );
	}

	/**
	 * @test
	 * it should return false when trying to upload non existing URL
	 */
	public function it_should_return_false_when_trying_to_upload_non_existing_url() {
		$image_url = 'http://some-fake/image.jpg';

		$sut = new Image_Uploader( $image_url );
		$id  = $sut->upload_and_get_attachment_id();

		$this->assertFalse( $id );
	}

	/**
	 * @test
	 * it should return false when trying to upload non supported file type
	 */
	public function it_should_return_false_when_trying_to_upload_non_supported_file_type() {
		$image_url = $this->get_image_url( 'raw' );

		$sut = new Image_Uploader( $image_url );
		$id  = $sut->upload_and_get_attachment_id();

		$this->assertFalse( $id );
	}

	/**
	 * @test
	 * it should return false when trying to upload non existing attachment ID
	 */
	public function it_should_return_false_when_trying_to_upload_non_existing_attachment_id() {
		$sut = new Image_Uploader( 2233 );
		$id  = $sut->upload_and_get_attachment_id();

		$this->assertFalse( $id );
	}

	/**
	 * @test
	 * it should return attachment ID when uploading existing image URL
	 */
	public function it_should_return_attachment_id_when_uploading_existing_image_url() {
		$image_url = $this->get_image_url();

		$sut = new Image_Uploader( $image_url );
		$id  = $sut->upload_and_get_attachment_id();

		$this->assertNotFalse( $id );
		$this->assertEquals( 'attachment', get_post( $id )->post_type );
	}

	/**
	 * @test
	 * it should return attachment ID when uploading existing attachment ID
	 */
	public function it_should_return_attachment_id_when_uploading_existing_attachment_id() {
		$image_path             = $this->get_image_path();
		$existing_attachment_id = $this->factory()->attachment->create_upload_object( $image_path );

		$sut = new Image_Uploader( $existing_attachment_id );
		$id  = $sut->upload_and_get_attachment_id();

		$this->assertNotFalse( $id );
		$this->assertEquals( $existing_attachment_id, $id );
	}

	/**
	 * @test
	 * it should return same ID when referencing a Media Library image by URL
	 */
	public function it_should_return_same_id_when_referencing_a_media_library_image_by_url() {
		$image_path             = $this->get_image_path();
		$existing_attachment_id = $this->factory()->attachment->create_upload_object( $image_path );
		$attachment_post        = get_post( $existing_attachment_id );
		$attachment_url         = $attachment_post->guid;

		$sut_1 = new Image_Uploader( $attachment_url );
		$id_1  = $sut_1->upload_and_get_attachment_id();

		$sut_2 = new Image_Uploader( $attachment_url );
		$id_2  = $sut_2->upload_and_get_attachment_id();

		$this->assertEquals( $id_1, $id_2 );
	}

	/**
	 * @test
	 * it should return the same ID when referencing the same image by URL twice
	 */
	public function it_should_return_the_same_id_when_referencing_the_same_image_by_url_twice() {
		$image_url              = $this->get_image_url();

		$sut_1 = new Image_Uploader( $image_url );
		$id_1  = $sut_1->upload_and_get_attachment_id();

		$sut_2 = new Image_Uploader( $image_url );
		$id_2  = $sut_2->upload_and_get_attachment_id();

		$this->assertEquals( $id_1, $id_2 );
	}

	/**
	 * @test
	 * it should not insert same image twice in same run
	 */
	public function it_should_not_insert_same_image_twice_in_same_run() {
		$image_url             = $this->get_image_url();

		$sut  = new Image_Uploader( $image_url );
		$id_1 = $sut->upload_and_get_attachment_id();
		$id_2 = $sut->upload_and_get_attachment_id();

		$this->assertEquals( $id_1, $id_2 );
	}

	/**
	 * It should allow uploading a file by path
	 *
	 * @test
	 */
	public function it_should_allow_uploading_a_file_by_path() {
		$image_path             = $this->get_image_path();

		$sut  = new Image_Uploader( $image_path );
		$id_1 = $sut->upload_and_get_attachment_id();
		$id_2 = $sut->upload_and_get_attachment_id();

		$this->assertEquals( $id_1, $id_2 );
	}
}
