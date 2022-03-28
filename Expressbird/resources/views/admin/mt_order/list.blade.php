@extends('view_path::layouts.default')



@section('content')

<div class="container">
    <div class="row zhila-row">
        

        
        <h1 class="page-title">
            <i class="zly-paper"></i>&nbsp;&nbsp;订单列表
        </h1>
        
        @if(isset($data) && $data && $data->count()>0)

        
        <div class="col-sm-12">
            <div class="card">
                

                @if(isset($data) && $data->count()>0)
                <div class="card-body" id="list_content">
                    
                    <!-- list start -->

                    <div class="table-responsive data-list">
                      <table class="table table-striped table-hover">
                          <thead>
                              <tr>
                                <th width="12%">订单</th>
                                <th width="12%">发货单号</th>
                                <th width="12%">美团单号</th>
                                <th width="15%">产品编号</th>
                                <th width="15%">状态</th>
                                <th width="15%">费用</th>
                                <th>操作</th>
                              </tr>
                            </thead>
                          <tbody>
                            
                            @foreach ($data as $item)
                            <tr>
                              
                              <td width="12%">
                                {{ $item->shop_order_id }}
                              </td>
                              <td width="12%">
                                {{ $item->delivery_id }}
                              </td>
                              <td width="12%">
                                {{ $item->mt_peisong_id }}
                              </td>
                              <td width="15%">
                                {{ $item->delivery_service_code }}
                              </td>
                              <td width="15%">
                                {{ $item->status_text }}
                              </td>
                              <td width="15%">
                                {{ bcdiv($item->delivery_fee,100,2) }}
                              </td>
                              <td>
                                <a class="zhila-modal-box btn btn-xs btn-primary " style="margin-bottom:10px;" href="{{ admin_url('extension/expressbird/meituan/orderlogs/'.$item->shop_order_id) }}">查看日志</a>
                                
                                {{-- <a class="zhila-modal-box btn btn-xs btn-primary " style="margin-bottom:10px;" href="{{ admin_url('extension/expressbird/printer/edit/'.$item->id) }}">编辑</a> --}}
                                
                                @if($item->status!=50 && $item->status!=99)
                                <button class="btn btn-xs btn-default zhila-info-box " style="margin-bottom:10px;" zhila-info-box="true" data_title="是否取消" data_action="cancel" data_id="{{ $item->id }}" href="{{ admin_url('extension/expressbird/meituan/order/cancel/'.$item->id) }}">取消发单</button>
                                @endif
                              </td>
                            </tr>
                            @endforeach
                            
                          </tbody>
                      </table>
                  
                      {{ $data->links() }}
                  
                    </div>
                
                </div>
                @endif
        </div>
        

        @endif
        
        
    </div>
</div>
@endsection