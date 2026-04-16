<?php $alertMargin ??= 'mb-md'; ?>
<?php if (!empty($success)): ?>
  <div class="alert alert-success <?= $alertMargin ?>" role="status"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <div class="alert alert-error <?= $alertMargin ?>" role="alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
