<?php
use yii\helpers\Html;
?>

<div class="h-100 d-flex justify-content-center flex-column">
  <form class="d-flex justify-content-center">
    <div class="mb-3">
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
      <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
    </div>
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control" id="name" aria-describedby="nameHelp">
      <div id="nameHelp" class="form-text">Your firstname.</div>
    </div>
  </form>
</div>
