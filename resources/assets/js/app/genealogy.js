$(document).ready(function(){
    $('#chart-container').orgchart({
        'data':'/genealogy/' + $('.genealogy_link').attr('id'),
        'depth':4,
        'nodeContent':'title'
    });
});