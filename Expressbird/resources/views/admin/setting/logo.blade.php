@extends('view_path::layouts.default')



@section('content')

<div class="container">
    <div class="row zhila-row">
        <div class="page-heading">
            
            <h1 class="page-title"><i class="zly-gear-s-o"></i>&nbsp;&nbsp;基本设置</h1>
        </div>
        
        
        <div class="col-sm-12">
            <div class="card">
                
                <div class="card-body">

                    <form class="form-horizontal ajaxForm" role="form" method="POST" action="{{ admin_url('extension/expressbird/setting/key/save') }}">
                        {{ csrf_field() }}
                      
                      

                    <div class="form-group{{ $errors->has('relate_model') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 control-label">
                            关联数据
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name="relate_model" class="form-control" placeholder="关联模型 例如 \Ecdo\Store" @if(isset($data) && isset($data['relate_model'])) value="{{ $data['relate_model'] }}" @endif>
                        </div>
                        
                    </div>

                    <div class="form-group{{ $errors->has('relate_model_column') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 control-label">
                            字段键值
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name="relate_model_column" class="form-control" placeholder="关联字段" @if(isset($data) && isset($data['relate_model_column'])) value="{{ $data['relate_model_column'] }}" @endif>
                        </div>
                        
                    </div>


                    
                      

                      
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                          <button type="submit" class="btn btn-primary">{{ trans('zest_lang::press.submit_save') }}</button>
                        </div>
                      </div>
                      
                    </form>
                
                </div>
                
            </div>
        </div>
        
        
    </div>
</div>


@endsection



@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        
        $.fn.mediabox('media','zhila/modal/media/media','zhila/upload/image/media');

    })
</script>
@endpush