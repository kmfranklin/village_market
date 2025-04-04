<div class="row">
  <?php
  $half = ceil(count($upcoming_dates) / 2);
  $columns = array_chunk($upcoming_dates, $half);
  ?>

  <?php foreach ($columns as $column): ?>
    <div class="col-md-6">
      <?php foreach ($column as $date): ?>
        <div class="form-check mb-2">
          <input class="form-check-input"
            type="checkbox"
            name="market_dates[]"
            value="<?php echo h($date->market_date_id); ?>"
            id="market_date_<?php echo h($date->market_date_id); ?>"
            <?php if (in_array($date->market_date_id, $selected_dates)) echo 'checked'; ?>>
          <label class="form-check-label" for="market_date_<?php echo h($date->market_date_id); ?>">
            <?php echo date('F j, Y', strtotime($date->market_date)); ?>
          </label>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>
