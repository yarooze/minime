<td data-column="<?php $view->printString($fieldName); ?>"><?php
    $value = $entity[$fieldName];
    if (isset($fieldData['selection']) && isset($fieldData['selection']['values'])) {
        $idField = $fieldData['selection']['idField'];
        $valueField = $fieldData['selection']['valueField'];
        foreach ($fieldData['selection']['values'] as $values) {
            if ($values[$idField] === $value) {
                $value = $values[$valueField];
                break;
            }
        }
    }
    $view->printString($value);
?></td>