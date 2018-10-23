<?php
/**
 * Helpers to work with html elements
 */

/**
 * Renders options for the select element
 *
 * @param array $data - [[$fieldId => '', $fieldValue => ''],..]
 * @param string $fieldId
 * @param string $fieldValue
 */
function make_select_options($data, $fieldId, $fieldValue, $value = null) {
    echo '<option value=""';
    echo ($value === null) ? 'selected="selected"' : '';
    echo '></option>';
    foreach ($data as $row) {
        echo '<option value="' . $row[$fieldId] . '" ';
        echo ($value == $row[$fieldId]) ? 'selected="selected"' : '';
        echo ' >' . $row[$fieldValue] . '</option>';
    }
}