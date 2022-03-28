@extends('view_path::component.modal',['modal_size'=>'medium'])



@section('content')

<div class="modal-body">
    @include('extension::admin.mt_order.logs_item')
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">取消</span></button>
    
</div>


<script>
    
    $('.modal-body').on('click','a.page-link',function(ev) {
        ev.preventDefault();
        var target = $(this).attr("href");
        
        // load the url and show modal on success
        $(this).parents(".modal-body").load(target, function() { 
            // $("#myModal").modal("show"); 
        });
    });

</script>


@endsection

