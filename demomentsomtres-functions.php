<?php

/**
 * @since 1.0
 * @param string $name option name
 * @param mixed $default the default value if option is not set
 * @return mixed The option value
 */
function demomentsomtres_accommodation_get_option($name, $default = null) {
    $options = get_option(DMST_ACCOMMODATION_OPTIONS);
    if (!isset($options[$name])):
        return $default;
    else:
        return $options[$name];
    endif;
}

/**
 * @since 1.0
 * @return the label for items
 */
function demomentsomtres_accommodation_get_label_items() {
    return demomentsomtres_accommodation_get_option('label_items', __('Accommodation Items', DMST_ACCOMMODATION_TEXT_DOMAIN));
}

/**
 * @since 1.0
 * @return the label for item
 */
function demomentsomtres_accommodation_get_label_item() {
    return demomentsomtres_accommodation_get_option('label_item', __('Accommodation Item', DMST_ACCOMMODATION_TEXT_DOMAIN));
}

/**
 * @since 1.0
 * @return the slug for item
 */
function demomentsomtres_accommodation_get_slug_item() {
    return demomentsomtres_accommodation_get_option('slug_item', __('accommodation', DMST_ACCOMMODATION_TEXT_DOMAIN));
}

/**
 * @since 1.0
 * @return the slug for item
 */
function demomentsomtres_accommodation_get_slug_type() {
    return demomentsomtres_accommodation_get_option('slug_type', __('acommodation-type', DMST_ACCOMMODATION_TEXT_DOMAIN));
}

/**
 * @since 1.0
 * @return the slug for item
 */
function demomentsomtres_accommodation_get_slug_service() {
    return demomentsomtres_accommodation_get_option('slug_service', __('accommodation-service', DMST_ACCOMMODATION_TEXT_DOMAIN));
}

?>
