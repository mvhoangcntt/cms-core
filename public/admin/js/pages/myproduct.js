$(function () {
    //load lang
    load_lang('myproduct');
    //load slug
    init_slug('title','slug');
    //load table ajax
    init_data_table();
    //bind checkbox table
    init_checkbox_table();

});
//form them moi
function add_form() {
    save_method = 'add';
    $('#modal_form').modal('show');
    $('.modal-title').text('Thêm sản phẩm');
    $('#modal_form').trigger("reset");
    $('.form_size').remove();
    $(".totong").parent().append('\
    <div class="form_size">\
        <div class="input_left form-group">\
            <input name="quantity[0]" placeholder="Số lượng" class="form-control" type="text"/>\
        </div>\
        <div class="input_right form-group">\
            <input name="textsize[0]" placeholder="size" class="form-control" type="text" />\
        </div>\
        <div>\
            <i class="fa fa-times"></i>\
        </div>\
    </div>\
    ');
}
// them xóa form size
$(function () {
    var size = 0;
    $(".add_size").click(function(){
        size++;
        $(".totong").parent().append('\
        <div class="form_size">\
            <div class="input_left form-group">\
                <input name="quantity['+size+']" placeholder="Số lượng" class="form-control" type="text"/>\
            </div>\
            <div class="input_right form-group">\
                <input name="textsize['+size+']" placeholder="size" class="form-control" type="text" />\
            </div>\
            <div>\
                <i class="fa fa-times"></i>\
            </div>\
        </div>\
        ');
    });
    // xóa kích cỡ trong form
    $(document).on("click",".fa",function(){
        $( this ).parents('.form_size').remove();
    })
});

//ajax luu form
function save()
{
    if (chech_error() === true) {
    $('#btnSave').text(language['btn_saving']); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var id = $(".save").attr("id");
    var url;
    if(save_method == 'add') {
        url = url_ajax_add;
    } else {
        url = url_ajax_update+"/"+id;
    }
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        enctype: "multipart/form-data",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data){
            toastr[data.type](data.message);
            if(data.type === "warning"){
                $('span.text-danger').remove();
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').closest('.form-group').append(val);
                })
            } else {
                $('#modal_form').modal('hide');
                reload_table();
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            $('#btnSave').text(language['btn_save']);
            $('#btnSave').attr('disabled',false);
        }
    });
    }
}

//form sua
function edit_form(id)
{
    save_method = 'update';
    $('#modal_form').modal('show');
    $('.modal-title').text('Sửa thông tin sản phẩm');
    $('#modal_form').trigger("reset");
    $(".save").attr("id",id);
    //Ajax Load data from ajax
    $.ajax({
        url : url_ajax_edit+"/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('.form_size').remove();
            $.each(data, function( key, value ) {
                $("input[name='"+key+"']").val(value);
                $("textarea[name='"+key+"']").val(value);
                $("select[name='"+key+"']").val(value,'selected');
            });
            for(var key in data.size){
                $(".totong").parent().append('\
                <div class="form_size">\
                    <div class="input_left form-group">\
                        <input name="quantity['+key+']" value="'+data.size[key]['quantity']+'" placeholder="Số lượng" class="form-control" type="text"/>\
                    </div>\
                    <div class="input_right form-group">\
                        <input name="textsize['+key+']" value="'+data.size[key]['text_size']+'" placeholder="size" class="form-control" type="text" />\
                    </div>\
                    <div>\
                        <i class="fa fa-times"></i>\
                    </div>\
                </div>\
                ');
            }
            
            $('#modal_form').modal('show');
            $('.modal-title').text('Sửa bài viết');

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
            console.log(jqXHR);
        }
    });
}
function delete_item(id){
    $.ajax({
        type : "GET",
        url : url_ajax_delete +"/"+ id,
        dataType: "JSON",
        success: function(response){
            if (response.type) {
                toastr[response.type](response.message);
            }
            reload_table();
        }
    })
}

function chech_error(){
    $(".err").remove();
    var name = $("input[name='name']").val();
    var content = $("textarea[name='content']").val();
    var catalog = $("select[name='catalog']").val();
    var thumbnail = $("input[name='thumbnail']").val();
    var maker_id = $("input[name='maker_id']").val();
    var price = $("input[name='price']").val();
    var total = $("input[name='total']").val();

    var errArray = [];
    if (name === '') {
        errArray.push({name : 'Không được để trống !'});
    }else{
        if (name.length < 5) {
            errArray.push({name : 'Độ dài lớn hơn 5 ký tự !'});
        }
    }
    
    if (content === '') {
        errArray.push({content : 'Không được để trống !'});
    }else{
        if (content.length < 5) {
            errArray.push({content : 'Độ dài lớn hơn 5 ký tự !'});
        }
    }
    // if (save_method !== 'update') {
    //     if (thumbnail === '') {
    //         errArray.push({thumbnail : 'Mời chọn ảnh !'});
    //     }
    // }           
    if (price === '') {
        errArray.push({price : 'Không được để trống !'});
    }
    if (total === '') {
        errArray.push({total : 'Không được để trống !'});
    }                        
    for(var i = 0; i< errArray.length; i++){
        for(var key in errArray[i]){
            $("input[name='"+key+"']").parent().append("<div class='err'>"+ errArray[i][key] +"</div>");
            if (key === "content") {
                $("textarea[name='content']").parent().append("<div class='err'>"+ errArray[i][key] +"</div>");
            }
        }
    }
    if (errArray.length == 0) {
        return true;
    }
    return false;
}

loadFilter();

function loadFilter() {
    var data  = Array(); 
    data['catalog']  = $("select[name='filter_catalog']").val();
    data['maker_id'] = $("select[name='filter_maker_id']").val();
    data['size']     = $("select[name='filter_size']").val();
    $("select[name='filter_size']").change(function(){
        data['size'] = $(this).val();
        filterDatatables(data);
    })
    $("select[name='filter_maker_id']").change(function(){
        data['maker_id'] = $(this).val();
        filterDatatables(data);
    })
    $("select.catalog_id").on('change', function () {
        data['catalog'] = $(this).val();
        filterDatatables(data);
    });
}
function filterDatatables(data) {
  dataFilter = data;
  console.log(dataFilter);
  reload_table();
}