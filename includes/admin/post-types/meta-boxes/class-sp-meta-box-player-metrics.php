<?php
/**
 * Player Metrics
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Player_Metrics
 */
class SP_Meta_Box_Player_Metrics {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$metrics = get_post_meta( $post->ID, 'sp_metrics', true );
		$positions = get_the_terms( $post->ID, 'sp_position' );

		$args = array(
			'post_type' => 'sp_metric',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);

		if ( $positions ):
			$position_ids = array();
			foreach( $positions as $position ):
				$position_ids[] = $position->term_id;
			endforeach;
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'sp_position',
					'field' => 'id',
					'terms' => $position_ids,
				),
			);
		endif;

		$vars = get_posts( $args );

		if ( $vars ):
			foreach ( $vars as $var ):
			?>
			<p><strong><?php echo $var->post_title; ?></strong></p>
			<p><input type="text" name="sp_metrics[<?php echo $var->post_name; ?>]" value="<?php echo sportspress_array_value( $metrics, $var->post_name, '' ); ?>" /></p>
			<?php
			endforeach;
		else:
			sportspress_post_adder( 'sp_metric', __( 'Add New', 'sportspress' ) );
		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_metrics', sportspress_array_value( $_POST, 'sp_metrics', array() ) );
	}
}