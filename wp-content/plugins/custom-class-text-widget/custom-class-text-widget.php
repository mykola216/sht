<?php
/*
Plugin Name: Custom Class on Text Widgets
Plugin URI: http://www.inigo.net
Description: A customized text widget to give custom classes on each widget area.
Author: Paul @ Inigo
Version: 1.1
Author URI: http://www.inigo.net
*/
 
    class customClassText extends WP_Widget {
        function customClassText() {
            parent::WP_Widget('customclasstext', $name = 'Custom Class Text Widget');
        }
 
        function widget($args, $instance) {
            extract($args);
            $title          = apply_filters('widget_title', $instance['title']);
            $customClass    = apply_filters('widget_title', $instance['customClass']);
            $text           = $instance['text'];
 
            echo '<div class="'.$customClass.'">'."\n";
            echo    $before_widget;
            echo    $before_title.$title.$after_title;
            echo    $text;
            echo    $after_widget;
            echo '</div>'."\n";
        }
 
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title']          = strip_tags($new_instance['title']);
            $instance['customClass']    = strip_tags($new_instance['customClass']);
            $instance['text']           = $new_instance['text'];
            return $instance;
        }
 
        function form($instance) {
            if($instance) {
                $title          = esc_attr($instance['title']);
                $customClass    = esc_attr($instance['customClass']);
                $text           = esc_attr($instance['text']);
            } else {
                $title          = __('', 'text_domain');
                $customClass    = __('', 'text_domain');
                $text           = __('', 'text_domain');
            }
 
            echo '<p><label for="'.$this->get_field_id('title').'">'._e('Title:').'</label>';
            echo '<input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" /></p>';
            echo '<p><label for="'.$this->get_field_id('customClass').'">'._e('Custom Class:').'</label>';
            echo '<input class="widefat" id="'.$this->get_field_id('customClass').'" name="'.$this->get_field_name('customClass').'" type="text" 
value="'.$customClass.'" /></p>';
            echo '<p><label for="'.$this->get_field_id('text').'">'._e('Text:').'</label>';
            echo '<textarea class="widefat" id="'.$this->get_field_id('text').'" name="'.$this->get_field_name('text').'" rows="20">'.$text.'</textarea></p>';
 
        }
    }
 
    add_action('widgets_init', create_function('', 'return register_widget("customClassText");'));
?>
