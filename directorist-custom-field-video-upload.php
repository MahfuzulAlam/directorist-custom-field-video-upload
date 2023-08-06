<?php

/** 
 * @package  Directorist - Custom field Video Upload
 */

/**
 * Plugin Name:       Directorist - Custom field Video Upload
 * Plugin URI:        https://wpwax.com
 * Description:       Custom field Video Upload for direcorist
 * Version:           1.0.0
 * Requires at least: 5.2
 * Author:            wpWax
 * Author URI:        https://wpwax.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       directorist-custom-field-video-upload
 * Domain Path:       /languages
 */

/* This is an extension for Directorist plugin. It helps to generate Video Upload custom field in Directorist plugin.*/

/**
 * If this file is called directly, abrot!!!
 */
if (!defined('ABSPATH')) {
    exit;                      // Exit if accessed
}

if (!class_exists('Directorist_Custom_Field_Video_Upload')) {

    final class Directorist_Custom_Field_Video_Upload
    {
        /**
         * Instance
         */
        private static $instance;

        /**
         * Instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof Directorist_Custom_Field_Video_Upload)) {
                self::$instance = new Directorist_Custom_Field_Video_Upload;
                self::$instance->init();
            }
            return self::$instance;
        }

        /**
         * INIT
         */
        public function init()
        {
            add_filter('atbdp_form_custom_widgets', array($this, 'atbdp_form_custom_widgets'));
            add_filter('atbdp_single_listing_content_widgets', array($this, 'atbdp_single_listing_content_widgets'));
            add_filter('directorist_field_template', array($this, 'directorist_field_template'), 10, 2);
            add_filter('directorist_single_item_template', array($this, 'directorist_single_item_template'), 10, 2);
            add_action('wp_head', array($this, 'custom_css_code'));
        }

        /**
         * Plugin Directory Url
         */
        public function plugin_dir_url()
        {
            return plugin_dir_url(__FILE__);
        }

        /**
         * Base Directory
         */
        public function base_dir()
        {
            return plugin_dir_path(__FILE__);
        }

        /**
         * Template Exists
         */
        public function template_exists($template_file)
        {
            $file = $this->base_dir() . '/templates/' . $template_file . '.php';

            if (file_exists($file)) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Get Template
         */
        public function get_template($template_file, $args = array())
        {
            if (is_array($args)) {
                extract($args);
            }

            if ($this->template_exists($template_file)) {
                $data = $args;
                if (isset($args['form'])) $listing_form = $args['form'];

                $file = $this->base_dir() . '/templates/' . $template_file . '.php';

                include $file;
            }
        }

        /**
         * atbdp_form_custom_widgets
         */
        public function atbdp_form_custom_widgets($widgets)
        {
            $widgets['video-upload'] = [
                'label' => __('Video Upload', 'directorist'),
                'icon' => 'uil uil-file-upload-alt',
                'options' => [
                    'type' => [
                        'type'  => 'hidden',
                        'value' => 'file',
                    ],
                    'label' => [
                        'type'  => 'text',
                        'label' => __('Label', 'directorist'),
                        'value' => 'Video Upload',
                    ],
                    'field_key' => apply_filters('directorist_custom_field_meta_key_field_args', [
                        'type'  => 'hidden',
                        'label' => __('Key', 'directorist'),
                        'value' => 'custom-video-file',
                        'rules' => [
                            'unique' => true,
                            'required' => true,
                        ]
                    ]),
                    'file_type' => [
                        'type'        => 'hidden',
                        'value'       => 'video',
                    ],
                    'file_size' => [
                        'type'  => 'text',
                        'label' => __('File Size', 'directorist'),
                        'description' => __('Set maximum file size to upload', 'directorist'),
                        'value' => '2mb',
                    ],
                    'description' => [
                        'type'  => 'text',
                        'label' => __('Description', 'directorist'),
                        'value' => '',
                    ],
                    'required' => [
                        'type'  => 'toggle',
                        'label'  => __('Required', 'directorist'),
                        'value' => false,
                    ],
                    'only_for_admin' => [
                        'type'  => 'toggle',
                        'label'  => __('Only For Admin Use', 'directorist'),
                        'value' => false,
                    ],
                ]

            ];
            return $widgets;
        }

        /**
         * atbdp_single_listing_content_widgets
         */
        public function atbdp_single_listing_content_widgets($widgets)
        {
            $widgets['video-upload'] = [
                'options' => [
                    'icon' => [
                        'type'  => 'icon',
                        'label' => 'Icon',
                        'value' => 'las la-code',
                    ],
                ]
            ];
            return $widgets;
        }

        /**
         * directorist_field_template
         */
        public function directorist_field_template($template, $field_data)
        {
            if ('video-upload' === $field_data['widget_name']) {
                $this->get_template('listing-form/video-upload', $field_data);
            }
            return $template;
        }


        /**
         * directorist_single_item_template
         */
        public function directorist_single_item_template($template, $field_data)
        {
            if ('video-upload' === $field_data['widget_name']) {
                $this->get_template('single/video-upload', $field_data);
            }
            return $template;
        }

        /**
         * Custom CSS Code
         */
        public function custom_css_code()
        {
?>
            <style>
                .directorist-details-info-wrap .directorist-single-info {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .directorist-details-info-wrap .directorist-single-info .directorist-single-info__label {
                    margin-bottom: 10px
                }
            </style>
<?php
        }
    }

    if (!function_exists('directorist_is_plugin_active')) {
        function directorist_is_plugin_active($plugin)
        {
            return in_array($plugin, (array) get_option('active_plugins', array()), true) || directorist_is_plugin_active_for_network($plugin);
        }
    }

    if (!function_exists('directorist_is_plugin_active_for_network')) {
        function directorist_is_plugin_active_for_network($plugin)
        {
            if (!is_multisite()) {
                return false;
            }

            $plugins = get_site_option('active_sitewide_plugins');
            if (isset($plugins[$plugin])) {
                return true;
            }

            return false;
        }
    }

    function Directorist_Custom_Field_Video_Upload()
    {
        return Directorist_Custom_Field_Video_Upload::instance();
    }

    if (directorist_is_plugin_active('directorist/directorist-base.php')) {
        Directorist_Custom_Field_Video_Upload(); // get the plugin running
    }
}
