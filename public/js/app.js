$(".flushout-date").datepicker({
    dateFormat: 'MM d, yy'
});

$('.add-product').click(function(){
    /*
    var html = '<div class="form-group"><label class="col-md-4 control-label"></label><div class="col-md-6">';
    html += $('.product-dropdown').html();
    html += '</div></div>';
    */
    $('.add-product').addClass('link-disabled');
    $('.product-purchase-code').prop('readonly', true);
    $('.product-code-search').attr('disabled', true);
    
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
                html += '<br/>(already used to ' + data.used_by_ibo + ' (' + data.used_by_ibo_id + ') ' + ' at ' + data.datetime_used + ')';
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
    var used = false;
    
    $.getJSON('/productcode/check_product_code', {code:code_}, function(data){
        if(data){
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
                /*
                $('input[name^="product_code"]').each(function() {
                    console.log($(this).val());
                });
                */
                for(var i = 1; i < id; i++) if($('#product-code-text-' + i).val() == code_) used = true;

                if(!used){
                    html += ' (not yet used)';

                    $('.register-button').removeAttr('disabled');
                    $('.add-product').removeClass('link-disabled');
                }
                else{
                    html += ' (currently used)';
                    color = 'red';
                }
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
    window.location = '/commissionsummaryreport/all/' + $(this).val() + '|' + $('#selectYear').val();
});

$('#selectYear').change(function(){
    window.location = '/commissionsummaryreport/all/1|' + $(this).val();
});

$('#indirect-week').change(function(){
    console.log('indirect commission week changed');
    window.location = '/indirectcommission500up/' + $(this).val() + '|' + $('#indirect-year').val();
});

$('#indirect-year').change(function(){
    console.log('indirect commission year changed');
    window.location = '/indirectcommission500up/' + $('#indirect-week').val() + '|' + $(this).val();
});

$('#all-indirect-week').change(function(){
    console.log('all indirect commission week changed');
    window.location = '/allindirectcommission/' + $(this).val() + '|' + $('#all-indirect-year').val();
});

$('#all-indirect-year').change(function(){
    console.log('all indirect commission year changed');
    window.location = '/allindirectcommission/' + $('#all-indirect-week').val() + '|' + $(this).val();
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

$("#ibo_id").click(function(){
    $("#name").val('');
});

$("#name").click(function(){
    $("#ibo_id").val('');
});

$('.ibo-search-button').click(function(e){
    e.preventDefault();
    
    $('.ibo-search-loading').show();
    
    $.getJSON('/ibo/search', {ibo_id:$('#ibo_id').val(), name:$('#name').val()}, function(data){
        if(data){
            var html = '<hr>';
            
            for(var key in data.ibo.info){
                var info = data.ibo.info[key];
                
                html += '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
                html += '    <div class="panel panel-default">';
                html += '        <div class="panel-heading" role="tab" id="headingOne">';
                html += '            <h4 class="panel-title">';
                html += '                    ' + info.firstname + ' ' + info.middlename + ' ' + info.lastname + ' (' + info.ibo_id + ')';
                html += '                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse' + info.id + '" aria-expanded="false" aria-controls="collapse' + info.id + '">';
                html += '                   <span class="pull-right">View</span>'
                html += '                </a>';
                html += '            </h4>';
                html += '        </div>';
                html += '        <div id="collapse' + info.id + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading' + info.id + '">';
                html += '            <div class="panel-body">';
                html += '               <table class="table">';
                html += '                   <tbody>';
                html += '                       <tr><td class="ibo-search-table-left">Name</td><td class="ibo-search-table-right">' + info.firstname + ' ' + info.middlename + ' ' + info.lastname + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Email</td><td class="ibo-search-table-right">' + info.email + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Gender</td><td class="ibo-search-table-right">' + info.gender_id + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Birthdate</td><td class="ibo-search-table-right">' + info.birth_date + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Marital Status</td><td class="ibo-search-table-right">' + info.marital_status_id + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">TIN</td><td class="ibo-search-table-right">' + info.tin + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">SSS</td><td class="ibo-search-table-right">' + info.sss + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Address</td><td class="ibo-search-table-right">' + info.address + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">City</td><td class="ibo-search-table-right">' + info.city + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Province</td><td class="ibo-search-table-right">' + info.province + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Contact #</td><td class="ibo-search-table-right">' + info.contact_no + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Sponsor</td><td class="ibo-search-table-right">' + info.sponsor_id + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Placement</td><td class="ibo-search-table-right">' + info.placement_id + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Placement Position</td><td class="ibo-search-table-right">' + info.placement_position + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Activation code</td><td class="ibo-search-table-right">' + info.activation_code + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Pickup center</td><td class="ibo-search-table-right">' + info.pickup_center_id + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Bank</td><td class="ibo-search-table-right">' + info.bank_id + '</td></tr>';
                html += '                       <tr><td class="ibo-search-table-left">Account #</td><td class="ibo-search-table-right">' + info.account_no + '</td></tr>';
                html += '                   </tbody>';
                html += '               </table>';
                html += '            </div>';
                html += '        </div>';
                html += '    </div>';
                html += '</div>';
            }
            
            $('.ibo-search-display').html(html);
            
            $('.ibo-search-loading').hide();
        }
    });
});

$('#rebates-week').change(function(){
    console.log('all rebates commission week changed');
    window.location = '/commissionsummaryreport/0?type=monthly&month=' + $(this).val() + '&year=' + $('#rebates-year').val();
});

$('#rebates-year').change(function(){
    console.log('all rebates commission year changed');
    window.location = '/commissionsummaryreport/0?type=monthly&month=' + $('#rebates-week').val() + '&year=' + $(this).val();
});

$('#myrebates-week').change(function(){
    console.log('all rebates commission week changed');
    window.location = '/commissionsummaryreport/' + $('#myrebates-ibo-id').val() + '?type=myrebate&month=' + $(this).val() + '&year=' + $('#myrebates-year').val();
});

$('#myrebates-year').change(function(){
    console.log('all rebates commission year changed');
    window.location = '/commissionsummaryreport/' + $('#myrebates-ibo-id').val() + '?type=myrebate&month=' + $('#myrebates-week').val() + '&year=' + $(this).val();
});

$('.change-password').click(function(e){
    console.log('change password');
    
    $('.change-password-message-main, .change-password-message').html('');
});

$('#old-password, #new-password').keyup(function(e){
    if($('#old-password').val() && $('#new-password').val()) $('.change-password-button').attr('disabled', false);
    else $('.change-password-button').attr('disabled', true);
});

$('.change-password-button').click(function(e){
    console.log('change password button');
    
    $.getJSON('/user/changepassword', {old_password:$('#old-password').val(), new_password:$('#new-password').val()}, function(data){
        if(data){
            console.log(data);
            
            if(!data.authenticated){
                $('.change-password-message').html('<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + data.message + '</div>');
            }
            else{
                $('.change-password-message-main').html('<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + data.message + '</div>');
                $('#old-password, #new-password').val('');
                $(this).attr('disabled', true);
                $('#modal-change-password').modal('hide');
            }
        }
    });
});

$('.close-password-button').click(function(e){
    $('#old-password, #new-password').val('');
    $('.change-password-button').attr('disabled', true);
});

$('.ibo-info-search').click(function(){
    var id_ = $(this).attr('data-id');
    var ibo_id_ = $('#' + id_).val();

    $.getJSON('/ibo/search', {ibo_id:ibo_id_}, function(data){
        var html = 'IBO not registered';

        if(data) html = 'IBO Name: ' + data.ibo.info[0].firstname + ' ' + data.ibo.info[0].middlename + ' ' + data.ibo.info[0].lastname;

        $('.' + id_ + '-help-block').css('color', 'green').html(html).show();
    });
});

$('.flushout-fetch').click(function(){
    console.log('flushout');
    window.location = '/flushout?date=' + $('.flushout-date').val();
});

var sponsor_id = 0;

if(typeof $('#genealogy_sponsor_id').val() !== 'undefined') sponsor_id = $('#genealogy_sponsor_id').val();

$('#chart-container').orgchart({
    'data':'/genealogy/' + sponsor_id,
    'nodeContent':'title',
    'nodeId':'id'
})
.on('click', '.node', function(e){
    if($(this).find('.title').text() == ''){
        var _id = $(this).attr('id');
        var id_arr = _id.split('|');
                
        if(id_arr[0]){
            //window.location.href = '/ibo/create?placement_id=' + id_arr[0] + '&placement_position=' + id_arr[1];
            window.open('/ibo/create?placement_id=' + id_arr[0] + '&placement_position=' + id_arr[1], '_blank');
        }
    }
    else{
        window.location.href = '/genealogy?sponsor_id=' + $(this).find('.title').text() + '&placement_id=' + $(this).attr('id');
        //window.open('/genealogy?sponsor_id=' + $(this).find('.title').text() + '&placement_id=' + $(this).attr('id'), '_blank');
    }
});
//# sourceMappingURL=app.js.map
