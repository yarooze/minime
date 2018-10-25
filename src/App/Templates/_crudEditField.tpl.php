<?php
require_once __DIR__.'/../Helper/HtmlHelper.php';
$attr = array(
    'type' => 'text',
    'class' => 'form-control',
);
if (isset($fieldData['attr'])) {
    foreach ($fieldData['attr'] as $key => $val) {
        $attr[$key] = $val;
    }
}

$title = (isset($fieldData['title'])) ? $i18n->trans($fieldData['title']) : $fieldName;

if (isset($fieldData['type']) && $fieldData['type'] === 'select'): ?>
    <div class="form-group row">
        <label for="userId" class="col-sm-2 col-form-label"><?php $view->printString($title); ?></label>
        <div class="col-sm-10">
            <select class="custom-select mr-sm-2 <?php echo (isset($errs[$fieldName])) ? 'is-invalid' : ''; ?>"
                    name="<?php echo $form->getFullFieldName($fieldName); ?>" aria-describedby="<?php $view->printString($fieldName); ?>Help"
                <?php
                foreach ($attr as $key => $val) {
                    echo ' ' . $key . '="'.  $val . '" ';
                } ?>>
                <?php
                $selectIdField = $fieldData['selection']['idField'];
                $selectValueField = $fieldData['selection']['valueField'];
                $selectValues = $fieldData['selection']['values']; // array($selectIdField => $selectValueField)
                make_select_options($selectValues, $selectIdField, $selectValueField, $form->getValue($fieldName));
                ?></select>
            <small id="<?php $view->printString($fieldName); ?>Help" class="form-text text-muted"><?php
                if (isset($fieldData['help'])) {
                    echo $i18n->trans($fieldData['help']);
                }
                ?></small>
        </div>
    </div><?php
elseif (isset($fieldData['type']) && in_array($fieldData['type'], array('checkbox', 'radio'), true)):
    ?><div class="form-group row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <div class="form-check">
                <label class="form-check-label" for="<?php $view->printString($fieldName); ?>">
                <input class="form-check-input" type="<?php echo $fieldData['type']; ?>" id="<?php echo $form->getName(); ?>_<?php $view->printString($fieldName); ?>"
                       name="<?php echo $form->getFullFieldName($fieldName); ?>" aria-describedby="<?php $view->printString($fieldName); ?>Help"
                    <?php
                    foreach ($attr as $key => $val) {
                        echo ' ' . $key . '="'.  $val . '" ';
                    } ?>
                    <?php echo (in_array($form->getValue($fieldName), array(1, '1', true, 'on'), true)) ? ' checked="checked"' : ''; ?> >
                    <?php $view->printString($title); ?>
                </label>
            </div>
            <small id="<?php $view->printString($fieldName); ?>Help" class="form-text text-muted"><?php
                if (isset($fieldData['help'])) {
                    echo $i18n->trans($fieldData['help']);
                }
                ?></small>
        </div>
    </div><?php
else:
    ?><div class="form-group row">
        <label for="<?php $view->printString($fieldName); ?>" class="col-sm-2 col-form-label"><?php $view->printString($title); ?></label>
        <div class="col-sm-10">
            <input name="<?php echo $form->getFullFieldName($fieldName); ?>"
                   value="<?php $view->printString($form->getValue($fieldName)); ?>" <?php
            foreach ($attr as $key => $val) {
                echo ' ' . $key . '="'.  $val . '" ';
            } ?> aria-describedby="<?php $view->printString($fieldName); ?>Help">
            <small id="<?php $view->printString($fieldName); ?>Help" class="form-text text-muted"><?php
                if (isset($fieldData['help'])) {
                    echo $i18n->trans($fieldData['help']);
                }
                ?></small>
        </div>
    </div><?php
endif;
