<?php
/*
 * Settings and administration
 * @since 1.0
 */

add_action('admin_menu', 'demomentsomtres_accommodation_add_page');
add_action('admin_init', 'demomentsomtres_accommodation_admin_init');

/**
 * @since 1.0
 */
function demomentsomtres_accommodation_add_page() {
    add_options_page(__('DMS3-Accommodation', DMST_PORTFOLIO_TEXT_DOMAIN), __('Accommodation', DMST_PORTFOLIO_TEXT_DOMAIN), 'manage_options', DMST_ACCOMMODATION_OPTIONS, 'demomentsomtres_accommodation_option_page');
}

/**
 * @since 1.0
 */
function demomentsomtres_accommodation_option_page() {
    ?>
    <div class="wrap" style="float:left;width:70%;">
        <?php screen_icon(); ?>
        <h2><?php _e('DeMomentSomTres - Accommodation Manager', DMST_ACCOMMODATION_TEXT_DOMAIN); ?></h2>
        <form action="options.php" method="post">
            <?php settings_fields('dmst_accommodation_options'); ?>
            <?php do_settings_sections('dmst_accommodation'); ?>
            <input name="Submit" class="button button-primary" type="submit" value="<?php _e('Save Changes', DMST_ACCOMMODATION_TEXT_DOMAIN); ?>"/>
        </form>
    </div>
    <?php
    echo '<div style="background-color:#eee; width:25%;float:right;padding:10px;">';
    echo '<h3>' . __('Options', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</h3>' . '<pre style="font-size:0.8em;">';
    print_r(get_option(DMST_ACCOMMODATION_OPTIONS));
    echo '</pre>';
    echo '</div>';
}

/**
 * @since 1.0
 */
function demomentsomtres_accommodation_admin_init() {
    register_setting('dmst_accommodation_options', DMST_ACCOMMODATION_OPTIONS, 'demomentsomtres_accommodation_validate_options');
    add_settings_section('dmst_accommodation_services', __('Services', DMST_ACCOMMODATION_TEXT_DOMAIN), 'demomentsomtres_accommodation_section_services', 'dmst_accommodation');
//    demomentsomtres_accommodation_services();
}

/**
 * @since 1.0
 */
function demomentsomtres_accommodation_validate_options($input) {
    $ordre = array();
    $services = array();
    foreach ($input['services'] as $k => $registre):
        if ('' != $registre['id'] || '' != $registre['title'] || '' != $registre['hint'] || '' != $registre['classes']):
            $services[] = $registre;
            $ordre[] = $registre['pos'];
        endif;
    endforeach;
    array_multisort($ordre, SORT_ASC, $services);
    $i = 1;
    foreach ($services as $k => $s):
        $services[$k]['pos'] = $i;
        $i++;
    endforeach;
    $result = $input;
    $result['services'] = $services;
    return $result;
}

/**
 * @since 1.0
 */
function demomentsomtres_accommodation_section_services() {
    echo '<p>' . __("Manage the service information.<br/>If you need to add more fields, just save and more fields will appear.<br/>In order to delete services, mark the delete checkbox.", DMST_ACCOMMODATION_TEXT_DOMAIN) . '</p>';

    $services = dmst_admin_helper_get_option(DMST_ACCOMMODATION_OPTIONS, 'services', array());
//    echo '<pre>' . print_r($services, true) . '</pre>';

    $i = 1;
    foreach ($services as $service) {
        $service['pos'] = $i;
        $i++;
    }
//    echo '<pre>' . print_r($services, true) . '</pre>';
    // additional ids
    for ($i = 0; $i < 5; $i++):
        $services[] = array(
            'id' => '',
            'title' => '',
            'classes' => '',
            'hint' => '',
            'pos' => count($services) + 1,
        );
    endfor;
    echo '<table>';
    echo '<tr valign="top">';
    echo '<th>' . __('Position', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
    echo '<th>' . __('ID', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
    echo '<th>' . __('Title', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
    echo '<th>' . __('Classes', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
    echo '<th>' . __('Hint', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
    echo '<th>' . __('Delete', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</th>';
    echo '</tr>';
    foreach ($services as $i => $service):
        echo '<tr valign="top">';
        echo '<td>';
        dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][$service[pos]][pos]", $service['pos']);
        echo '</td>';
        echo '<td>';
        dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][$service[pos]][id]", $service['id']);
        echo '</td>';
        echo '<td>';
        dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][$service[pos]][title]", $service['title']);
        echo '</td>';
        echo '<td>';
        dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][$service[pos]][classes]", $service['classes']);
        echo '</td>';
        echo '<td>';
        dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][$service[pos]][hint]", $service['hint'], "checkbox", "on");
        echo '</td>';
        echo '<td>';
        dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services]$service[pos]][delete]", null, "checkbox", false);
        echo '</td>';
        echo '</tr>';
    endforeach;
    echo '<tr valign="top">';
    echo '<td style="font-size:80%;">' . __('Change values to define sort order', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</td>';
    echo '<td style="font-size:80%;">' . __('Set an ID for the feature. Keep consistent through sites.', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</td>';
    echo '<td style="font-size:80%;">' . __('The service name', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</td>';
    echo '<td style="font-size:80%;">' . __('Classes to apply to service presentation in widget. Use them to apply images and custom css.', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</td>';
    echo '<td style="font-size:80%;">' . __('If selected, a complementary info field will be shown to provide additional data.', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</td>';
    echo '<td style="font-size:80%;">' . __('Selected lines will be deleted on save.', DMST_ACCOMMODATION_TEXT_DOMAIN) . '</td>';
    echo '</tr>';
    echo '</table>';
}

/**
 * @since 1.0
 * @param array $service
 */
function demomentsomtres_accommodation_service_id_field($service) {
    dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][id$service[id]]", $service['id'], $html_before = '<td style="background-color:#f80;">', $html_after = '</td>');
}

/**
 * @since 1.0
 * @param array $service
 */
function demomentsomtres_accommodation_service_title_field($service) {
    dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][id$service[id]]", $service['title']);
}

/**
 * @since 1.0
 * @param array $service
 */
function demomentsomtres_accommodation_service_classes_field($service) {
    dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][id$service[id]]", $service['classes']);
}

/**
 * @since 1.0
 * @param array $service
 */
function demomentsomtres_accommodation_service_pos_field($service) {
    dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][id$service[id]]", $service['pos']);
}

/**
 * @since 1.0
 * @param array $service
 */
function demomentsomtres_accommodation_service_hint_field($service) {
    dmst_admin_helper_input(null, DMST_ACCOMMODATION_OPTIONS . "[services][id$service[id]]", $service['hint']);
}
