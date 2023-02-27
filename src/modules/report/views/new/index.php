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
    <?php if(isset($result['message']) && $result['success'] == true): ?>
      <div class="alert alert-success" role="alert">
        <?= Html::encode($result['message']) ?>
      </div>
    <?php endif; ?>
    <?= Html::beginForm(['/reports/'], 'post', ['id' => 'createReportForm', 'name' => 'createReportForm']) ?>
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input autocomplete="off" type="text" class="form-control" name="title" id="title" aria-describedby="titleHelp">
        <div id="titleHelp" class="form-text">Title of report.</div>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea autocomplete="off" type="text" class="form-control" name="description" id="description" aria-describedby="descriptionHelp">
        </textarea>
        <div id="descriptionHelp" class="form-text">Description of report.</div>
      </div>
      <a href="/reports/new" id="cancelForm" class="btn btn-danger">Cancel</a>
      <button type="submit" form="createReportForm" class="btn btn-success">Ok</button>
    <?= Html::endForm() ?>
  </div>
</div>
