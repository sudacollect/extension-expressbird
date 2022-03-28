@extends('view_path::layouts.default')



@section('content')

<div class="container">
    <div class="row zhila-row">
        <div class="page-heading">
            <h1 class="page-title">门店信息</h1>
            
        </div>
        
        
        <div class="col-sm-12">
            <div class="card">
                
                <div class="card-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ admin_url('extension/expressbird/meituan/shop/save') }}">
                        @csrf
                      
                        <div class="form-group{{ $errors->has('shop_name') ? ' has-error' : '' }}">
                            
                          <label for="rules" class="col-sm-2 control-label">
                            门店
                          </label>
                          <div class="col-sm-6">
                              <input type="text" class="form-control" name="shop_name" placeholder="门店" value="{{ $item->shop_name }}" >
                          </div>
                          
                      </div>

                        <div class="form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="col-sm-2 control-label">
                                门店ID
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="shop_id" placeholder="门店ID" value="{{ $item->shop_id }}" >
                            </div>
                            
                        </div>

                        <div class="form-group">
                            
                          <label for="rules" class="col-sm-2 control-label">
                              城市
                          </label>
                          <div class="col-sm-6">
                              <input type="text" class="form-control" name="city" placeholder="城市"  value="{{ $item->city }}" >
                          </div>
                          
                      </div>

                      <div class="form-group">
                            
                        <label for="rules" class="col-sm-2 control-label">
                            联系人
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="contact_name" placeholder="联系人"  value="{{ $item->contact_name }}" >
                        </div>
                        
                    </div>
                    <div class="form-group">
                            
                      <label for="rules" class="col-sm-2 control-label">
                          手机号
                      </label>
                      <div class="col-sm-6">
                          <input type="text" class="form-control" name="contact_phone" placeholder="手机号"  value="{{ $item->contact_phone }}" >
                      </div>
                      
                  </div>
                  <div class="form-group">
                            
                    <label for="rules" class="col-sm-2 control-label">
                        门店地址
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="shop_address" placeholder="门店地址"  value="{{ $item->shop_address }}" >
                    </div>
                    
                  </div>
                  <div class="form-group">
                            
                    <label for="rules" class="col-sm-2 control-label">
                        门店地址
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="shop_address" placeholder="门店地址"  value="{{ $item->shop_address }}" >
                    </div>
                    
                  </div>
                  <div class="form-group">
                            
                    <label for="rules" class="col-sm-2 control-label">
                      详细地址
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="shop_address_detail" placeholder="详细地址"  value="{{ $item->shop_address_detail }}" >
                    </div>
                    
                  </div>
                  <div class="form-group">
                            
                    <label for="rules" class="col-sm-2 control-label">
                      坐标 lat
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="shop_lat" placeholder="lat"  value="{{ $item->shop_lat }}" >
                    </div>
                    
                  </div>
                  <div class="form-group">
                            
                    <label for="rules" class="col-sm-2 control-label">
                      坐标 lng
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="shop_lng" placeholder="lng"  value="{{ $item->shop_lng }}" >
                    </div>
                    
                  </div>
                  <div class="form-group{{ $errors->has('delivery_service_codes') ? ' has-error' : '' }}">
                            
                    <label for="rules" class="col-sm-2 control-label">
                        配送服务代码
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="delivery_service_codes" placeholder="服务代码" value="{{ $item->delivery_service_codes }}">
                    </div>
                    
                </div>

                        <div class="form-group{{ $errors->has('delivery_hours') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="col-sm-2 control-label">
                                配送时间
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="delivery_hours" placeholder="配送时间" value="{{ $item->delivery_hours }}">
                            </div>
                            
                        </div>

                        <div class="form-group{{ $errors->has('scope') ? ' has-error' : '' }}">
                            
                          <label for="rules" class="col-sm-2 control-label">
                              配送范围
                          </label>
                          <div class="col-sm-6">
                            <textarea class="form-control" rows="10">{{ $item->scope }}</textarea>
                          </div>
                          
                      </div>

                      
                    </form>
                    
                   
                    

                </div>
                
            </div>
        </div>
        
        
    </div>
</div>
@endsection