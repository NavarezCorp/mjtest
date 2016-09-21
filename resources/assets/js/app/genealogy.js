$('#chart-container').orgchart({
    'data':'/genealogy/' + $('.genealogy_link').attr('id'),
    'depth':6,
    'nodeContent':'title'
});
