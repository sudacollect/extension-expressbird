@extends('view_path::layouts.default')



@section('content')

<div class="container">
    <div class="row app-row">
        <div class="page-heading">
            <h1 class="page-title">参数配置</h1>
            <span class="help-block">需要创建好应用后获取相关参数</span>
        </div>
        
        
        <div class="col-sm-12">
            <div class="card">
                
                <div class="card-body">

                    <form class="ajaxForm" role="form" method="POST" action="{{ admin_url('extension/expressbird/'.$express_code.'/setting/save') }}">
                        @csrf
                      
                        
                        <div class="mb-3 row{{ $errors->has('app_name') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="col-sm-2 col-form-label">
                                应用名称
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="express_info[app_name]" placeholder="应用名称" @if(isset($data['app_name'])) value="{{ $data['app_name'] }}" @endif>
                            </div>
                            
                        </div>

                        <div class="mb-3 row{{ $errors->has('app_id') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="col-sm-2 control-label">
                                应用ID/Key
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="express_info[app_id]" placeholder="应用ID/Key" @if(isset($data['app_id'])) value="{{ $data['app_id'] }}" @endif>
                            </div>
                            
                        </div>

                        <div class="mb-3 row{{ $errors->has('app_secret') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="col-sm-2 control-label">
                                应用密钥
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="express_info[app_secret]" placeholder="应用密钥" @if(isset($data['app_secret'])) value="{{ $data['app_secret'] }}" @endif>
                            </div>
                            
                        </div>
                        

                        <div class="mb-3 row{{ $errors->has('max_distance') ? ' has-error' : '' }}">
                            
                            <label for="max_distance" class="col-sm-2 control-label">
                                配送距离
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="express_info[max_distance]" placeholder="配送距离" @if(isset($data) && isset($data['max_distance'])) value="{{ $data['max_distance'] }}" @endif>
                                <span class="help-block">单位：km, 超出配送距离则不配送</span>
                            </div>
                            
                        </div>

                        <div class="mb-3 row{{ $errors->has('max_distance_notice') ? ' has-error' : '' }}">
                            
                            <label for="max_distance" class="col-sm-2 control-label">
                                超距离提示
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="express_info[max_distance_notice]" placeholder="超距离提示" @if(isset($data) && isset($data['max_distance_notice'])) value="{{ $data['max_distance_notice'] }}" @endif>
                                <span class="help-block">超出配送距离时提醒用户</span>
                            </div>
                            
                        </div>

                        <div class="mb-3 row{{ $errors->has('app_secret') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="col-sm-2 control-label">
                                运费公式
                            </label>
                            <div class="col-sm-6">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">起步公里</span>
                                    <input type="number" name="express_info[begin_km]" @if(isset($data) && isset($data['begin_km'])) value="{{ $data['begin_km'] }}" @endif class="form-control form-control-sm">
                                    <span class="input-group-text" id="basic-addon1">km</span>
                                </div>
                                
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">起步重量</span>
                                    <input type="number" name="express_info[begin_weight]" @if(isset($data) && isset($data['begin_weight'])) value="{{ $data['begin_weight'] }}" @endif class="form-control form-control-sm">
                                    <span class="input-group-text" id="basic-addon1">kg</span>
                                </div>
                                
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">起步费用</span>
                                    <input type="number" name="express_info[begin_cost]" @if(isset($data) && isset($data['begin_cost'])) value="{{ $data['begin_cost'] }}" @endif class="form-control form-control-sm">
                                    <span class="input-group-text" id="basic-addon1">元</span>
                                </div>
                                
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">超出后每公里</span>
                                    <input type="number" name="express_info[km_cost]" @if(isset($data) && isset($data['km_cost'])) value="{{ $data['km_cost'] }}" @endif class="form-control form-control-sm">
                                    <span class="input-group-text" id="basic-addon1">元</span>
                                </div>
                                
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="basic-addon1">超出后每公斤</span>
                                    <input type="number" name="express_info[weight_cost]" @if(isset($data) && isset($data['weight_cost'])) value="{{ $data['weight_cost'] }}" @endif class="form-control form-control-sm">
                                    <span class="input-group-text" id="basic-addon1">元</span>
                                </div>
                                
                            </div>
                            
                        </div>

                        
                        <div class="mb-3 row">
                            <div class="offset-sm-2 col-sm-6">
                            <button type="submit" class="btn btn-primary">提交保存</button>
                            </div>
                        </div>
                      
                    </form>
                
                </div>
                
            </div>
        </div>
        
        
    </div>
</div>
@endsection