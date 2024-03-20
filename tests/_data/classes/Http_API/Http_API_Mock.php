<?php

namespace TEC\Common\Tests\Http_API;

use WpOrg\Requests\Cookie\Jar;
use WpOrg\Requests\Response;
use WpOrg\Requests\Response\Headers;

abstract class Http_API_Mock {
	/**
	 * A map from status codes to the HTTP standard status description.
	 *
	 * @see https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
	 */
	protected static $status_messages = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		103 => 'Early Hints',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Content Too Large',
		414 => 'URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Range Not Satisfiable',
		417 => 'Expectation Failed',
		421 => 'Misdirected Request',
		422 => 'Unprocessable Content',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Too Early',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		451 => 'Unavailable For Legal Reasons',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		510 => 'Not Extended',
		511 => 'Network Authentication Required'
	];

	/**
	 * A map from method and URI to the response to return.
	 *
	 * @var array<string,array<string,array>>
	 */
	protected $mock_responses = [];

	/**
	 * Build and return a mock response.
	 *
	 * @param int          $status_code  The response status code.
	 * @param string|array $body         The response body.
	 * @param string       $content_type The response Content Type; e.g., 'application/json' or 'text/html'.
	 *
	 * @return array<string,mixed> The mock response, in the same format returned by the `wp_remote_request()` function.
	 *
	 * @throws \JsonException If the response body cannot be encoded as JSON.
	 */
	public function make_response( int $status_code, $body, string $content_type = 'applicaton/json' ): array {
		if ( $content_type === 'application/json' ) {
			$body = is_string( $body ) ? $body : json_encode( $body, JSON_THROW_ON_ERROR );
		} elseif ( $content_type === 'application/x-www-form-urlencoded' ) {
			$body = is_string( $body ) ? $body : http_build_query( $body );
		}

		$url = rtrim( $this->get_url(), '/' );
		$current_date = ( new \DateTime( 'now', new \DateTimezone( 'GMT' ) ) )->format( 'D, d M Y H:i:s GMT' );
		$request_response = new Response();
		$request_response->headers = new Headers( [
			'date'                          => [ $current_date ],
			'content-type'                  => [ "$content_type; charset=UTF-8" ],
			'server'                        => [ 'nginx' ],
			'vary'                          => [ 'Accept-Encoding' ],
			'x-robots-tag'                  => [ 'noindex' ],
			'link'                          => [ '<' . $url . '>; rel="https://api.w.org/"' ],
			'x-content-type-options'        => [ 'nosniff' ],
			'access-control-expose-headers' => [ 'X-WP-Total, X-WP-TotalPages, Link' ],
			'access-control-allow-headers'  => [ 'Authorization, X-WP-Nonce, Content-Disposition, Content-MD5, Content-Type' ],
			'allow'                         => [ 'GET, POST' ],
			'strict-transport-security'     => [ 'max-age=31536000; includeSubdomains; preload;' ],
			'cache-control'                 => [ 'store, must-revalidate, post-check=0, pre-check=0' ],
			'access-control-allow-origin'   => [ '*' ],
			'x-frame-options'               => [ 'SAMEORIGIN' ],
			'x-xss-protection'              => [ '1; mode=block' ],
			'alternate-protocol'            => [ '443:npn-spdy/3' ],
			'x-ua-compatible'               => [ 'IE=Edge' ],
			'content-encoding'              => [ 'gzip' ],
		] );
		$request_response->cookies = new Jar( [] );
		$status_message = self::$status_messages[ $status_code ] ?? 'Unknown';
		foreach (
			[
				'body'             => $body,
				'raw'              => "HTTP/1.1 $status_code $status_message
Date: Wed, 05 Oct 2022 09:30:09 GMT
Content-Type: $content_type; charset=UTF-8
Transfer-Encoding: chunked
Connection: close
Server: nginx
Vary: Accept-Encoding
X-Robots-Tag: noindex
Link: <$url>; rel=\"https://api.w.org/\"
X-Content-Type-Options: nosniff
Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages, Link
Access-Control-Allow-Headers: Authorization, X-WP-Nonce, Content-Disposition, Content-MD5, Content-Type
Allow: GET, POST
Strict-Transport-Security: max-age=31536000; includeSubdomains; preload;
Cache-Control: store, must-revalidate, post-check=0, pre-check=0
Access-Control-Allow-Origin: *
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Alternate-Protocol: 443:npn-spdy/3
X-UA-Compatible: IE=Edge
Content-Encoding: gzip

$body",
				'status_code'      => $status_code,
				'protocol_version' => 1.1,
				'success'          => $status_code < 400,
				'redirects'        => 0,
				'url'              => 'https://pue.theeventscalendar.com/api/plugins/v2/license/validate',
				'history'          => [],
			] as $key => $value
		) {
			$request_response->{$key} = $value;
		}
		$http_response = new \WP_HTTP_Requests_Response( $request_response );

		return [
			'headers'       =>
				new \Requests_Utility_CaseInsensitiveDictionary(
					[
						'date'                          => $current_date,
						'content-type'                  => 'application/json; charset=UTF-8',
						'server'                        => 'nginx',
						'vary'                          => 'Accept-Encoding',
						'x-robots-tag'                  => 'noindex',
						'link'                          => '<https://pue.theeventscalendar.com/api/>; rel="https://api.w.org/"',
						'x-content-type-options'        =>
							[
								0 => 'nosniff',
								1 => 'nosniff',
							],
						'access-control-expose-headers' => 'X-WP-Total, X-WP-TotalPages, Link',
						'access-control-allow-headers'  => 'Authorization, X-WP-Nonce, Content-Disposition, Content-MD5, Content-Type',
						'allow'                         => 'GET, POST',
						'strict-transport-security'     => 'max-age=31536000; includeSubdomains; preload;',
						'cache-control'                 => 'store, must-revalidate, post-check=0, pre-check=0',
						'access-control-allow-origin'   => '*',
						'x-frame-options'               => 'SAMEORIGIN',
						'x-xss-protection'              => '1; mode=block',
						'alternate-protocol'            => '443:npn-spdy/3',
						'x-ua-compatible'               => 'IE=Edge',
						'content-encoding'              => 'gzip',
					]
				),
			'body'          => $body,
			'response'      => [ 'code' => $status_code, 'message' => $status_message, ],
			'cookies'       => [],
			'filename'      => null,
			'http_response' => $http_response,
		];
	}

	/**
	 * Sets up the HPTT API mock to return a response to a specific request.
	 *
	 * @param string         $method   The HTTP method to mock, defaults to `GET`.
	 * @param string         $uri      The URI to mock, relative to the URL specified by the extending class `get_url`
	 *                                 method.
	 * @param array|callable $response Either a response in array format, or a callable that will return a
	 *                                 response and will receive the request parsed arguments and URL as input.
	 *
	 * @return void The corresponding mock will be set up.
	 */
	public function will_reply_to_request( string $method, string $uri, $response ): void {
		$method = strtoupper( $method );
		$uri = '/' . ltrim( $uri, '/' );
		$key = "$method $uri";
		$this->mock_responses[ $key ] = $response;
		if ( ! has_filter( 'pre_http_request', [ $this, 'mock_http_response' ] ) ) {
			add_filter( 'pre_http_request', [ $this, 'mock_http_response' ], 10, 3 );
		}
	}

	/**
	 * Hooked on the `pre_http_request` filter to prefill the mocked HTTP responses.
	 *
	 * @param bool                $preempt     Whether to preempt an HTTP request's return value. Default `false`.
	 * @param array<string,mixed> $parsed_args The HTTP request arguments.
	 * @param string              $url         The full request URL.
	 *
	 * @return false|mixed Either the mocked response or `false` to let the request go through.
	 */
	public function mock_http_response( bool $preempt, array $parsed_args, string $url ) {
		$uri = '/' . ltrim( str_replace( $this->get_url(), '', $url ), '/' );
		$method = $parsed_args['method'] ?? 'GET';
		$key = "$method $uri";

		if ( ! isset( $this->mock_responses[ $key ] ) ) {
			// We do not have a mock for this, let the HTTP API run its course.
			return false;
		}

		$mock_response = $this->mock_responses[ $key ];

		if ( is_callable( $mock_response ) ) {
			$mock_response = $mock_response( $parsed_args, $url );
		}

		return $mock_response;
	}

	/**
	 * Returns the root URL to use for the mocked HTTP API requests.
	 *
	 * @return string The root URL to use for the mocked HTTP API requests.
	 */
	abstract protected function get_url(): string;

}