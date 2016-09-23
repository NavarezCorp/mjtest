$('#datetimepicker').datetimepicker();

$('.add-product').click(function(){
    var html = '<div class="form-group"><label class="col-md-4 control-label"></label><div class="col-md-6">';
    html += $('.product-dropdown').html();
    html += '</div></div>';
    
    $('.product-container').append(html);
});
$('#chart-container').orgchart({
    'data':'/genealogy/' + $('#genealogy_ibo_id').val(),
    'nodeContent':'title',
    //'pan': true,
    //'zoom': true
})
.on('click', '.node', function(e){
    //console.log($(this).find('.title').text());
    window.location.href = '/genealogy?ibo_id=' + $(this).find('.title').text();
});
//# sourceMappingURL=app.js.map
