<?php
/*
Plugin Name: Fallback thumbnail
Description: Set a default fallback thumbnail. Your posts will always have a thumbnail.
Version: 1.0
Author: ArnaudBan
Author URI: https://arnaudban.me/
*/


add_filter( 'post_thumbnail_html', 'gft_default_post_thumbnail_html', 10, 5 );

function gft_default_post_thumbnail_html( $html, $post_ID, $post_thumbnail_id, $size, $attr ){

    if(  $html == '' ){

        // class = wp-post-image
        $default_image_id = get_theme_mod( 'gft_fallback_thumbnail' );

        if( $default_image_id ){

            if( ! ( isset( $attr['class'] ) && ! empty( $attr['class'] ) ) ){

                $size_class = $size;
                if ( is_array( $size_class ) ) {
                    $size_class = join( 'x', $size_class );
                }
                $size_class .= "attachment-$size_class size-$size_class wp-post-image";

                if( $attr == '' ){
                    $attr = array();
                }
                $attr['class'] = $size_class;
            }

            $html = wp_get_attachment_image( $default_image_id, $size, false, $attr );
        }


    }

    return $html;
}


add_action( 'customize_register', 'gft_customize_register_default_thumbnail' );

function gft_customize_register_default_thumbnail( $wp_customize ){

    $wp_customize->add_setting( 'gft_fallback_thumbnail', array(
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'transport'         => 'refresh',
    ) );


    // On ajoute le controle pour le texte en footer
    $wp_customize->add_control( new WP_Customize_Media_Control(
        $wp_customize,
        'gft_fallback_thumbnail',
        array(
            'label'      => __('Fallback thumbnail'),
            'section'    => 'gft_fallback_thumbnail_section',
            'settings'   => 'gft_fallback_thumbnail',
        )
    ) );

    // On ajout une section pour les pages spéciales
    $wp_customize->add_section( 'gft_fallback_thumbnail_section', array(
        'title'         => __('Fallback thumbnail'),
        'description'   => __('Set a default fallback thumbnail'),
        'capability'    => 'edit_theme_options',
    ) );
}

