<?php
/**
 * Kumpulan shortcode yang digunakan di theme ini.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
//[resize-thumbnail width="300" height="150" linked="true" class="w-100"]
add_shortcode('resize-thumbnail', 'resize_thumbnail');
function resize_thumbnail($atts) {
    ob_start();
	global $post;
    $atribut = shortcode_atts( array(
        'output'	=> 'image', /// image or url
        'width'    	=> '300', ///width image
        'height'    => '150', ///height image
        'crop'      => 'false',
        'upscale'   	=> 'true',
        'linked'   	=> 'true', ///return link to post	
        'class'   	=> 'w-100', ///return class name to img	
        'attachment' 	=> 'true'
    ), $atts );

    $output			= $atribut['output'];
    $attach         = $atribut['attachment'];
    $width          = $atribut['width'];
    $height         = $atribut['height'];
    $crop           = $atribut['crop'];
    $upscale        = $atribut['upscale'];
    $linked        	= $atribut['linked'];
    $class        	= $atribut['class']?'class="'.$atribut['class'].'"':'';
	$urlimg			= get_the_post_thumbnail_url($post->ID,'full');
	
	if(empty($urlimg) && $attach == 'true'){
          $attachments = get_posts( array(
            'post_type' 		=> 'attachment',
            'posts_per_page' 	=> 1,
            'post_parent' 		=> $post->ID,
        	'orderby'          => 'date',
        	'order'            => 'DESC',
          ) );
          if ( $attachments ) {
				$urlimg = wp_get_attachment_url( $attachments[0]->ID, 'full' );
          }
    }

	if($urlimg):
		$urlresize      = aq_resize( $urlimg, $width, $height, $crop, true, $upscale );
		if($output=='image'):
			if($linked=='true'):
				echo '<a href="'.get_the_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
			endif;
			echo '<img src="'.$urlresize.'" width="'.$width.'" height="'.$height.'" loading="lazy" '.$class.'>';
			if($linked=='true'):
				echo '</a>';
			endif;
		else:
			echo $urlresize;
		endif;
	endif;

	return ob_get_clean();
}

//[excerpt count="150"]
add_shortcode('excerpt', 'vd_getexcerpt');
function vd_getexcerpt($atts){
    ob_start();
	global $post;
    $atribut = shortcode_atts( array(
        'count'	=> '150', /// count character
    ), $atts );

    $count		= $atribut['count'];
    $excerpt	= get_the_content();
    $excerpt 	= strip_tags($excerpt);
    $excerpt 	= substr($excerpt, 0, $count);
    $excerpt 	= substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt 	= ''.$excerpt.'...';

    echo $excerpt;

	return ob_get_clean();
}

// [vel-breadcrumbs]
add_shortcode('vd-breadcrumbs','vd_breadcrumbs');
function vd_breadcrumbs() {
    ob_start();
    echo justg_breadcrumb();
    return ob_get_clean();
}

//[ratio-thumbnail size="medium" ratio="16:9"]
add_shortcode('ratio-thumbnail', 'ratio_thumbnail');
function ratio_thumbnail($atts) {
    ob_start();
	global $post;

    $atribut = shortcode_atts( array(
        'size'      => 'medium', // thumbnail, medium, large, full
        'ratio'     => '16:9', // 16:9, 8:5, 4:3, 3:2, 1:1
    ), $atts );

    $size       = $atribut['size'];
    $ratio      = $atribut['ratio'];
    $ratio      = $ratio?str_replace(":","-",$ratio):'';
	$urlimg     = get_the_post_thumbnail_url($post->ID,$size);

    echo '<div class="ratio-thumbnail">';
        echo '<a class="ratio-thumbnail-link" href="'.get_the_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
            echo '<div class="ratio-thumbnail-box ratio-thumbnail-'.$ratio.'" style="background-image: url('.$urlimg.');">';
                echo '<img src="'.$urlimg.'" loading="lazy" class="ratio-thumbnail-image"/>';
            echo '</div>';
        echo '</a>';
    echo '</div>';

	return ob_get_clean();
}