$('#chart-container').orgchart({
    'data':'/genealogy/' + $('#genealogy_ibo_id').val(),
    'nodeContent':'title',
    //'pan': true,
    //'zoom': true
})
.on('click', '.node', function(e){
    window.location.href = '/genealogy?ibo_id=' + $(this).find('.title').text();
});