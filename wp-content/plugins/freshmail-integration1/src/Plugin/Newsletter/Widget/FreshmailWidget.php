<?php 
namespace Plugin\Newsletter\Widget;

class FreshmailWidget extends \WP_Widget
{

	public function __construct()
	{
		parent::__construct(true, __('FreshMail', 'wp_freshmail'), array('description' => __('A widget that displays the FreshMail sign up Forms', 'wp_freshmail')));
	}

	public function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;

		if (isset($instance['form_id'])) {
			echo do_shortcode('[FM_form id="'.$instance['form_id'].'"]');
		}

		echo $after_widget;
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];
		$instance['form_id'] = $new_instance['form_id'];

		return $instance;
	}

	public function form($instance)
	{
		global $wpdb;
		$results = $wpdb->get_results('SELECT form_id FROM '.$wpdb->prefix.'freshmail_forms', OBJECT);

		$instance = wp_parse_args((array)$instance, array('title' => __('newsletter', 'wp_freshmail'))); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp_freshmail'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title') ?>" id="<?php echo $this->get_field_id('title') ?> " value="<?php echo $instance['title'] ?>" size="20"/>
		</p>
		<?php echo '<p>';
		if (count($results)) {
			echo '<label for="'.$this->get_field_id('form_id').'">'.__('Sign Up Form', 'wp_freshmail').'</label><br /><select name="'.$this->get_field_name('form_id').'">';
			foreach ($results as $val) {
				echo '<option value="'.$val->form_id.'" '.($val->form_id == $instance['form_id'] ? 'selected="selected"' : null).'>'.__('Sign Up Form', 'wp_freshmail').' #'.$val->form_id.'</option>';
			}
			echo '</select>';
		} else {
			echo '<p class="no-options-widget">'.__('There are no form. <a href="admin.php?page=freshmail_add_new_form">Add first</a>.', 'wp_freshmail').'</p>';
		}
		echo '</p>';

		require(WP_FRESHMAIL_DIR.'/templates/google_url.php');

		return null;
	}
}