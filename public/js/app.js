$('#datetimepicker').datetimepicker();

$('.add-product').click(function(){
    var html = '<div class="form-group"><label class="col-md-4 control-label"></label><div class="col-md-6">';
    html += $('.product-dropdown').html();
    html += '</div></div>';
    
    $('.product-container').append(html);
});
$('#chart-container').orgchart({
    'data':'/genealogy/' + $('#genealogy_sponsor_id').val(),
    'nodeContent':'title',
    'nodeId':'id'
})
.on('click', '.node', function(e){
    if($(this).find('.title').text() == ''){
        var _id = $(this).attr('id');
        var id_arr = _id.split('|');
                
        window.location.href = '/ibo/create?placement_id=' + id_arr[0] + '&placement_position=' + id_arr[1];
    }
    else window.location.href = '/genealogy?sponsor_id=' + $(this).find('.title').text() + '&placement_id=' + $(this).attr('id');
});
//# sourceMappingURL=app.js.map
