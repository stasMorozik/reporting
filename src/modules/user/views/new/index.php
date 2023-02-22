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
    <?= Html::beginForm(['/users/'], 'post', ['id' => 'signUpForm', 'name' => 'signUpForm']) ?>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input autocomplete="off" type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input autocomplete="off" type="text" class="form-control" name="name" id="name" aria-describedby="nameHelp">
        <div id="nameHelp" class="form-text">Your firstname.</div>
      </div>
      <div class="mb-3">
        <label for="surname" class="form-label">Surname</label>
        <input autocomplete="off" type="text" class="form-control" name="surname" id="surname" aria-describedby="surnameHelp">
        <div id="surnameHelp" class="form-text">Your surname.</div>
      </div>
      <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select name="role" id="roleSelect" class="form-select">
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="department" class="form-label">Department</label>
        <select name="department" id="departmentSelect" class="form-select">
          <option value="accounting">Accounting</option>
          <option value="store">Store</option>
          <option value="human">Human</option>
          <option value="logistics">Logistics</option>
          <option value="It">It</option>
          <option value="purchasing">Purchasing</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input autocomplete="off" type="password" class="form-control" name="password" id="password" aria-describedby="passwordHelp">
        <div id="passwordHelp" class="form-text">Your password.</div>
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm password</label>
        <input autocomplete="off" type="password" class="form-control" name="confirm_password" id="confirmPassword" aria-describedby="confirmPasswordHelp">
        <div id="confirmPasswordHelp" class="form-text">Confirm your password.</div>
      </div>
      <a href="/users/new" id="cancelForm" class="btn btn-danger">Cancel</a>
      <button type="submit" form="signUpForm" class="btn btn-success">Ok</button>
    <?= Html::endForm() ?>
  </div>
</div>
