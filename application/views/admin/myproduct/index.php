<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$controller = $this->router->fetch_class();
// dd($controller);
$method = $this->router->fetch_method();
?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <?php $this->load->view($this->template_path."_block/where_datatables") ?>
                    <?php $this->load->view($this->template_path."_block/button",array('display_button'=>array('add','delete'))) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <form action="" id="form-table  formhoang" method="post">
                        <input type="hidden" value="0" name="msg" />
                        <table id="data-table" class="table table-bordered table-striped" style="width: 100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                                    <th>ID</th>
                                    <th><?php echo lang('text_title');?></th>
                                    <th><?php echo lang('text_content');?></th>
                                    <th><?php echo lang('text_catalog');?></th>
                                    <th><?php echo lang('text_image');?></th>
                                    <th><?php echo lang('text_size');?></th>
                                    <th><?php echo lang('text_maker');?></th>
                                    <th><?php echo lang('text_price');?></th>
                                    <th><?php echo lang('text_date');?></th>
                                    <th><?php echo lang('text_total');?></th>
                                    <th><?php echo lang('text_action');?></th>
                                </tr>
                            </thead>
                        </table>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add');?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('',['id'=>'form','class'=>'']) ?>
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_language">
                            <div class="tab-content">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label><?php echo lang('form_title');?></label>
                                                <input name="name" placeholder="<?php echo lang('form_title');?>" class="form-control" type="text" />
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo lang('from_content');?></label>
                                                <textarea name="content" placeholder="<?php echo lang('from_content');?>" class="tinymce form-control content_post" rows="10"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo lang('from_category');?></label>
                                                <select data-placeholder="<?php echo lang('from_category');?>" class="form-control" name="catalog" id="catalog"  style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                    <option value="1">Áo khoác</option>
                                                    <option value="2">Quần tây</option>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="">
                                                <div>
                                                    <label class="totong"><?php echo lang('form_size');?></label>
                                                    <div class="form_size">
                                                        <div class="input_left form-group">
                                                            <input name="quantity[0]" placeholder="<?php echo lang('form_quantity');?>" class="form-control" type="text"/>
                                                        </div>
                                                        <div class="input_right form-group">
                                                            <input name="textsize[0]" placeholder="<?php echo lang('form_text_size');?>" class="form-control" type="text" />
                                                        </div>
                                                        <div>
                                                            <i class="fa fa-times"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="add_size" id="0">+ Thêm ...</div>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo lang('form_maker');?></label>
                                                <select data-placeholder="<?php echo lang('form_maker');?>" class="form-control" name="maker_id" id="maker_id"  style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                    <option value="1">Hà Nội</option>
                                                    <option value="2">Thái Nguyên</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo lang('form_price');?></label>
                                                <input id="title_<?php echo $lang_code;?>" name="price" placeholder="<?php echo lang('form_price');?>" class="form-control" type="text" />
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo lang('form_total');?></label>
                                                <input id="title_<?php echo $lang_code;?>" name="total" placeholder="<?php echo lang('form_total');?>" class="form-control" type="text" />
                                            </div>
                                            <?php $this->load->view($this->template_path. '_block/input_media') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="save btn btn-primary pull-left"><?php echo lang('btn_save');?></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('btn_cancel');?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

