@extends('view_path::layouts.default')



@section('content')

<div class="container">
    <div class="row app-row">
        <div class="page-heading">
            
            <h1 class="page-title"><i class="zly-gear-s-o"></i>&nbsp;&nbsp;链接参数</h1>
        </div>
        
        
        <div class="col-sm-12">
            <div class="card">
                
                <div class="card-body">

                    <form class="form-horizontal ajaxForm" role="form" method="POST" action="{{ admin_url('extension/expressbird/$express_code/setting-url/save') }}">
                        {{ csrf_field() }}
                      
                      
                    <div class="form-group{{ $errors->has('print_finish_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 control-label">
                            配送状态更改
                        </label>
                        <div class="col-sm-6">
                        <input type="text" class="form-control" readonly value="{{ url('api/expressbird/callback') }}">
                        </div>
                        
                    </div>

                    <div class="form-group{{ $errors->has('order_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 control-label">
                            订单完成
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" readonly value="{{ url('api/expressbird/finish') }}">
                        </div>
                        
                    </div>

                    <div class="form-group{{ $errors->has('status_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 control-label">
                            顺丰原因取消
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" readonly value="{{ url('api/expressbird/cancel') }}">
                        </div>
                        
                    </div>

                    <div class="form-group{{ $errors->has('button_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 control-label">
                            订单配送异常
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" readonly value="{{ url('api/expressbird/unusual') }}">
                        </div>
                        
                    </div>

                      
                      {{-- <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                          <button type="submit" class="btn btn-primary">{{ trans('zest_lang::press.submit_save') }}</button>
                        </div>
                      </div> --}}
                      
                    </form>
                
                </div>
                
            </div>
        </div>
        
        
    </div>
</div>
@endsection