@extends('view_path::component.modal')



@section('content')
<form class="form-horizontal ajaxForm" role="form" method="POST" action="{{ admin_url('extension/expressbird/printer/save') }}">
<div class="modal-body">
    <div class="container">

        <div class="col-md-12 col-md-offset-0 press_content">
            
                  {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $item->id }}">
                  <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-sm-3 control-label">
                        设备名称
                    </label>
                    <div class="col-sm-6"><input type="text" name="name" class="form-control" id="name"  placeholder="设备名称" value="{{ $item->name }}"></div>
                  </div>

                  <div class="form-group{{ $errors->has('uuid') ? ' has-error' : '' }}">
                    <label for="uuid" class="col-sm-3 control-label">
                        设备编号
                    </label>
                    <div class="col-sm-6"><input type="text" name="uuid" class="form-control" id="uuid"  placeholder="设备编号" value="{{ $item->uuid }}"></div>
                  </div>

                  <div class="form-group">
                    <label for="secret" class="col-sm-3 control-label">
                        设备密钥
                    </label>
                    <div class="col-sm-6"><input type="text" name="secret" class="form-control" id="secret"  placeholder="设备密钥" value="{{ $item->secret }}"></div>
                  </div>


                  <div class="form-group">
                    <label for="remark" class="col-sm-3 control-label">
                        备注说明
                    </label>
                    <div class="col-sm-6"><input type="text" name="remark" class="form-control" id="remark"  placeholder="备注说明" value="{{ $item->remark }}"></div>
                  </div>
            
            </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">取消</span></button>
    <button type="submit" class="btn btn-primary">保存</button>
</div>

</form>

<script>
    
    jQuery(document).ready(function(){
        $.fn.ajaxform($('.ajaxForm'));
    });
    
</script>

@endsection

