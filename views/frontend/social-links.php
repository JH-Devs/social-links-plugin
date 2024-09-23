<!-- Section: Links -->
<section class="mb-2">
  <?php if (!empty($links)): ?>

    <!-- Výpis hlavních odkazů -->
    <?php foreach ($links as $link): ?>
      <?php if (user_can($link->permission)) :?>
        <a 
          data-mdb-ripple-init 
          class="btn btn-outline btn-floating m-1"
          href="<?= ROOT ?>/<?= htmlspecialchars($link->slug, ENT_QUOTES, 'UTF-8') ?>"
          role="button"
        >
        <img src="<?= get_image($link->image) ?>" class="rounded-circle" style="width:30px;height:30px;object-fit:cover;" />
          <?php if (!empty($link->icon)): ?>
            <i class="<?= $link->icon ?>"></i>
          <?php endif; ?>
        </a>
      <?php endif; ?>
    <?php endforeach; ?>

  <?php endif; ?>
</section>
<!-- Section: Links -->
