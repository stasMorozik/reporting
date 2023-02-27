<?php
use yii\helpers\Html;
?>
<div class="my-5 row">
  <div class="col-12">
    <?php if(isset($result['message']) && $result['success'] == false): ?>
      <div class="alert alert-warning" role="alert">
        <?= Html::encode($result['message']) ?>
      </div>
    <?php endif; ?>
    <?php if($result['success'] == true): ?>
      <h2>Reports</h2>
      <table class="table table-responsive table-striped">
        <thead>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">User email</th>
            <th scope="col">User name</th>
            <th scope="col">User surname</th>
            <th scope="col">Created</th>
        </thead>
        <tbody>
          <?php foreach($result['rows'] as $key => $value): ?>
            <tr>
              <th scope="row"><?= $key+1 ?></th>
              <td><?= Html::encode($value->getTitle()) ?></td>
              <td><?= Html::encode($value->getDescription()) ?></td>
              <td><?= Html::encode($value->getOwner()->getEmail()->getValue()) ?></td>
              <td><?= Html::encode($value->getOwner()->getName()->getValue()) ?></td>
              <td><?= Html::encode($value->getOwner()->getSurname()->getValue()) ?></td>
              <td><?= Html::encode($value->getCreated()->format('Y-m-d h:m')) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
