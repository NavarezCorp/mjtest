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
    
    console.log(data);
});