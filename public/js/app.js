$('#datetimepicker').datetimepicker();

$('.add-product').click(function(){
    var html = '<div class="form-group"><label class="col-md-4 control-label"></label><div class="col-md-6">';
    html += $('.product-dropdown').html();
    html += '</div></div>';
    
    $('.product-container').append(html);
});

$("#code-generator").submit(function(e){
    e.preventDefault();
    
    var data = [];
    
    $.each($('#code-generator').serializeArray(), function(i, val){
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

$("#activation_code").click(function(){
    $(this).val('');
    $('.code-help-block').hide();
    $('.register-button').attr('disabled', true);
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
                
        if(id_arr[0]) window.location.href = '/ibo/create?placement_id=' + id_arr[0] + '&placement_position=' + id_arr[1];
    }
    else window.location.href = '/genealogy?sponsor_id=' + $(this).find('.title').text() + '&placement_id=' + $(this).attr('id');
});
//# sourceMappingURL=app.js.map
