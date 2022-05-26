@extends('view_path::layouts.default')



@section('content')

<div class="container">
    <div class="row app-row">
        <div class="page-heading">
            
            <h1 class="page-title"><i class="zly-gear-s-o"></i>&nbsp;&nbsp;设置演示</h1>
            
        </div>
        
        
        <div class="col-sm-12">
            <div class="card">
                
                <div class="card-body">
                
                    <form class="form-horizontal ajaxForm" role="form" method="POST" action="{{ admin_url('#') }}">
                        {{ csrf_field() }}
                      
                      
                      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                          
                        <label for="inputName" class="col-sm-3 control-label">
                            小程序
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name="name"  class="form-control" id="name" placeholder="{{ trans('zest_lang::press.input_text_placeholder',['column'=>'小程序名称']) }}">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                      </div>
                      
                      <div class="form-group{{ $errors->has('appid') ? ' has-error' : '' }}">
                          
                        <label for="inputName" class="col-sm-3 control-label">
                            appid
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name="appid"  class="form-control" id="appid" placeholder="{{ trans('zest_lang::press.input_text_placeholder',['column'=>'小程序appid']) }}">
                            @if ($errors->has('appid'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('appid') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                      </div>
                      
                      <div class="form-group{{ $errors->has('secret') ? ' has-error' : '' }}">
                          
                        <label for="inputName" class="col-sm-3 control-label">
                            secret
                        </label>
                        <div class="col-sm-6">
                            <input type="text" name="secret"  class="form-control" id="secret" placeholder="{{ trans('zest_lang::press.input_text_placeholder',['column'=>'小程序secret']) }}">
                            @if ($errors->has('secret'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('secret') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                      </div>

                      
                      <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
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