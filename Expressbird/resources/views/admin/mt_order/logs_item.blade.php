<div class="container">

    
            
    <div class="table-responsive data-list">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                  <th width="15%">状态码</th>
                  <th width="20%">状态</th>
                  <th width="40%">描述</th>
                  <th width="20%">时间</th>
                  <th>操作</th>
                </tr>
              </thead>
            <tbody>

              
              
              @foreach ($data as $item)
              <tr>
                
                <td width="15%">
                    {{ $item->status }}
                </td>
                <td width="20%">
                  {{ $item->status_text }}
                </td>
                
                <td width="40%">
                  {{ $item->content }}
                  @if($item->cancel_reason_id)
                  <br>
                  {{ $item->cancel_reason_id.'#'.$item->cancel_reason }}
                  @endif
                </td>
                <td width="20%">
                    {{ $item->created_at }}
                </td>
                <td>
                  @if(!in_array($item->status,[0,20,30,50,99]))
                    <button href="{{ admin_url('extension/expressbird/meituan/orderlog/resend/'.$item->order_id) }}" class="btn btn-danger btn-xs" zhila-info-box="true" data_id="{{ $item->id }}" title="重新发送" data-toggle="tooltip" data-placement="top" >重新发单</button>
                  @endif
                </td>
              </tr>
              @endforeach
              
            </tbody>
        </table>
    
        {{ $data->links() }}
    
      </div>

</div>