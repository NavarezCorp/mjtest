<style>
    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 22px;
        background-color: transparent;
        border-collapse: collapse;
        border-spacing: 0;
        display: table;
        border-color: grey;
    }
    
    thead {
        display: table-header-group;
        vertical-align: middle;
        border-color: inherit;
    }
    
    tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }
    
    td, th {
        display: table-cell;
    }
    
    th {
        font-weight: bold;
        text-align: left;
    }
    
    .table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td {
        line-height: 1.6;
    }
    
    .table > thead > tr > th {
        vertical-align: bottom;
        border-bottom: 2px solid #ddd;
    }
    
    .table-condensed > thead > tr > th, .table-condensed > thead > tr > td, .table-condensed > tbody > tr > th, .table-condensed > tbody > tr > td, .table-condensed > tfoot > tr > th, .table-condensed > tfoot > tr > td {
        padding: 5px;
    }
    
    .table > caption + thead > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > th, .table > thead:first-child > tr:first-child > td {
        border-top: 0;
    }
    
    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }
    
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    
    .table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td {
        line-height: 1.6;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }
    
    .table-condensed > thead > tr > th, .table-condensed > thead > tr > td, .table-condensed > tbody > tr > th, .table-condensed > tbody > tr > td, .table-condensed > tfoot > tr > th, .table-condensed > tfoot > tr > td {
        padding: 5px;
    }
</style>

<h2>SFI Activation Codes</h2>
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Activation Type</th>
            <th>DateTime Created</th>
            <th>Created By</th>
            <th>Used By</th>
            <th>DateTime Used</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ decrypt($value->code) }}</td>
                <td>{{ App\ActivationType::find($value->activation_type_id)->name }}</td>
                <td>{{ $value->created_at }}</td>
                <td>{{ App\User::find($value->created_by)->name }}</td>
                <td>{{ $value->used_by_ibo_id ? App\User::where('ibo_id', $value->used_by_ibo_id)->first()->name : '' }}</td>
                <td>{{ $value->datetime_used }}</td>
            </tr>
        @endforeach
    </tbody>
</table>