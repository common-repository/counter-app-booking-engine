<?php

class CounterBookingWidget extends WP_Widget {
	function __construct() {
		parent::__construct('CounterBookingWidget',  __('Counter.App Booking Form', 'counter'), ['description' => __('Counter.app booking form', 'counter')]);
	}

	public function widget($args, $instance) {
		echo $args['before_widget'];

		$formId = uniqid();
		include 'form.partial.php';

		echo $args['after_widget'];
	}

	public function form($instance) {
		?>

		<p>
			<em>Future settings to come here...</em>
		</p>

		<?php 
	}

	public function update($new, $old) {
		$instance = [];

		return $instance;
	}
}
