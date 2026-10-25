<?php
/**
 * Import a remote image into the media library — the server half of the
 * "Import to this site" button in blocks/_shared/ImagePicker.js, which shows
 * on an image whose URL is on another site (typically a block copied in from
 * another website, still pointing at the original's media).
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

const LC_TIDYJS2026_IMPORT_MAX_BYTES = 10 * MB_IN_BYTES;
const LC_TIDYJS2026_IMPORT_SOURCE_META = '_lc_tidyjs2026_source_url';

/**
 * Register the import route.
 *
 * @return void
 */
function lc_tidyjs2026_register_image_import_route() {
	register_rest_route(
		'lc-tidyjs2026/v1',
		'/import-image',
		array(
			'methods'             => 'POST',
			'callback'            => 'lc_tidyjs2026_import_image',
			'permission_callback' => function () {
				return current_user_can( 'upload_files' );
			},
			'args'                => array(
				'url' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'esc_url_raw',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'lc_tidyjs2026_register_image_import_route' );

/**
 * Download the image and add it to the media library. An image already
 * imported from the same URL is reused instead of downloaded again.
 *
 * @param WP_REST_Request $request Request with a `url` param.
 * @return array|WP_Error `{ id, url, alt }` or an error.
 */
function lc_tidyjs2026_import_image( $request ) {
	$url = $request->get_param( 'url' );

	if ( ! $url || ! in_array( wp_parse_url( $url, PHP_URL_SCHEME ), array( 'http', 'https' ), true ) ) {
		return new WP_Error( 'lc_import_bad_url', __( 'That is not a valid image URL.', 'lc-tidyjs2026' ), array( 'status' => 400 ) );
	}

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => LC_TIDYJS2026_IMPORT_SOURCE_META, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $url, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);

	if ( $existing ) {
		return lc_tidyjs2026_import_response( $existing[0] );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// download_url() uses wp_safe_remote_get(): it refuses private/loopback hosts.
	$tmp = download_url( $url, 30 );

	if ( is_wp_error( $tmp ) ) {
		return new WP_Error( 'lc_import_download', $tmp->get_error_message(), array( 'status' => 502 ) );
	}

	// A streamed download is not decoded by WordPress, so a server that gzips
	// the file (common for SVG) leaves the compressed bytes in the temp file.
	if ( "\x1f\x8b" === file_get_contents( $tmp, false, null, 0, 2 ) && function_exists( 'gzdecode' ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$plain = gzdecode( file_get_contents( $tmp, false, null, 0, LC_TIDYJS2026_IMPORT_MAX_BYTES ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( false !== $plain ) {
			file_put_contents( $tmp, $plain ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_put_contents_file_put_contents
		}
	}

	$name = sanitize_file_name( wp_basename( (string) wp_parse_url( $url, PHP_URL_PATH ) ) );
	$type = wp_check_filetype_and_ext( $tmp, $name );

	// WordPress refuses SVG outright. When the Safe SVG plugin is active it
	// allows SVG on upload and sanitises the file itself (including on
	// sideload), so a genuine-looking .svg is handed to it; without the
	// plugin SVG stays refused, as it is for every other upload.
	if ( ! $type['type'] && class_exists( 'SafeSvg\\safe_svg' ) && lc_tidyjs2026_looks_like_svg( $tmp, $name ) ) {
		$type = array(
			'type'            => 'image/svg+xml',
			'proper_filename' => false,
		);
	}

	if ( filesize( $tmp ) > LC_TIDYJS2026_IMPORT_MAX_BYTES || ! $type['type'] || 0 !== strpos( $type['type'], 'image/' ) ) {
		wp_delete_file( $tmp );
		return new WP_Error( 'lc_import_type', __( 'The file is not an allowed image type, or is too large (10MB max).', 'lc-tidyjs2026' ), array( 'status' => 415 ) );
	}

	$id = media_handle_sideload(
		array(
			'name'     => $type['proper_filename'] ? $type['proper_filename'] : $name,
			'tmp_name' => $tmp,
		)
	);

	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );
		return new WP_Error( 'lc_import_sideload', $id->get_error_message(), array( 'status' => 500 ) );
	}

	update_post_meta( $id, LC_TIDYJS2026_IMPORT_SOURCE_META, $url );

	return lc_tidyjs2026_import_response( $id );
}

/**
 * Shape an attachment as the picker's onSelect() receives it.
 *
 * @param int $id Attachment ID.
 * @return array
 */
function lc_tidyjs2026_import_response( $id ) {
	return array(
		'id'  => (int) $id,
		'url' => wp_get_attachment_url( $id ),
		'alt' => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
	);
}

/**
 * Whether a downloaded file is named .svg and its start is an <svg> document.
 * Only a cheap gate: Safe SVG does the real sanitising.
 *
 * @param string $path Temp file path.
 * @param string $name Sanitised file name.
 * @return bool
 */
function lc_tidyjs2026_looks_like_svg( $path, $name ) {
	if ( 'svg' !== strtolower( pathinfo( $name, PATHINFO_EXTENSION ) ) ) {
		return false;
	}

	$head = file_get_contents( $path, false, null, 0, 8192 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	return false !== $head && false !== stripos( $head, '<svg' );
}
