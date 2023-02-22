<?php
use yii\helpers\Html;
?>
<div class="my-5 d-flex justify-content-center">
  <div class="col-xxl-4 col-xl-4 col-lg-4 col-12">
    <?php if(isset($result['message'])) : ?>
      <div class="alert alert-warning" role="alert">
        <?= Html::encode($result['message']) ?>
      </div>
    <?php endif; ?>
    <?= Html::beginForm(['/users/session'], 'post', ['id' => 'signInForm', 'name' => 'signInForm']) ?>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input autocomplete="off" type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input autocomplete="off" type="password" class="form-control" name="password" id="password" aria-describedby="passwordHelp">
        <div id="passwordHelp" class="form-text">Your password.</div>
      </div>
      <a href="/users/auth" id="cancelForm" class="btn btn-danger">Cancel</a>
      <button type="submit" form="signInForm" class="btn btn-success">Ok</button>
    <?= Html::endForm() ?>
    <div class="my-3 fw-light">Don't have an account yet? <a href="/users/new" class="link-primary">Register now</a></div>
  </div>
</div>
