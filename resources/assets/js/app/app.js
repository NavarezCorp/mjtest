$('#datetimepicker').datetimepicker();

$('.add-product').click(function(){
    /*
    var html = '<div class="form-group"><label class="col-md-4 control-label"></label><div class="col-md-6">';
    html += $('.product-dropdown').html();
    html += '</div></div>';
    */
    $('.add-product').addClass('link-disabled');
    
    var str = $('.product-container').attr('id');
    var arr = str.split('-');
    var id = arr[arr.length - 1];
    id++;
    
    var html = '<div class="form-group">';
    html += '<input type="hidden" name="pcid[]" id="pcid-' + id + '" value="">';
    html += '<label class="col-md-4 control-label"></label>';
    html += '<div class="col-md-6 product-dropdown">';
    html += '<input id="product-code-text-' + id + '" type="text" class="form-control product-purchase-code" name="product_code[]" style="width:400px; float:left; margin-right:10px;">';
    html += '<button type="button" class="btn btn-default product-code-search" id="product-code-button-' + id + '">';
    html += '<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>';
    html += '</button>';
    html += '<span class="help-block code-help-block-' + id + '" style="display:none;"></span>';
    html += '</div>';
    html += '</div>';
    
    $('.product-container').append(html);
    $('.product-container').attr('id', 'ctr-' +id);
});

$("#activation-code-generator").submit(function(e){
    e.preventDefault();
    
    var data = [];
    
    $.each($('#activation-code-generator').serializeArray(), function(i, val){
        data[val.name] = val.value;
    });
    
    $.getJSON('/activationcode/get_activation_code', 
        {
            activation_type_id:data['activation_type_id'],
            howmanychar:data['howmanychar'], 
            howmanycode:data['howmanycode']
        },
        function(data){
            window.location = "/activationcode";
        }
    );
});

$("#product-code-generator").submit(function(e){
    e.preventDefault();
    
    var data = [];
    
    $.each($('#product-code-generator').serializeArray(), function(i, val){
        data[val.name] = val.value;
    });
    
    $.getJSON('/productcode/get_product_code', 
        {
            ibo_id:data['ibo_id'],
            product_id:data['product_id'],
            howmanychar:data['howmanychar'], 
            howmanyproducts:data['howmanyproducts'],
        },
        function(data){
            //window.location = "/productcode";
            
            for(var i = 0; i < data.new_product_code.length; i++){
                var html = '';
                html += '<tr>';
                html += '<td>' + data.new_product_code[i].id + '</td>';
                html += '<td>' + data.new_product_code[i].product + '</td>';
                html += '<td>' + data.new_product_code[i].code + '</td>';
                html += '<td>' + data.new_product_code[i].transfered_to + '</td>';
                html += '<td>' + data.new_product_code[i].datetime_transfered + '</td>';
                html += '</tr>';

                $('.new-product-code-table').append(html);
            }
            
            $('.print-product-codes').removeClass('link-disabled');
        }
    );
});

$('.code-search').click(function(){
    $.getJSON('/activationcode/check_activation_code', {code:$('#activation_code').val().toUpperCase()}, function(data){
        if(data){
            $('#cid').val(data.id);
            $('#activation_code_type').val(data.activation_code_type);
            
            var color = 'green';
            
            var html = '';
            html += 'Code Type: <strong>' + data.activation_code_type + '</strong>';
            
            if(data.datetime_used){
                html += '<br/>(used: ' + data.datetime_used + ')';
                color = 'red';
            }
            else{
                html += ' (not yet used)';
                
                $('.register-button').removeAttr('disabled');
            }
            
            $('.code-help-block').css('color', color).html(html).show();
        }
        else $('.code-help-block').css('color', 'red').html('<strong>Code does not exist</strong>').show();
    });
});

//$('.product-code-search').click(function(){
$(document).on("click", '.product-code-search', function(){
    var str = $(this).attr('id');
    var arr = str.split('-');
    var id = arr[arr.length - 1];
    var orig_code = $('#product-code-text-' + id).val();
    var code_ = $('#product-code-text-' + id).val().toUpperCase();
    
    $.getJSON('/productcode/check_product_code', {code:code_}, function(data){
        if(data){
            console.log(data);
            $('#pcid-' + id).val(data.id);
            
            var color = 'green';
            
            var html = '';
            html += 'Product name: <strong>' + data.product.name + '</strong>';
            
            if(data.datetime_used){
                html += '<br/>Product Code: ' + orig_code;
                html += '<br/>(used: ' + data.datetime_used + ')';
                color = 'red';
                
                $('#product-code-text-' + id).val('');
            }
            else{
                html += ' (not yet used)';
                
                $('.register-button').removeAttr('disabled');
                $('.add-product').removeClass('link-disabled');
            }
            
            $('.code-help-block-' + id).css('color', color).html(html).show();
        }
        else{
            $('#product-code-text-' + id).val('');
            $('.code-help-block-' + id).css('color', 'red').html('<strong>Product Code "' + orig_code + '" does not exist</strong>').show();
        }
    });
});

$("#activation_code").click(function(){
    $(this).val('');
    $('.code-help-block').hide();
    $('.register-button').attr('disabled', true);
});

$('#code-tabs a').click(function(e){
    e.preventDefault();
    $(this).tab('show');
})

$('input[name="transfer_to"]').click(function(e){
    if($(this).val() == 'ms') $('.ms-text-box').show();
    else $('.ms-text-box').hide();
})

$('#selectWeek').change(function(){
    //console.log($(this).val());
    window.location = '/commissionsummaryreport/all/' + $(this).val() + '|' + $('#selectYear').val();
});

$('#selectYear').change(function(){
    //console.log($(this).val());
    window.location = '/commissionsummaryreport/all/' + $('#selectWeek').val() + '|' + $(this).val();
});

$('.product-code-transfered-to, .product-code-product-id').change(function(){
    //if($('.product-code-transfered-to').val() || $('.product-code-product-id').val()) $('.print-all-product-codes').removeClass('link-disabled');
    //else $('.print-all-product-codes').addClass('link-disabled');
    
    var query_string = '?transfered_to=' + $('.product-code-transfered-to').val() + '&product_id=' + $('.product-code-product-id').val();
    window.location = '/productcode/all' + query_string;
});

$(document).on('click', '.product-purchase-code', function(){
    $('.register-button').attr('disabled', true);
});