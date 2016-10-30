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

$('#code-tabs a').click(function(e){
    e.preventDefault();
    $(this).tab('show');
})

$('#selectWeek').change(function(){
    //console.log($(this).val());
    window.location = '/commissionsummaryreport/all/' + $(this).val() + '|' + $('#selectYear').val();
});

$('#selectYear').change(function(){
    //console.log($(this).val());
    window.location = '/commissionsummaryreport/all/' + $('#selectWeek').val() + '|' + $(this).val();
});