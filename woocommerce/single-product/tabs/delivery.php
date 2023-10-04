<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$heading = 'Доставка';

?>

<?php if ( $heading ): ?>
  <h2><?php echo $heading; ?></h2>
<?php endif; ?>

<?php 
	global $post;
	$terms = get_the_terms( $post->ID, 'product_cat' );
	foreach ($terms as $term) {
	    $product_cat_id = $term->term_id;

	    switch ( $product_cat_id ) {
				case 748:
					$my_postid = 21712;//This is page id or post id
					break;
				case 749:
					$my_postid = 21710;//This is page id or post id
					break;
                                case 686:
					$my_postid = 21714;//This is page id or post id
                                        break;
                                case 607:
                                case 439:
                                case 478:
                                case 565:
                                case 570:
					$my_postid = 18324;//This is page id or post id
//					if ( $_REQUEST['moscow_in_stock'] == true )
//						$my_postid = 
                                        break;
                                default:
					$my_postid = 21716;//This is page id or post id
                                        break;
	    }

	    break;
	}

	$content_post = get_post($my_postid);
	$content = $content_post->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	echo $content;
?>
