<?php												


function ensure_bounds ( &$val )
{
	$val = round( $val );
	if ( $val < 0 ) $val = 0;
	elseif ( $val > 255 ) $val = 255;			
}

function create_rgb_variable( $r, $g, $b )
{
	$rgb_variable = sprintf( "%02x%02x%02x", $r, $g, $b);
	return $rgb_variable;
}
		
function calculate_colors( $jpgfile )
{
 	$filename = $jpgfile;
 	$color_interval = 32;
 	
 	$image = imagecreatefromjpeg( $filename );
 	$primary = "000000";
 	$secondary = "000000";
 	$tertiary = "000000";
 	
	if ( $image ) 
	{
		$dim = getimagesize( $filename );
	 	$rgb = ImageColorAt( $image, 0, 0 );
		$total_pixels = 0;

		$primary_r = 0;
		$primary_g = 0;
		$primary_b = 0;
			
		for ( $x = 0; $x < $dim[0]; $x++ )
		{
			for ( $y = 0; $y < $dim[1]; $y++ )
			{
				$rgb = ImageColorAt( $image, $x, $y );
				$tval = ( ($rgb >> 16) & 0xFF );
				$primary_r += $tval;
					
				$tval = ( ($rgb >> 8) & 0xFF );
				$primary_g += $tval;

				$tval = ( $rgb & 0xFF );
				$primary_b += $tval;
				++$total_pixels;
			}
		}

		$primary_r /= $total_pixels;
 		$primary_g /= $total_pixels;
 		$primary_b /= $total_pixels;
			
		ensure_bounds( $primary_r );
		ensure_bounds( $primary_g );
		ensure_bounds( $primary_b );
		$primary = create_rgb_variable( $primary_r, $primary_g, $primary_b );

		$secondary_r = $primary_r - $color_interval;
		$secondary_g = $primary_g - $color_interval;
		$secondary_b = $primary_b - $color_interval;

		ensure_bounds( $secondary_r );
		ensure_bounds( $secondary_g );
		ensure_bounds( $secondary_b );
		$secondary = create_rgb_variable( $secondary_r, $secondary_g, $secondary_b );
 			
		$tertiary_r = $primary_r + ( $color_interval * 2 );
		$tertiary_g = $primary_g + ( $color_interval * 2 );
		$tertiary_b = $primary_b + ( $color_interval * 2 );

		ensure_bounds( $tertiary_r );
		ensure_bounds( $tertiary_g );
		ensure_bounds( $tertiary_b );
		$tertiary = create_rgb_variable( $tertiary_r, $tertiary_g, $tertiary_b );
	}
	
	return array( $primary, $secondary, $tertiary );
}
?>
