<?php

class CounterBookingPlugin {
	public static function register() {
		$instance = new self();

		add_action('widgets_init', [$instance, 'registerCounterBookingWidget']);
        add_action('wp_enqueue_scripts', [$instance, 'registerCounterBookingWidget']);
        add_action('wp_enqueue_scripts', [$instance, 'enqueues']);
        add_shortcode('counter-booking-form', [$instance, 'shortcode']);
	}

    public static function shortcode($atts) {
    	ob_start();
    	$formId = uniqid();
    	include 'form.partial.php';
		$html = ob_get_clean();
		
		return $html;
    }

    public static function enqueues() {
        $version = '1.0.1' . time();
        $baseUrl = plugin_dir_url(dirname(__FILE__)) . 'assets';

        wp_enqueue_style('vanilla-datepicker', $baseUrl . '/css/datepicker.css', [], $version);
        wp_enqueue_style('counter-booking-plugin', $baseUrl . '/css/counter-booking-plugin.css', [], $version);
        
        wp_enqueue_script('vanilla-datepicker', $baseUrl . '/js/datepicker-full.js', [], $version, true);
        wp_enqueue_script('counter-booking-plugin', $baseUrl . '/js/counter-booking-plugin.js', [], $version, true);
    }

    public static function registerCounterBookingWidget() {
    	register_widget('CounterBookingWidget');
    }
}
