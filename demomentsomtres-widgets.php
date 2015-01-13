<?php

class OtherAccommodationsWidget extends WP_Widget {

    function OtherAccommodationsWidget() {
        parent::WP_Widget(false, $name = __('Accommodation: other', DMST_ACCOMMODATION_TEXT_DOMAIN));
    }

    function form($instance) {
        $title = esc_attr($instance['title']);
        $mode = isset($instance['mode']) ? $instance['mode'] : 'image';
        $show_caption = isset($instance['show_caption']) ? $instance['show_caption'] : '1';
        $top_caption = isset($instance['top_caption']) ? $instance['top_caption'] : '0';
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <p>
            <label for="<?php echo $this->get_field_id('mode'); ?>"><?php _e('Mode', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></label> 
            <input id="<?php echo $this->get_field_id('mode'); ?>" name="<?php echo $this->get_field_name('mode'); ?>" type="radio" value="text" <?php checked("text" == $mode); ?>><?php _e('Text', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></radio>
        <input id="<?php echo $this->get_field_id('mode'); ?>" name="<?php echo $this->get_field_name('mode'); ?>" type="radio" value="image" <?php checked("image" == $mode); ?>><?php _e('Image', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></radio>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_caption'); ?>"><?php _e('Show caption', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></label> 
            <input id="<?php echo $this->get_field_id('show_caption'); ?>" name="<?php echo $this->get_field_name('show_caption'); ?>" type="radio" value="1" <?php checked("1" == $show_caption); ?>><?php _e('Yes', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></radio>
        <input id="<?php echo $this->get_field_id('show_caption'); ?>" name="<?php echo $this->get_field_name('show_caption'); ?>" type="radio" value="0" <?php checked("0" == $show_caption); ?>><?php _e('No', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></radio>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('top_caption'); ?>"><?php _e('Show caption on top', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></label> 
            <input id="<?php echo $this->get_field_id('top_caption'); ?>" name="<?php echo $this->get_field_name('top_caption'); ?>" type="radio" value="1" <?php checked("1" == $top_caption); ?>><?php _e('Yes', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></radio>
        <input id="<?php echo $this->get_field_id('top_caption'); ?>" name="<?php echo $this->get_field_name('top_caption'); ?>" type="radio" value="0" <?php checked("0" == $top_caption); ?>><?php _e('No', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></radio>
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $new_instance['title'] = strip_tags($new_instance['title']);
        $new_instance['mode'] = isset($new_instance['mode']) ? $new_instance['mode'] : 'image';
        return $new_instance;
    }

    function widget($args, $instance) {
        extract($args);
        global $post;
        $taxs = wp_get_post_terms($post->ID, 'accommodation-type');
        echo $before_widget;
        $title = apply_filters('widget_title', $instance['title']);
        if ($title)
            echo $before_title . $title . $after_title;
        if (is_array($taxs)):
            if (isset($taxs[0])):
                $tax = $taxs[0]->term_id;
                $queryArgs = array(
                    'post_type' => 'accommodation-item',
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'accommodation-type',
                            'field' => 'id',
                            'terms' => $tax,
                        ),
                    ),
                );
                $newQuery = new WP_Query();
                $newQuery->query($queryArgs);
                $items = $newQuery->posts;
                foreach ($items as $item):
                    if ($item->ID != $post->ID):
                        if ("0" != $instance['show_caption']):
                            $caption = "<span class='caption'>$item->post_title</span>";
                            if ("1" == $instance['top_caption']):
                                $top = $caption;
                                $bottom = '';
                            else:
                                $top = '';
                                $bottom = $caption;
                            endif;
                        endif;
                        if (get_option('permalink_structure')):
                            echo '<a href="' . get_permalink($item) . '" title="' . $item->post_title . '">';
                        else:
                            echo '<a href="' . $item->guid . '" title="' . $item->post_title . '">';
                        endif;
                        echo $top;
                        if ("text" == $instance['mode']):
                            echo "<span class='demomentsomtres-accommodation-item'>$item->post_title</span>";
                        else:
                            echo get_the_post_thumbnail($item->ID, 'medium');
                        endif;
                        echo $bottom;
                        echo '</a>';
                    endif;
                endforeach;
            endif;
        endif;
        echo $after_widget;
    }

}

class AccommodationServicesWidget extends WP_Widget {

    function AccommodationServicesWidget() {
        parent::WP_Widget(false, $name = __('Accommodation Services', DMST_ACCOMMODATION_TEXT_DOMAIN));
    }

    function form($instance) {
        $title = esc_attr($instance['title']);
        $mode = isset($instance['mode']) ? $instance['mode'] : 'image';
        $items_per_row = (int) esc_attr($instance['items_per_row']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('items_per_row'); ?>"><?php _e('Items in each row:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('items_per_row'); ?>" name="<?php echo $this->get_field_name('items_per_row'); ?>" type="text" value="<?php echo $items_per_row; ?>" /></label></p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $new_instance['title'] = strip_tags($new_instance['title']);
        $new_instance['items_per_row'] = (int) esc_attr($new_instance['items_per_row']);
        return $new_instance;
    }

    function widget($args, $instance) {
        extract($args);
        global $post;
        $services = demomentsomtres_accommodation_get_option('services', array());
        $accommodation_services = DeMomentSomTresAccommodation::getAccommodationServices($post);
        echo $before_widget;
        $title = apply_filters('widget_title', $instance['title']);
        $items_per_row = (int) $instance['items_per_row'];
        if ($items_per_row == 0)
            $items_per_row = 1000;
        if ($title)
            echo $before_title . $title . $after_title;
        $i = 1;
        echo "<ul>";
        foreach ($services as $s):
            $service = $accommodation_services[$s['id']];
            if ($service['show']):
                if (($i) % $items_per_row == 1):
                    $clear = ' firstofrow';
                else:
                    $clear = '';
                endif;
                echo "<li class='$s[classes]$clear'>";
                echo "<div class='feature'>" . $s['title'] . "</div>";
                if ($s['hint']):
                    echo "<div class='info'>" . $service['info'] . "</div>";
                endif;
                echo "</li>";
                $i++;
            endif;
        endforeach;
        echo "</ul>";
        echo $after_widget;
    }

}
?>