<?php

/**
 * Class to manage accomodation information
 * @since 1.0
 * @author marcqueralt
 */
if (!class_exists('DeMomentSomTresAccommodation')) {

    class DeMomentSomTresAccommodation {

        function DeMomentSomTresAccommodation() {
            add_action('plugins_loaded', array(&$this, 'plugin_init'));
            add_action('init', array(&$this, 'custom_types'));

            add_action('widgets_init', create_function('', 'return register_widget("OtherAccommodationsWidget");'));
            add_action('widgets_init', create_function('', 'return register_widget("AccommodationServicesWidget");'));
            add_action('add_meta_boxes', array(&$this, 'add_metaboxes'));
            add_action('save_post', array(&$this, 'saveMetadata'));
        }

        /**
         * @since 1.0
         */
        function plugin_init() {
            load_plugin_textdomain(DMST_ACCOMMODATION_TEXT_DOMAIN, false, DMST_ACCOMMODATIO_LANG_DIR);
            //$this->initTables();
        }

        /**
         * @since 1.0
         */
        function custom_types() {
            register_post_type('accommodation-item', array(
                'labels' => array(
                    'name' => demomentsomtres_accommodation_get_label_items(),
                    'singular_name' => demomentsomtres_accommodation_get_label_item(),
                    'add_new' => __('Add', DMST_ACCOMMODATION_TEXT_DOMAIN) . ' ' . demomentsomtres_accommodation_get_label_item(),
                    'add_new_item' => __('Add New', DMST_ACCOMMODATION_TEXT_DOMAIN) . ' ' . demomentsomtres_accommodation_get_label_item(),
                    'edit' => __('Edit', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'edit_item' => __('Edit', DMST_ACCOMMODATION_TEXT_DOMAIN) . ' ' . demomentsomtres_accommodation_get_label_item(),
                    'new_item' => __('New', DMST_ACCOMMODATION_TEXT_DOMAIN) . ' ' . demomentsomtres_accommodation_get_label_item(),
                    'view' => __('View', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'view_item' => __('View', DMST_ACCOMMODATION_TEXT_DOMAIN) . ' ' . demomentsomtres_accommodation_get_label_item(),
                    'search_items' => __('Search', DMST_ACCOMMODATION_TEXT_DOMAIN) . ' ' . demomentsomtres_accommodation_get_label_item(),
                    'not_found' => __('No item found', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'not_found_in_trash' => __('No item found in Trash', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'parent' => __('Parent item', DMST_ACCOMMODATION_TEXT_DOMAIN)
                ),
                'public' => true,
                'show_in_nav_menus' => true,
                'menu_position' => 15,
                'taxonomies' => array(),
                'rewrite' => array('slug' => demomentsomtres_accommodation_get_slug_item()),
                'query_var' => true,
                'has_archive' => true,
                'supports' => array(
                    'title',
                    'editor',
                    'excerpt',
                    'trackbacks',
                    'custom-fields',
                    'comments',
                    'revisions',
                    'thumbnail',
                    'author',
                    'page-attributes'
                )
                    )
            );
            register_taxonomy('accommodation-type', 'accommodation-item', array(
                'hierarchical' => false,
                'labels' => array(
                    'name' => _x('Accommodation Types', 'taxonomy general name', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'singular_name' => _x('Accommodation Type', 'taxonomy singular name', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'search_items' => __('Search Accommodation Types', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'popular_items' => __('Popular Accommodation Types', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'all_items' => __('All Accommodation Types', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'parent_item' => null,
                    'parent_item_colon' => null,
                    'edit_item' => __('Edit Accommodation Type', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'update_item' => __('Update Accommodation Type', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'add_new_item' => __('Add New Accommodation Type', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'new_item_name' => __('New Accommodation Type Name', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'separate_items_with_commas' => __('Separate accommodation types with commas', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'add_or_remove_items' => __('Add or remove accommodation types', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'choose_from_most_used' => __('Choose from the most used accommodation types', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'not_found' => __('No accommodation types found.', DMST_ACCOMMODATION_TEXT_DOMAIN),
                    'menu_name' => __('Accommodation Types', DMST_ACCOMMODATION_TEXT_DOMAIN),
                ),
                'show_ui' => true,
                'show_admin_column' => true,
                //'update_count_callback' => '_update_post_term_count',
                'query_var' => true,
                'rewrite' => array('slug' => demomentsomtres_accommodation_get_slug_type()),
            ));
            /*            register_taxonomy('accommodation-service', 'accommodation-item', array(
              'hierarchical' => false,
              'labels' => array(
              'name' => _x('Accommodation Services', 'taxonomy general name', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'singular_name' => _x('Accommodation Service', 'taxonomy singular name', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'search_items' => __('Search Accommodation Services', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'popular_items' => __('Popular Accommodation Services', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'all_items' => __('All Accommodation Services', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'parent_item' => null,
              'parent_item_colon' => null,
              'edit_item' => __('Edit Accommodation Service', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'update_item' => __('Update Accommodation Service', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'add_new_item' => __('Add New Accommodation Service', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'new_item_name' => __('New Accommodation Type Service', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'separate_items_with_commas' => __('Separate accommodation services with commas', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'add_or_remove_items' => __('Add or remove accommodation services', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'choose_from_most_used' => __('Choose from the most used accommodation services', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'not_found' => __('No accommodation services found.', DMST_ACCOMMODATION_TEXT_DOMAIN),
              'menu_name' => __('Accommodation Services', DMST_ACCOMMODATION_TEXT_DOMAIN),
              ),
              'show_ui' => true,
              'show_admin_column' => true,
              //'update_count_callback' => '_update_post_term_count',
              'query_var' => true,
              'rewrite' => array('slug' => demomentsomtres_accommodation_get_slug_service()),
              )); */
        }

        /**
         * Setup DataBase if saved version is not the current one
         * @global type $wpdb
         * @deprecated since version 1.0
         */
        function initTables() {
            global $wpdb;

            $currentDBver = $this->getDataBaseVersion();
            if ($currentDBver != DMST_ACCOMMODATION_DBVER):
                $table_name_calendar = $wpdb->prefix . 'dms3_accom_calendar';
                //if ('' == $currentDBver):
                $sql = "CREATE TABLE $table_name_calendar (
                            id bigint(20) NOT NULL AUTO_INCREMENT,
                            site_id bigint(20) NOT NULL,
                            place_id bigint(20) NOT NULL,
                            checkin datetime NOT NULL,
                            checkout datetime NOT NULL,
                            people int(11) NOT NULL DEFAULT 1,
                            children int(11) NOT NULL DEFAULT 0,
                            status varchar(20) NOT NULL,
                            public_text text,
                            private_text text,
                            createdate timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
                            UNIQUE KEY id (id)
                        );";
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                dbDelta($sql);
                $this->updateDataBaseVersion(DMST_ACCOMMODATION_DBVER);
            //endif;
            endif;
        }

        /**
         * * @deprecated since version 1.0
         * @return string
         */
        function getDataBaseVersion() {
            return demomentsomtres_accommodation_get_option('dms3-accommodation-database', '');
        }

        /**
         * @deprecated since version 1.0
         * @param string $newVersion
         */
        function updateDataBaseVersion($newVersion) {
            update_option('dms3-accommodation-database', $newVersion);
        }

        /**
         * @since 1.0
         */
        function add_metaboxes() {
            add_meta_box('dms3-accommodation-services', __('Services and features information', DMST_ACCOMMODATION_TEXT_DOMAIN), array(&$this, 'service_metabox'), 'accommodation-item', 'normal', 'high');
        }

        function service_metabox($post) {
            $services = demomentsomtres_accommodation_get_option('services', array());
            $accommodation_services = $this->getAccommodationServices($post);
            $grey = true;
//            echo '<pre>' . print_r($accommodation_services, true) . '</pre>';
            echo '<p>' . __("Here you manage the accommodation services information.", DMST_ACCOMMODATION_TEXT_DOMAIN) . '</p>';
            echo '<table width="100%" class="demomentsomtres-accommodation-services">';
            echo '<thead><tr>';
            echo '<th>' . __('Code', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
            echo '<th>' . __('Service', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
            echo '<th>' . __('Show?', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
            echo '<th>' . __('Additional information', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            foreach ($services as $s):
                if (array_key_exists($s['id'], $accommodation_services)):
                    $temp = $accommodation_services[$s['id']];
                    $ck = $temp['show'];
                    $value = $temp['info'];
                else:
                    $ck = false;
                    $value = '';
                endif;
                if ($grey):
                    echo '<tr style="background-color:#ccc;">';
                    $grey = false;
                else:
                    echo '<tr>';
                    $grey = true;
                endif;
                echo '<td style="text-align:center;">';
                echo $s['id'];
                echo '</td>';
                echo '<td>';
                echo $s['title'];
                echo '</td>';
                echo '<td style="text-align:center;">';
                echo '<input name="dms3-accommodation-services[' . $s['id'] . '][show]" type="checkbox" ' . checked($ck, 'on', false) . '/>';
                echo '</td>';
                echo '<td>';
                if ($s['hint']):
                    echo '<input style="margin-left:5%;width:90%;" name="dms3-accommodation-services[' . $s['id'] . '][info]" type="text" value="' . $value . '" />';
                endif;
                echo '</td>';
                echo '</tr>';
            endforeach;
            echo '</tbody></table>';
        }

        /**
         * Gets the services stored in metadata
         * @param mixed $post the post object
         * @return array this accommodation services
         */
        function getAccommodationServices($post) {
            $result = get_post_meta($post->ID, 'dms3-accommodation-services', true);
            if (!$result):
                $result = array();
            else:
//                $result = json_decode($result);
            endif;
            return $result;
        }

        /**
         * @since 1.0
         * @param int $post_id
         * @return mixed 
         */
        function saveMetadata($post_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return $post_id;
            $services = $_POST['dms3-accommodation-services'];
//            echo '<pre>' . print_r($services, true) . '</pre>';
//            exit;
//            update_post_meta($post_id, 'dms3-accommodation-services', json_encode($services));
            update_post_meta($post_id, 'dms3-accommodation-services', $services);
        }

    }

}