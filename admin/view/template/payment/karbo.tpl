<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general" class="page">
          <table class="form">
            <tr>
              <td><?php echo $wallet_address; ?>:</td>
              <td><input type="text" name="karbo_wallet_address" value="<?php echo $karbo_wallet_address; ?>" size="100" /></td>
            </tr>
            <tr>
              <td><?php echo $wallet_type; ?>:</td>
              <td><select name="karbo_wallet_type">
                    <?php if ($karbo_wallet_type == 0){ ?>
                    <option value="0" selected="selected"><?php echo $wallet_type_simplewallet; ?></option>
                    <?php } else { ?>
                    <option value="0"><?php echo $wallet_type_simplewallet; ?></option>
                    <?php }; if ($karbo_wallet_type == 1){ ?>
                    <option value="1" selected="selected"><?php echo $wallet_type_walletd; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $wallet_type_walletd; ?></option>
                    <?php }; if ($karbo_wallet_type == 2){ ?>
                    <option value="2" selected="selected"><?php echo $wallet_type_gateway; ?></option>
                    <?php } else { ?>
                    <option value="2"><?php echo $wallet_type_gateway; ?></option>
                    <?php } ?>
                  </select>
              </td>
            </tr>
            <tr>
              <td><?php echo $wallet_host; ?>:</td>
              <td><input type="text" name="karbo_wallet_host" value="<?php echo $karbo_wallet_host; ?>" size="20" /></td>
            </tr>
            <tr>
              <td><?php echo $wallet_port; ?>:</td>
              <td><input type="text" name="karbo_wallet_port" value="<?php echo $karbo_wallet_port; ?>" size="5" /></td>
            </tr>
            <tr>
              <td><?php echo $wallet_ssl; ?>:</td>
              <td><select name="karbo_wallet_ssl">
                    <?php if ($karbo_wallet_ssl == 1){ ?>
                    <option value="1" selected="selected"><?php echo $karbo_text_yes; ?></option>
                    <option value="0"><?php echo $karbo_text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $karbo_text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $karbo_text_no; ?></option>
                    <?php } ?>
                  </select>
              </td>
            </tr>
            <tr>
              <td><?php echo $karbo_text_order_status; ?>:</td>
              <td><select name="karbo_order_status_id">
                  <?php foreach ($karbo_order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $karbo_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $karbo_text_order_payment_status; ?>:</td>
              <td><select name="karbo_order_payment_status_id">
                  <?php foreach ($karbo_order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $karbo_order_payment_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $karbo_text_geo_zone; ?>:</td>
              <td><select name="karbo_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($karbo_geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $karbo_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $karbo_text_status; ?>:</td>
              <td><select name="karbo_status">
                  <?php if ($karbo_status == 1) { ?>
                  <option value="1" selected="selected"><?php echo $karbo_text_enable; ?></option>
                  <option value="0"><?php echo $karbo_text_disable; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $karbo_text_enable; ?></option>
                  <option value="0" selected="selected"><?php echo $karbo_text_disable; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $karbo_text_sort_order; ?>:</td>
              <td><input type="text" name="karbo_sort_order" value="<?php echo $karbo_sort_order; ?>" size="1" /></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
