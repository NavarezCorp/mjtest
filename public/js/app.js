$(document).ready(function(){
    $('#datetimepicker').datetimepicker();
});
$(document).ready(function(){
    $('#chart-container').orgchart({
        'data':'/genealogy/' + $('.genealogy_link').attr('id'),
        'depth':4,
        'nodeContent':'title'
    });
});
//# sourceMappingURL=app.js.map
