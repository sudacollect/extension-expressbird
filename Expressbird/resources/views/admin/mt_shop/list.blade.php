@extends('view_path::layouts.default')



@section('content')

<div class="container">
    <div class="row app-row">
        

        
        <h1 class="page-title ms-3">
            门店列表
            <a href="{{ admin_url('extension/expressbird/meituan/shop-query') }}" class="btn btn-primary btn-sm">查询门店</a>
        </h1>
        
        

        
        <div class="col-sm-12">
            <div class="card">
                

                @if(isset($data) && $data->count()>0)
                <div class="card-body" id="list_content">
                    
                    <!-- list start -->

                    <div class="table-responsive data-list">
                      <table class="table table-striped table-hover">
                          <thead>
                              <tr>
                                <th width="10%">ID</th>
                                <th width="12%">编号</th>
                                <th width="12%">配送风险</th>
                                <th width="10%">城市</th>
                                <th width="20%">联系信息</th>
                                
                                <th width="15%">状态</th>
                                
                                <th>操作</th>
                              </tr>
                            </thead>
                          <tbody>
                            
                            @foreach ($data as $item)
                            <tr>
                              
                              <td width="10%">
                                {{ $item->id }}
                              </td>
                              <td width="12%">
                                {{ $item->shop_id }}
                              </td>
                              <td width="12%">
                                {{ $item->delivery_risk_level }}
                              </td>
                              <td width="10%">
                                {{ $item->city }}
                              </td>
                              <td width="20%">
                                {{ $item->contact_name.'/'.$item->contact_phone }}<br>
                                {{ $item->shop_address }}{{ $item->shop_address_detail }}
                              </td>
                              
                              <td width="15%">
                                {{ $item->status_text }}
                              </td>
                              

                              <td>
                                <a class="btn btn-xs btn-primary " style="margin-bottom:10px;" href="{{ admin_url('extension/expressbird/meituan/shop-detail/'.$item->id) }}">查看</a>
                                
                                
                              </td>
                            </tr>
                            @endforeach
                            
                          </tbody>
                      </table>
                  
                      {{ $data->links() }}
                  
                    </div>
                
                </div>
                @else
                @include('view_suda::admin.component.empty',['without_card'=>true])
                @endif
        </div>
        

        
        
        
    </div>
</div>
@endsection