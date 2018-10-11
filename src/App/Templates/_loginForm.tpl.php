<div class="well" style="max-width: 400px; margin: 50px auto 10px;">
<h1>You Shall Not Pass!!!</h1>
<form role="form" method="post" id="<?php echo $form->getName(); ?>" name="<?php echo $form->getName(); ?>"
      action="<?php echo $app->router->getUrl('loginSubmit'); ?>">
    <div class="form-group">
      <label for="exampleInputEmail1">Email address</label>
      <input type="email" class="form-control" id="email" name="<?php echo  $form->getFullFieldName('email'); ?>" placeholder="Enter email">
    </div>
    <div class="form-group">
      <label for="exampleInputPassword1">Password</label>
      <input type="password" class="form-control" id="pwd" name="<?php echo $form->getFullFieldName('pwd'); ?>" placeholder="Password">
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" id="keep" name="<?php echo $form->getFullFieldName('keep'); ?>"> Check me out
      </label>
    </div>
    <button type="submit" class="btn btn-default">Pass</button>
  </form>
</div>
