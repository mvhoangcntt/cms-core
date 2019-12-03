<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
?>
<div class="col-sm-7 col-xs-12">
  <?php
  if (in_array($controller, ['myproduct','category', 'post', 'product', 'banner', 'tour', 'voucher', 'project', 'report', 'course'])):

    ?>
    <?php if ($controller != 'myproduct') { ?>
		<div class="col-md-4">
			<div class="form-group">
			  <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-filter"></i></span>
				<select class="form-control select2 filter_category" title="filter_category_id" name="category_id" style="width: 100%;" tabindex="-1" aria-hidden="true">
				  <option value="0"><?php echo lang('from_category'); ?></option>
				</select>
			  </div>
			</div>
		</div>
	<?php } ?>
	<?php if ($controller == 'myproduct') { ?>
		<div class="col-md-4">
			<div class="form-group">
			  <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-filter"></i></span>
				<select class="form-control catalog_id filter_catalog" title="filter_catalog_id" name="filter_catalog" style="width: 100%;" tabindex="-1" aria-hidden="true">
				  <option value=""><?php echo lang('from_category'); ?></option>
				  <option value="1">Áo khoác</option>
				  <option value="2">Quần tây</option>
				</select>
			  </div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
			  <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-filter"></i></span>
				<select class="form-control filter_maker_id filter_maker" title="filter_maker_id" name="filter_maker_id"
						style="width: 100%;" tabindex="-1" aria-hidden="true">
				  <option value=""><?php echo lang('form_maker'); ?></option>
				  <option value="1">Hà Nội</option>
				  <option value="2">Thái Nguyên</option>
				</select>
			  </div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
			  <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-filter"></i></span>
				<select class="form-control filter_size" title="filter_size_id" name="filter_size"
						style="width: 100%;" tabindex="-1" aria-hidden="true">
				  	<option value=""><?php echo lang('form_size'); ?></option>
				  	<?php foreach ($size as $key) { ?>
				  		<option value="<?php echo $key->text_size; ?>"><?php echo $key->text_size; ?></option>
				  	<?php } ?>
				</select>
			  </div>
			</div>
		</div>
	<?php } ?>
  <?php endif; ?>
</div>
