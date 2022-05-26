@extends('view_path::layouts.default')



@section('content')

<div class="container">
    <div class="row app-row">
        <div class="page-heading">
            <h1 class="page-title">模拟接单测试</h1>
        </div>
        
        
        <div class="col-sm-6">
            <div class="card">
                
                <div class="card-body">

                    <form class="" role="form" method="POST" action="{{ admin_url('extension/expressbird/meituan/test-order-post') }}">
                        @csrf
                      
                      
                        <div class="mb-3{{ $errors->has('mt_peisong_id') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="">
                                美团配送单号
                            </label>
                            
                            <input type="text" class="form-control" name="mt_peisong_id" placeholder="美团配送单号" @if(isset($mt_peisong_id)) value="{{ $mt_peisong_id }}" @endif>
                            
                            
                        </div>

                        <div class="mb-3{{ $errors->has('delivery_id') ? ' has-error' : '' }}">
                            
                            <label for="rules" class="">
                                发货单号
                            </label>
                            
                            <input type="text" class="form-control" name="delivery_id" placeholder="delivery_id" @if(isset($delivery_id)) value="{{ $delivery_id }}" @endif>
                            
                            
                        </div>

                        <div class="mb-3">
                            
                            <label for="rules" class="">
                                模拟操作
                            </label>
                            
                            <select name="action" class="form-control">
                                <option value="arrange">模拟接单</option>
                                <option value="pickup">模拟取货</option>
                                <option value="deliver">模拟送达</option>
                                <option value="rearrange">模拟改派</option>
                                <option value="reportException">模拟异常上报</option>
                            </select>
                            
                            
                        </div>
                        
                        <div class="mb-3">
                            
                            <button type="submit" class="btn btn-primary">模拟操作</button>
                            
                        </div>
                      
                    </form>
                    
                    @if(!isset($test_success))
                    @php

                    echo '<pre>';
                    print_r($test_fail);

                    @endphp

                    @else

                    <p class="my-5">模拟成功</p>

                    @endif
                        
                    

                </div>
                
            </div>
        </div>
        
        
    </div>
</div>
@endsection