$('#datetimepicker').datetimepicker();

$('.add-product').click(function(){
    var html = '<div class="form-group"><label class="col-md-4 control-label"></label><div class="col-md-6">';
    html += $('.product-dropdown').html();
    html += '</div></div>';
    
    $('.product-container').append(html);
});
$('#chart-container').orgchart({
    'data':'/genealogy/' + $('.genealogy_link').attr('id'),
    'depth':6,
    'nodeContent':'title'
});

//# sourceMappingURL=app.js.map
