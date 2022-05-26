@extends('view_path::layouts.default')



@section('content')

<div class="container">
    <div class="row app-row">
        <div class="page-heading">
            <h1 class="page-title">查询门店信息</h1>
            
        </div>
        
        
        <div class="col-sm-12">
            <div class="card">
                
                <div class="card-body">

                    <form class="form-horizontal" role="form" method="POST" action="{{ admin_url('extension/expressbird/meituan/shop-query-filter') }}">
                        @csrf
                      
                      
                        <div class="form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="col-sm-2 control-label">
                                门店ID
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="shop_id" placeholder="门店ID" @if(isset($shop_id)) value="{{ $shop_id }}" @endif>
                            </div>
                            
                        </div>

                        <div class="form-group{{ $errors->has('delivery_service_code') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="col-sm-2 control-label">
                                配送服务代码
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="delivery_service_code" placeholder="服务代码" @if(isset($delivery_service_code)) value="{{ $delivery_service_code }}" @endif>
                            </div>
                            
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-6">
                            <button type="submit" class="btn btn-primary">查询</button>
                            </div>
                        </div>
                      
                    </form>
                    
                    <div class="col-sm-6">
                        @if(isset($item))
                    
                        <ul class="list-group">
                        @foreach($item as $k=>$v)
                        
                        <li class="list-group-item">
                            <label label-default>{{ $k }}</label>
                            <br>{{ $v }}
                        </li>

                        @endforeach
                        </ul>

                        @endif
                    </div>
                    

                </div>
                
            </div>
        </div>
        
        
    </div>
</div>
@endsection