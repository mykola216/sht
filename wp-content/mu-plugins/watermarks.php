<?php
$pattern = '/^\/wp-content\/uploads\/(\d{4}\/\d{2})\/(.+\.(?:jpg|png))$/i';
if ( preg_match( $pattern, $_SERVER['REQUEST_URI'], $matches ) ) {
	$request_file_name = untrailingslashit( ABSPATH ) . $matches[0];
	if ( file_exists( $request_file_name ) ) {

		$path = ABSPATH . 'wp-content/cache/' . $matches[1];
		$filename = $matches[2];

		$watermark = imagecreatefrompng( __DIR__ . '/watermark.png' );
		$image = imagecreatefromjpeg( $request_file_name );

		if ( $watermark && $image ) {
			if ( filemtime( $request_file_name ) > strtotime('21 february 2016') ) {
				// вотермарк
				$src_x = 0;
				$src_y = 0;
				$src_w = imagesx( $watermark );
				$src_h = imagesy( $watermark );
				// картинка
				$dst_w = intval( imagesx( $image ) );
				$dst_w = $dst_w > 300 ? $dst_w / 2 : $dst_w;
				$dst_h = intval( $dst_w / $src_w * $src_h );
				$dst_x = intval( imagesx( $image ) - $dst_w );
				$dst_y = intval( imagesy( $image ) - $dst_h );

				$result = imagecopyresampled( $image, $watermark, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );
				if ( $result ) {
					if ( ! is_dir( $path ) ) {
						mkdir( $path, 0755, true );
					}
					imagejpeg( $image, $path . '/' . $filename );
				}
			}

			header( 'Content-type: image/jpeg' );
			imagejpeg( $image );
			die();
		}

	}
}
