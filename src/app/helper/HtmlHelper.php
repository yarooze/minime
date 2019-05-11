<?php
/**
 * Helpers to work with html elements
 */

if(!function_exists('make_select_options')) {
    /**
     * Renders options for the select element
     *
     * @param array $data - [[$fieldId => '', $fieldValue => ''],..]
     * @param string $fieldId
     * @param string $fieldValue
     */
    function make_select_options($data, $fieldId, $fieldValue, $value = null)
    {
        echo '<option value=""';
        echo ($value === null) ? 'selected="selected"' : '';
        echo '></option>';
        foreach ($data as $row) {
            echo '<option value="' . $row[$fieldId] . '" ';
            if (is_array($value)) {
                echo (in_array($row[$fieldId], $value)) ? 'selected="selected"' : '';
            } else {
                echo ($value == $row[$fieldId]) ? 'selected="selected"' : '';
            }
            
            echo ' >' . $row[$fieldValue] . '</option>';
        }
    }
}