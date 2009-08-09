<?php
/*
Plugin Name:  factory-featured
Plugin URI:   http://www.factory42.co.uk/
Description:  Nice simple Widget that shows the latest 5 (or more) stories from a set category with linked titles and linked custom field images. 
Version:      1.2
Author:       David Knight (david@Factory42.co.uk)
Author URI:   http://www.factory42.co.uk	
*/
	function f42_factory_featured($args, $widget_args = 1) {
		extract( $args, EXTR_SKIP );
		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
	
		$options = get_option('factory_featured');
		if ( !isset($options[$number]) ) 
		return;
		$title = $options[$number]['title']; 		
		$text = $options[$number]['text']; 		
		$custom = $options[$number]['custom']; 	
		$width = $options[$number]['width']; 		
		$showno = $options[$number]['showno']; 		
		$height = $options[$number]['height']; 		
echo $before_widget; // start widget display code ?>
<style type="text/css">
.ffcont {
	width:<?php echo $width;?>px;
	padding: 10px;
}
.ff-image {
	height:<?php echo $height;?>px;
	width:<?php echo $width;?>px;
}
.f42 {
	color: #fff;
	font-size: 8px;
	float: right;
}
dl.ffbox {
	width:<?php echo $width;?>px;
	padding: 5px;
	margin-left: auto;
	margin-right: auto;
}
</style>
<div class="ffcont">

<h3><?=$title?></h3>
<?php query_posts('cat=' . $text .'&showposts=' . $showno .''); ?>
    <?php while (have_posts()) : the_post(); ?>
	<dl class="ffbox"> <dt> 
<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">

 <img class="ff-image" src="<?php bloginfo('wpurl'); ?>/wp-content/uploads/<?php  
	$values = get_post_custom_values($custom); echo $values[0]; ?>" id="bimage" alt="" /></a> </dt>
<dd><a class="ff-link" href="<?php the_permalink() ?>" rel="bookmark"> <?php the_title(); ?></a></dd>
</dl> 
    <?php endwhile; ?>
<a class="f42" "href="http://www.factory42.co.uk" title="Factory42 web design, wordpress themes and widgets">F42</a>
<br style="clear:both" />&nbsp; 
</div>


       <?php echo $after_widget; 
}
function f42_factory_featured_control($widget_args) {
		global $wp_registered_widgets;
static $updated = false;
	
	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );			
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );
	
	$options = get_option('factory_featured');
		
			if ( !is_array($options) )	
			$options = array();
	
		if ( !$updated && !empty($_POST['sidebar']) ) {
		
			$sidebar = (string) $_POST['sidebar'];	
			$sidebars_widgets = wp_get_sidebars_widgets();
			
			if ( isset($sidebars_widgets[$sidebar]) )
				$this_sidebar =& $sidebars_widgets[$sidebar];
			else
				$this_sidebar = array();
	
			foreach ( (array) $this_sidebar as $_widget_id ) {
				if ( 'f42_factory_featured' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
					$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
					if ( !in_array( "factory-featured-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
						unset($options[$widget_number]);
				}
			}
	
			foreach ( (array) $_POST['factory-featured'] as $widget_number => $factory_featured ) {
				if ( !isset($factory_featured['title']) && isset($options[$widget_number]) ) // user clicked cancel
					continue;
				
				$title = strip_tags(stripslashes($factory_featured['title']));
				$text = strip_tags(stripslashes($factory_featured['text_value']));	
					$custom = strip_tags(stripslashes($factory_featured['custom']));				
				$showno = strip_tags(stripslashes($factory_featured['showno']));		
				$width = strip_tags(stripslashes($factory_featured['width']));			
				$height = strip_tags(stripslashes($factory_featured['height']));		
			
				$options[$widget_number] = compact( 'title', 'width', 'height', 'custom', 'showno', 'text'  );
			}
	
			update_option('factory_featured', $options);
			$updated = true;
		}
	
		if ( -1 == $number ) { 
	
			$title = '';
			$text = '';
			$width = '';
			$custom = '';		
			$showno = '';			
			$height = '';
			$number = '%i%';
			
		} else { 
		
			$title = attribute_escape($options[$number]['title']);
			$text = attribute_escape($options[$number]['text']); 
			$custom = attribute_escape($options[$number]['custom']); 
			$showno = attribute_escape($options[$number]['showno']); 
			$width = attribute_escape($options[$number]['width']); 
			$height = attribute_escape($options[$number]['height']); 
		}

	?>
	<p>Please fill in the options below. 
	
	
	 Help can be found here <a href="http://www.factory42.co.uk/factory-feature-wordpress-plugin">Plugin help</a> We also take any kind donations though that site as well! Thanks!</p>


	<p><label>Widget Title</label><br /><input id="title_value_<?php echo $number; ?>" name="factory-featured[<?php echo $number; ?>][title]" type="text" value="<?=$title?>" /></p>
	
<p><label>Category Number</label><br /><input id="text_value_<?php echo $number; ?>" name="factory-featured[<?php echo $number; ?>][text_value]" type="text" size="6" value="<?=$text?>" /></p>
    
<p><label>Number of posts to show</label><br /><input id="showno_value_<?php echo $number; ?>" name="factory-featured[<?php echo $number; ?>][showno]" type="showno" size="6" value="<?=$showno?>" /></p> 

<p><label>Custom Image Field Name</label><br /><input id="custom_value_<?php echo $number; ?>" name="factory-featured[<?php echo $number; ?>][custom]" type="custom" size="20" value="<?=$custom?>" /></p> 
    
<p><label>Image Width</label><br /><input id="width_value_<?php echo $number; ?>" name="factory-featured[<?php echo $number; ?>][width]" type="width" size="6" value="<?=$width?>" />px</p>
    
<p><label>Image Height</label><br /><input id="height_value_<?php echo $number; ?>" name="factory-featured[<?php echo $number; ?>][height]" type="height" size="6" value="<?=$height?>" />px</p>
 
<input type="hidden" name="factory-featured[<?php echo $number; ?>][submit]" value="1" />
<?php
	}
	
function f42_factory_featured_register() {
	if ( !$options = get_option('factory_featured') )
		$options = array();
		$widget_ops = array('classname' => 'factory_featured', 'description' => __('Test widget form'));
		$control_ops = array('width' => 250, 'height' => 350, 'id_base' => 'factory-featured');
		$name = __('Factory Featured');
		$id = false;
		
		foreach ( (array) array_keys($options) as $o ) {
		if ( !isset( $options[$o]['title'] ) )
		continue;
						
		$id = "factory-featured-$o";
		wp_register_sidebar_widget($id, $name, 'f42_factory_featured', $widget_ops, array( 'number' => $o ));
		wp_register_widget_control($id, $name, 'f42_factory_featured_control', $control_ops, array( 'number' => $o ));
		}
		
		if ( !$id ) {
			wp_register_sidebar_widget( 'factory-featured-1', $name, 'f42_factory_featured', $widget_ops, array( 'number' => -1 ) );
			wp_register_widget_control( 'factory-featured-1', $name, 'f42_factory_featured_control', $control_ops, array( 'number' => -1 ) );
		}
	}

add_action('init', f42_factory_featured_register, 1);

?>