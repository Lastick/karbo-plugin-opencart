<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1>
    <?php echo $heading_title; ?>
  </h1>
  <div class="content karbo_style">
    <div style="width: 100%; text-align: justify">
      <p>
        <?php echo $text_description; ?>
      </p>
    </div>
    <div style="width: 100%">
      <img class="karbo_style_qr" src="<?php echo $karbo_qr_link; ?>" alt="QR" title="QR" />
      <div class="karbo_style_ds">
        <span>
          <? echo $karbo_text_address; ?>: <a href="<?php echo $karbo_link; ?>"><?php echo $karbo_address; ?></a>
        </span>
        <span>
          <? echo $karbo_text_payment_id; ?>: <?php echo $karbo_payment_id; ?>
        </span>
        <span>
          <? echo $text_amount; ?>: <?php echo $text_total_ext; ?> &#1180;
        </span>
      </div>
      <div class="clear_both">&nbsp;</div>
    </div>
    <div>
      <p>
        <?php echo $text_payment; ?>
      </p>
    </div>
  </div>
  <div class="buttons">
    <div class="right">
      <a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a>
    </div>
</div>
<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>
