<div class="form-group row">
    <label for="<?php $view->printString($fieldName); ?>" class="col-sm-2 col-form-label"><?php $view->printString($fieldName); ?></label>
    <div class="col-sm-10"><?php
        $attr = array(
            'type' => 'text',
            'class' => 'form-control',
        );
        if (isset($fieldData['attr'])) {
            foreach ($fieldData['attr'] as $key => $val) {
                $attr[$key] = $val;
            }
        }
        ?>
        <input name="<?php echo $form->getFullFieldName($fieldName); ?>"
               value="<?php $view->printString($form->getValue($fieldName)); ?>" <?php
            foreach ($attr as $key => $val) {
                echo ' ' . $key . '="'.  $val . '" ';
            } ?> >
    </div>
</div>