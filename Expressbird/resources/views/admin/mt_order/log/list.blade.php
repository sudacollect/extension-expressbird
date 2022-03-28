@extends('view_path::layouts.default')

@section('content')
<div class="container container-fix">
    <div class="row zhila-row">

        <div class="page-heading">
            <h1 class="page-title">
                发单日志
            </h1>
        </div>

        <div class="col-md-12 col-md-offset-0 press_content">
            {{-- <ul class="nav nav-tabs">
              <li role="presentation" @if($active=='all') class="active" @endif><a href="{{ admin_url($extpath.'/stores') }}">全部门店</a></li>
              <li role="presentation" @if($active=='enable') class="active" @endif><a href="{{ admin_url($extpath.'/stores/enable') }}">营业门店</a></li>
              <li role="presentation" @if($active=='disabled') class="active" @endif><a href="{{ admin_url($extpath.'/stores/disabled') }}">歇业门店</a></li>
            </ul> --}}
            <div class="card">

                
                
              @if(isset($data) && $data->count()>0)
                <div class="card-body" id="list_content">
                    
                    <!-- list start -->
                    
                    
                  
                    <div class="table-responsive data-list">
                      <table class="table table-striped table-hover">
                          <thead>
                              <tr>
                                <th width="15%">订单号</th>
                                <th width="15%">状态</th>
                                <th width="30%">描述</th>
                                <th>操作</th>
                              </tr>
                            </thead>
                          <tbody>

                            
                            
                            @foreach ($data as $item)
                            <tr>
                              
                              <td width="15%">
                                  {{ $item->shop_order_id }}
                                  @if(isset($item->sf_order_id))
                                  <br>{{ $item->sf_order_id }}
                                  @endif
                              </td>
                              <td width="15%">
                                {{ $item->status_text }}
                              </td>
                              
                              <td width="30%">
                                {{ $item->status_desc }}
                              </td>
                              <td>
                                @if(!in_array($item->order_status,['1','2','10','12','15','17']))
                                  <button href="{{ admin_url('extension/expressbird/log/resend/'.$item->id) }}" class="btn btn-danger btn-xs" zhila-info-box="true" data_id="{{ $item->id }}" title="重新发送" data-toggle="tooltip" data-placement="top" >重新发单</button>
                                @endif
                              </td>
                            </tr>
                            @endforeach
                            
                          </tbody>
                      </table>
                  
                      {{ $data->links() }}
                  
                    </div>
                    
                    
                    <!-- list end -->
                    
                </div>
                

              @else
                
                  <div class="card-body">
                  @include('view_zest::admin.component.empty',['no_border'=>true])
                  </div>
                
              @endif

            </div>
            
        </div>
        
    </div>
</div>

@endsection



@push('scripts')
<script type="text/javascript">

    $(document).ready(function(){
        $('[data-toggle="datepicker"]').datetimepicker({
            format: 'YYYY-MM-DD',
            showClear:true,
            sideBySide:true,
            useCurrent:'day'
        });

    });
</script>

@endpush
