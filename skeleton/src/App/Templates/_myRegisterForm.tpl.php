<form role="form" method="post" id="<?php echo $form->getName(); ?>" name="<?php echo $form->getName(); ?>"
      action="<?php echo $app->router->getUrl('myRegisterSubmit'); ?>">
  <div class="row notice">
    <h1>Register for FREE!</h1>
    <hr class="style-one" />
    <div class="col-md-1"></div>
    <div class="col-md-10">
    <?php if($errs):?>
      <div class="form-group">
        <div class="danger-text" style="padding: 15px;">
      <?php foreach($errs as $err): ?>
        <p><i class="fa fa-exclamation-triangle"></i><strong>Error: </strong><?php $view->printString($err); ?></p>
      <?php endforeach; ?>
        </div>
      </div>
    <?php endif;?>
      <div class="form-group">
        <input type="text" class="form-control-notice" id="name" name="form_register[name]" placeholder="Login" required
        pattern="[a-zA-Z0-9\._-]{3,25}" value="<?php $view->printString($form->getValue('name')); ?>"
        <?php echo (isset($errs['name'])) ? 'style="border:#FF0000 3px solid;"' : ''; ?> />
      </div>
      <?php if(isset($errs['name'])): ?>
        <p><i class="fa fa-exclamation-triangle"></i><strong>Error: </strong><?php $view->printString($errs['name']); ?></p>
      <?php endif; ?>
      <div class="form-group">
        <input type="email" class="form-control-notice" id="mail" name="form_register[mail]" placeholder="Email" required
         value="<?php $view->printString($form->getValue('mail')); ?>" <?php echo (isset($errs['mail'])) ? 'style="border:#FF0000 3px solid;"' : ''; ?> />
      </div>
      <?php if(isset($errs['mail'])): ?>
        <p><i class="fa fa-exclamation-triangle"></i><strong>Error: </strong><?php $view->printString($errs['mail']); ?></p>
      <?php endif; ?>
      <div class="form-group">
        <input type="password" class="form-control-notice" id="pwd1" name="form_register[pwd1]" placeholder="Rassword" required
         <?php echo (isset($errs['pwd']) || isset($errs['pwd_eq'])) ? 'style="border:#FF0000 3px solid;"' : ''; ?> />
      </div>
      <?php if(isset($errs['pwd'])): ?>
        <p><i class="fa fa-exclamation-triangle"></i><strong>Error: </strong><?php $view->printString($errs['pwd']); ?></p>
      <?php endif; ?>
      <div class="form-group">
        <input type="password" class="form-control-notice" id="pwd2" name="form_register[pwd2]" placeholder="Repeat password" required
        <?php echo (isset($errs['pwd']) || isset($errs['pwd_eq'])) ? 'style="border:#FF0000 3px solid;"' : ''; ?> />
      <?php if(isset($errs['pwd_eq'])): ?>
        <p><i class="fa fa-exclamation-triangle"></i><strong>Error: </strong><?php $view->printString($errs['pwd_eq']); ?></p>
      <?php endif; ?>
      </div>
      <div class="form-group">
      </div>
      <div class="radio">
          <label>
            <input type="checkbox" name="form_register[newsletter]" id="newsletter" value="abo" checked>
            Yes, send me lot of SPAM!
          </label>
      </div>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-12"><hr class="style-one" /></div>
    <div class="col-md-2"></div>
    <div class="col-md-8"><button class="btn btn-success" type="submit">REGISTER NOW!</button></div>
    <div class="col-md-2"></div>
  </div>
</form>
<script type="text/javascript">
  window.onload = function () {
    document.getElementById("pwd1").onchange  = validatePassword;
    document.getElementById("pwd2").onchange  = validatePassword;
    document.getElementById("name").oninvalid = invalidName;
  }
  function validatePassword() {
    var pass2=document.getElementById("pwd2").value;
    var pass1=document.getElementById("pwd1").value;
    document.getElementById("pwd2").setCustomValidity('');
    document.getElementById("pwd1").setCustomValidity('');
    if (pass1.length < 6) {
      document.getElementById("pwd1").setCustomValidity("<?php $view->printString($form->getErrorMsg('pwd')); ?>");
    } else if(pass1!=pass2) {
      document.getElementById("pwd2").setCustomValidity("<?php $view->printString($form->getErrorMsg('pwd_eq')); ?>");
    }
  }
  function invalidName(e) {
    e.target.setCustomValidity("");
    if (!e.target.validity.valid) {
      e.target.setCustomValidity("<?php $view->printString($form->getErrorMsg('name')); ?>");
    }
  }
</script>
