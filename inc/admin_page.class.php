<?php

class CounterAdminPage {
    private $options;

    public function __construct() {
        add_action('admin_menu', [$this, 'menu_init']);
        add_action('admin_init', [$this, 'page_init']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

    }

    public function enqueue_scripts($hook) {
        if ('settings_page_counter-booking-widget-settings' !== $hook)  {
            return;
        }

        wp_enqueue_script('counter-vue.js', plugin_dir_url(dirname(__FILE__)) . 'assets/js/vue.js', [], '2.6.12');
    }

    public function menu_init() {
        add_options_page(
            'Counter Booking Widget', 
            'Counter Booking Widget', 
            'manage_options', 
            'counter-booking-widget-settings', 
            [$this, 'page']
        );
    }

    public function page() {
        $this->options = get_option('counter_booking_widget_settings');
        ?>
        <div class="wrap">
            <h1>Counter Booking Widget Settings</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields('counter_booking_widget_settings_group');
                do_settings_sections('counter-booking-settings-admin');
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function page_init() {
        wp_enqueue_media();

        register_setting(
            'counter_booking_widget_settings_group',
            'counter_booking_widget_settings',
            [$this, 'sanitize']
        );

        add_settings_section(
            'counter_booking_widget_settings_section_1',
            '',
            [$this, 'info'],
            'counter-booking-settings-admin'
        );

        add_settings_field(
            'booking_engine_url',
            'Booking engine URL',
            [$this, 'multifield'],
            'counter-booking-settings-admin',
            'counter_booking_widget_settings_section_1',
            ['name' => 'booking_engine_url']
        );

        add_settings_field(
            'promo_code_field',
            'Promo code field',
            [$this, 'radiolist'],
            'counter-booking-settings-admin',
            'counter_booking_widget_settings_section_1',
            ['name' => 'promo_code_field']
        );

        add_settings_field(
            'background_color',
            'Background color (hexcode)',
            [$this, 'textfield'],
            'counter-booking-settings-admin',
            'counter_booking_widget_settings_section_1',
            ['name' => 'background_color']
        );

        add_settings_field(
            'submit_button_color',
            'Submit button color (hexcode)',
            [$this, 'textfield'],
            'counter-booking-settings-admin',
            'counter_booking_widget_settings_section_1',
            ['name' => 'submit_button_color']
        );
    }

    public function sanitize($input) {
        $new = [];

        if (isset($input['booking_engine_url'])) {
            $new['booking_engine_url'] = sanitize_text_field($input['booking_engine_url']);
        }

        if (isset($input['promo_code_field'])) {
            $new['promo_code_field'] = sanitize_text_field($input['promo_code_field']);
        }

        if (isset($input['background_color'])) {
            $new['background_color'] = sanitize_text_field($input['background_color']);
        }

        if (isset($input['submit_button_color'])) {
            $new['submit_button_color'] = sanitize_text_field($input['submit_button_color']);
        }

        return $new;
    }

    public function info() {
        echo '<p>Customize your own Countr.app booking form before displaying it on your website.</p>';
    }

    public function multifield($args) {
        $name = $args['name'];
        $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';

        printf("<div id='multifield'></div>
            <style type='text/css'>
                #multifield th, #multifield td { padding: 0px; }
                #multifield .field-label { width: 200px; }
                #multifield .field-url { width: 400px; }
            </style>
            <script type='text/javascript'>
                new Vue({
                    el: '#multifield',
                    data: {
                        message: 'Hello Vue!',
                        value: '%s',
                        entries: [],
                        frame: null,
                        indexInProgress: 0
                    },
                    mounted: function() {
                        var entries = this.value.split(',');
                        for (var i in entries) {
                            var pieces = entries[i].split('||');
                            this.entries.push({ 
                                id: Math.random(), 
                                label: pieces[0], 
                                url: pieces.length > 0 ? pieces[1] : '',
                                image_id: pieces.length > 1 ? pieces[2] : 0,
                                image_thumbnail_url: pieces.length > 2 ? pieces[3] : '',
                            });
                        }
                    },
                    methods: {
                        onAdd: function() {
                            this.entries.push({
                                id: Math.random(), 
                                label: '', 
                                url: '',
                                image_id: 0,
                                image_thumbnail_url: ''
                            });
                        },
                        onUpdate: function() {
                            var entries = [];

                            for (var i in this.entries) {
                                var entry = this.entries[i];

                                if (entry.label.trim() !== '' || entry.url.trim() !== '') {
                                    entries.push(
                                        entry.label + '||' + 
                                        entry.url + '||' + 
                                        (entry.image_id ? entry.image_id : 0) + '||' + 
                                        (entry.image_thumbnail_url ? entry.image_thumbnail_url : '')
                                    );
                                }
                            }

                            this.value = entries.join(',');
                        },
                        onUpload: function(index) {
                            var self = this;
                            this.indexInProgress = index;

                            if (this.frame) {
                                this.frame.open();
                                return;
                            }
    
                            this.frame = wp.media({
                                multiple: false
                            });

                            this.frame.on('select', function() {
                                var attachment = self.frame.state().get('selection').first().toJSON();
                                self.entries[self.indexInProgress].image_id = attachment.id;
                                self.entries[self.indexInProgress].image_thumbnail_url = attachment.sizes.thumbnail.url;
                                self.onUpdate();
                            });

                            this.frame.open();
                        },
                        onRemove: function(index) {
                            this.entries.splice(index, 1);
                            this.onUpdate();
                        }
                    },
                    template: `
                        <div id='multifield'>
                            <table>
                                <tr>
                                    <th>City</th>
                                    <th>URL</th>
                                    <th>Icon</th>
                                </tr>
                                <tr v-for='(entry, i) in entries' :key='entry.id'>
                                    <td>
                                        <input type='text' v-model='entry.label' class='field-label' @keyup='onUpdate' />
                                    </td>
                                    <td>
                                        <input type='text' v-model='entry.url' class='field-url' @keyup='onUpdate' />
                                    </td>
                                    <td>
                                        <button type='button'@click='onUpload(i)' style='vertical-align: middle;'>Set icon</button>
                                        <img v-if='entry.image_thumbnail_url' :src='entry.image_thumbnail_url' width='45' style='vertical-align: middle; border: 2px solid transparent;' />
                                    </td>
                                    <td v-if='i>0'>
                                        <button type='button' @click='onRemove(i)'>-</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <button type='button' @click='onAdd'>+</button>
                                    </td>
                                </tr>
                            </table>
                            <input type='hidden' name='counter_booking_widget_settings[%s]' v-model='value' />
                        </div>
                    `
                });
            </script>
            ",
            $value,
            $name
        );
    }

    public function textfield($args) {
        $name = $args['name'];
        printf(
            '<input type="text" name="counter_booking_widget_settings[%s]" value="%s" style="width: 300px;" />',
            $name,
            isset($this->options[$name]) ? esc_attr($this->options[$name]) : ''
        );
    }

    public function radiolist($args) {
        $name = $args['name'];
        $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';

        printf(
            '<label>
                <input type="radio" name="counter_booking_widget_settings[%s]" value="yes" %s />
                Yes
            </label>
            <br/>
            <label>
                <input type="radio" name="counter_booking_widget_settings[%s]" value="no" %s />
                No
            </label>
            ',
            $name,
            $value === 'yes' ? 'checked="checked"' : '',
            $name,
            $value !== 'yes' ? 'checked="checked"' : ''
        );
    }
}

if (is_admin()) {
    new CounterAdminPage();
}
