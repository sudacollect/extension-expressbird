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

                    <form class="ajaxForm" role="form" method="POST" action="{{ admin_url('extension/expressbird/$express_code/setting-url/save') }}">
                        @csrf
                      
                      
                    <div class="mb-3 row{{ $errors->has('print_finish_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 col-form-label">
                            配送订单状态回调URL
                        </label>
                        <div class="col-sm-6">
                        <input type="text" class="form-control" readonly value="{{ url('api/expressbird/meituan/order/callback') }}">
                        </div>
                        
                    </div>

                    <div class="mb-3 row{{ $errors->has('order_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 col-form-label">
                            配送异常信息回调URLw
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" readonly value="{{ url('api/expressbird/meituan/order/unusual') }}">
                        </div>
                        
                    </div>

                    <div class="mb-3 row{{ $errors->has('status_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 col-form-label">
                            配送范围变更回调URL
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" readonly value="{{ url('api/expressbird//meituan/update/shop_areas') }}">
                        </div>
                        
                    </div>

                    <div class="mb-3 row{{ $errors->has('button_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 col-form-label">
                            配送风险等级变更回调URL
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" readonly value="{{ url('api/expressbird/meituan/update/shop_risk') }}">
                        </div>
                        
                    </div>


                    <div class="mb-3 row{{ $errors->has('button_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 col-form-label">
                            门店创建结果回调URL
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" readonly value="{{ url('api/expressbird/meituan/update/shop_status') }}">
                        </div>
                        
                    </div>


                    <div class="mb-3 row{{ $errors->has('button_url') ? ' has-error' : '' }}">
                          
                        <label for="rules" class="col-sm-2 col-form-label">
                            配送员上下班打卡回调URL
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" readonly value="{{ url('api/expressbird/meituan/update/rider_status') }}">
                        </div>
                        
                    </div>

                      
                      {{-- <div class="mb-3 row">
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