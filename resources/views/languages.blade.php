@extends('layouts.main')

@section('head_links')
    <link href="{{ asset('dark-editable/dark-editable.css')}}" rel="stylesheet"/>
    <script src="{{ asset('dark-editable/dark-editable.js')}}"></script>
@endsection

@section('content')
<div class="container">
    
    <!-- <h1>New Translation key</h1> -->
    <form method="POST" action="{{ route('translations.create') }}">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label>Key:</label>
                <input type="text" name="key" class="form-control Key" placeholder="Enter Key......">
            </div>
 
            <div class="col-md-4">
                <label>Value (en):</label>
                <input type="text" name="value" class="form-control Key" placeholder="Enter Value......">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success">Add</button>
            </div>
        </div>
    </form>
 
    <h2>Translate key value pair</h2>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th>Key</th>
            @if($languages->count() > 0)
                @foreach($languages as $language)
                    <th>{{ $language->title }}({{ $language->language_code }})</th>
                @endforeach
            @endif
            <th width="80px;">Action</th>
        </tr>
        </thead>
        <tbody>
            @if($columnsCount > 0)
                @foreach($columns[0] as $columnKey => $columnValue)
                    <tr>
                        <td><a href="#" class="translate-key" data-title="Enter Key" data-type="text" data-pk="{{ $columnKey }}" data-url="{{ route('translation.update.json.key') }}" data-placeholder="Required">{{ $columnKey }}</a></td>
                        @for($i=1; $i<=$columnsCount; ++$i)
                        <td><a href="#" data-title="Enter Translate" class="translate" data-code="{{ $columns[$i]['lang'] }}" data-type="textarea" data-pk="{{ $columnKey }}" data-url="{{ route('translation.update.json') }}">{{ isset($columns[$i]['data'][$columnKey]) ? $columns[$i]['data'][$columnKey] : '' }}</a></td>
                        @endfor
                        <td><button data-action="{{ route('translations.destroy', $columnKey) }}" class="btn btn-danger btn-xs remove-key">DeleteIT</button></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@endsection


@section('footer_js') 
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    // $('.translate').editable({
    //     params: function(params) {
    //         params.code = $(this).editable().data('code');
    //         return params;
    //     }
    // });

    $('.translate, .translate-key').each(function(index, el) {

        var code = $(this).data('code');
        new DarkEditable(this, {
              type: "text",
              name: code
          })
    });

    $('.translate').on('click',function(event) {
        event.preventDefault();
        const popover = new DarkEditable(this, {})
        // var pk = $(this).data('pk');
        // var url = $(this).data('url');
        // var code = $(this).data('code');
        // var txt = $(this).text();
        /* Act on the event */
        // const popover = new DarkEditable(this, {
        //       type: "text",
        //       value: txt,
        //       disabled: false,
        //       emptytext: 'empty',
        //       url: url,
        //       //name: code, // name of the field
        //       pk: pk, // primary key
        //       send: true, // send data to server
        //       success: function(response, newValue) {
        //         if(response.status == 'error') return response.msg; //msg will be shown in editable form
        //       },
        //       error: function(response, newValue) {
        //         // do something
        //       },
        // });
    });
    
    // $('.translate-key').on('click',function(event) {
    //     event.preventDefault();
    //     // var pk = $(this).data('pk');
    //     // var url = $(this).data('url');
    //     // var code = $(this).data('code');
    //     // var txt = $(this).text();

    //     if($.trim(txt) == '') {
    //         return 'Key is must required';
    //     }
    //     console.log("MMM="+txt)
    //     /* Act on the event */
    //     // const popover = new DarkEditable(this, {
    //     //       type: "text",
    //     //       value: txt,
    //     //       disabled: false,
    //     //       emptytext: 'empty',
    //     //       url: url,
    //     //       //name: code, // name of the field
    //     //       pk: pk, // primary key
    //     //       send: true, // send data to server
    //     //       success: function(response, newValue) {
    //     //         if(response.status == 'error') return response.msg; //msg will be shown in editable form
    //     //       },
    //     //       error: function(response, newValue) {
    //     //         // do something
    //     //       },
    //     // });
    // });
 
    $('.translate-key').DarkEditable({
        validate: function(value) {
            if($.trim(value) == '') {
                return 'Key is must required';
            }
        }
    });
 
 
    $('body').on('click', '.remove-key', function(){
        var cObj = $(this);
        if (confirm("Are you sure want to remove this stuff?")) {
            $.ajax({
                url: cObj.data('action'),
                method: 'DELETE',
                success: function(data) {
                    cObj.parents("tr").remove();
                    alert("Your imaginary file has deleted.");
                }
            });
        }
    });
</script>
@endsection