<?php
$current_month = date('n');
$current_year = date('Y');

$next_month = date('n', strtotime('+1 month'));
$next_year = date('Y', strtotime('+1 month'));
?>

<div class="row">
  <?php
  $dates_to_show = array_filter($upcoming_dates, function ($date) use ($current_month, $current_year, $next_month, $next_year) {
    $d = strtotime($date->market_date);
    $month = date('n', $d);
    $year = date('Y', $d);
    return ($month == $current_month && $year == $current_year) || ($month == $next_month && $year == $next_year);
  });

  // Group by month/year
  $grouped = [];
  foreach ($dates_to_show as $date) {
    $key = date('F Y', strtotime($date->market_date));
    $grouped[$key][] = $date;
  }
  ?>

  <?php foreach ($grouped as $month_label => $dates): ?>
    <div class="col-12 mb-3">
      <h5 class="mt-4"><?php echo h($month_label); ?></h5>
      <div class="row">
        <?php foreach ($dates as $date): ?>
          <?php
          $formatted_date = date('Y-m-d', strtotime($date->market_date));
          $input_id = "market_date_" . h($date->market_date_id);
          ?>
          <div class="col-md-6">
            <div class="form-check mb-2">
              <input
                class="form-check-input"
                type="checkbox"
                id="<?php echo $input_id; ?>"
                name="market_dates[]"
                value="<?php echo h($date->market_date_id); ?>"
                data-date="<?php echo $formatted_date; ?>"
                <?php if (in_array($date->market_date_id, $selected_dates)) echo 'checked'; ?>>
              <label class="form-check-label" for="<?php echo $input_id; ?>">
                <?php echo date('F j, Y', strtotime($date->market_date)); ?>
              </label>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
