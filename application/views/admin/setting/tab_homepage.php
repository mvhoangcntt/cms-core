<div class="tab-pane" id="tab_homepage">
   <ul class="nav nav-tabs">
        <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
            <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>><a
                href="#tab1_<?php echo $lang_code; ?>"
                data-toggle="tab"><img
                      src="<?php echo $this->templates_assets; ?>/flag/<?php echo $lang_code ?>.png"> <?php echo $lang_name; ?>
                </a>
            </li>
        <?php } ?>
    </ul>
    <div class="tab-content">

        <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
            <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>" id="tab1_<?php echo $lang_code; ?>">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tiêu đề</label>
                                <input name="home[<?php echo $lang_code; ?>][course]" placeholder="Tiêu đề"
                                class="form-control" type="text"
                                value="<?php echo isset($home[$lang_code]['course']) ? $home[$lang_code]['course'] : ''; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Content</label>
                                <input name="home[<?php echo $lang_code; ?>][t_course]" placeholder="Nội dung"
                                class="form-control" type="text"
                                value="<?php echo isset($home[$lang_code]['t_course']) ? $home[$lang_code]['t_course'] : ''; ?>"/>
                            </div>
                        </div>
                    </div> 
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tiêu đề</label>
                                <input name="home[<?php echo $lang_code; ?>][lecturers]"
                                placeholder="Tiêu đề"
                                class="form-control" type="text"
                                value="<?php echo isset($home[$lang_code]['lecturers']) ? $home[$lang_code]['lecturers'] : ''; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                                <label>Content</label>
                                <input name="home[<?php echo $lang_code; ?>][t_lecturers]"
                                placeholder="Nội dung"
                                class="form-control" type="text"
                                value="<?php echo isset($home[$lang_code]['t_lecturers']) ? $home[$lang_code]['t_lecturers'] : ''; ?>"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tiêu đề</label>
                                <input name="home[<?php echo $lang_code; ?>][student]"
                                placeholder="Tiêu đề"
                                class="form-control" type="text"
                                value="<?php echo isset($home[$lang_code]['student']) ? $home[$lang_code]['student'] : ''; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Content</label>
                                <input name="home[<?php echo $lang_code; ?>][t_student]"
                                placeholder="Nội dung"
                                class="form-control" type="text"
                                value="<?php echo isset($home[$lang_code]['t_student']) ? $home[$lang_code]['t_student'] : ''; ?>"/>
                            </div>
                        </div>
                    </div>

                    </div> 
            </div> 
        <?php } ?>
    </div>

</div>