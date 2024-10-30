<?php

$options = get_option('counter_booking_widget_settings');
$locations = isset($options['booking_engine_url']) ? explode(',', $options['booking_engine_url']) : [];

?>

<div id="counter-booking-form-holder-<?php echo $formId; ?>" class="counter-booking-form-holder">
	<div id="starting-form-<?php echo $formId; ?>" class="starting-form" <?php if (isset($options['background_color']) && trim($options['background_color']) !== ''): ?> style="background: <?php echo $options['background_color']; ?>;"<?php endif; ?>>
		<div class="location-holder">
			<label>Location</label>
			<?php if (count($locations) > 0): ?>
				<?php $firstLocation = explode('||', $locations[0]); ?>

				<input type="text" id="location-<?php echo $formId; ?>" value="<?php echo $firstLocation[0]; ?>" readonly="readonly" />
				<input type="hidden" id="location-value-<?php echo $formId; ?>" value="<?php echo isset($firstLocation[1]) ? $firstLocation[1] : $firstLocation[0]; ?>" readonly="readonly" />

				<div class="location-selector">
					<?php foreach ($locations as $location): ?>
						<?php $pieces = explode('||', $location); ?>
						<div class="location-selector-row" data-value="<?php echo isset($pieces[1]) ? $pieces[1] : $pieces[0]; ?>">
							<?php if (isset($pieces[3]) && $pieces[3]): ?>
								<img src="<?php echo $pieces[3]; ?>" />
							<?php endif; ?>
							<span class="lbl"><?php echo $pieces[0]; ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<div>
			<label>Check in</label>
			<input type="text" id="check-in-<?php echo $formId; ?>" placeholder="Add dates" readonly="readonly" />
		</div>
		<div>
			<label>Check out</label>
			<input type="text" id="check-out-<?php echo $formId; ?>" placeholder="Add dates" readonly="readonly" />
		</div>
		<div class="guests-holder">
			<label>Guests</label>
			<input type="text" id="guests-<?php echo $formId; ?>" value="2" readonly="readonly" />

			<div class="guests-selector">
				<div class="guests-selector-row">
					<span class="label">Adults</span>
					<div class="guests-switcher">
						<span class="btn minus"></span>
						<span class="value">2</span>
						<span class="btn plus"></span>
					</div>
				</div>
			</div>
		</div>
		<?php if (isset($options['promo_code_field']) && $options['promo_code_field'] === 'yes'): ?>
			<div>
				<label>Promotion code</label>
				<input type="text" id="promotion-code-<?php echo $formId; ?>" />
			</div>
		<?php endif; ?>
		<div class="button-holder">
			<button type="button" id="starting-form-button-<?php echo $formId; ?>" class="button" <?php if (isset($options['submit_button_color']) && trim($options['submit_button_color']) !== ''): ?> style="background: <?php echo $options['submit_button_color']; ?> !important;"<?php endif; ?>>
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0" viewBox="0 0 512.005 512.005" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
					<g>
						<g xmlns="http://www.w3.org/2000/svg">
							<g>
								<path d="M505.749,475.587l-145.6-145.6c28.203-34.837,45.184-79.104,45.184-127.317c0-111.744-90.923-202.667-202.667-202.667    S0,90.925,0,202.669s90.923,202.667,202.667,202.667c48.213,0,92.48-16.981,127.317-45.184l145.6,145.6    c4.16,4.16,9.621,6.251,15.083,6.251s10.923-2.091,15.083-6.251C514.091,497.411,514.091,483.928,505.749,475.587z     M202.667,362.669c-88.235,0-160-71.765-160-160s71.765-160,160-160s160,71.765,160,160S290.901,362.669,202.667,362.669z" fill="#ffffff" data-original="#000000" style="" class=""/>
							</g>
						</g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
						<g xmlns="http://www.w3.org/2000/svg"></g>
					</g>
				</svg>
				<span>Book now</span>
			</button>
		</div>
	</div>

	<div id="booking-modal-<?php echo $formId; ?>" class="counter-booking-modal">
		<header>
			<span id="booking-modal-close-<?php echo $formId; ?>">Close</span>
		</header>
		<main>
			<iframe src="about:blank" id="booking-form-<?php echo $formId; ?>" class="counter-booking-form"></iframe>
		</main>
	</div>
</div>
