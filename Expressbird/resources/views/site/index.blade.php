@extends('extension::layouts.default')



@section('content')

<div class="container">
    <div class="row zhila-row" style="margin-bottom:50px;">
        
        <h1 class="page-title" style="text-align:center;margin-bottom:50px;"><i class="zly-home"></i>&nbsp;&nbsp;这是演示应用的首页</h1>
        
        <div class="col-sm-4">
            <div class="card">
                
                <div class="card-header"><i class="zly-books"></i>&nbsp;&nbsp;使用帮助</div>
                <div class="card-body">
                
                <ul style="list-style-type: circle;list-style-position: inside;">
                    <li><a href="https://shimo.im/docs/gVmQ5A5XoWEUPyuE">知啦安装说明</a></li>
                    <li><a href="https://shimo.im/docs/LbvBNrQWyMsEKskE">配置参数说明</a></li>
                    <li><a href="https://shimo.im/docs/DPnjD7W1zVIFCFwi">分类和标签</a></li>
                    <li><a href="https://shimo.im/docs/mzsVcBpIjvM4rqzR">路由机制</a></li>
                </ul>
                
                </div>
                
                <div class="card-footer" style="text-align:right;color:#999;font-size:0.85rem;background:#fff">
                    <a href="https://shimo.im/docs/gVmQ5A5XoWEUPyuE" target="_blank">查看更多</a>
                </div>
                
            </div>
        </div>
        
        
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header"><i class="zly-stack"></i>&nbsp;&nbsp;开发应用</div>
                <div class="card-body">
                
                <ul style="list-style-type: circle;list-style-position: inside;">
                    <li><a href="">应用说明</a></li>
                    <li><a href="">配置参数说明</a></li>
                    <li><a href="">应用路由机制</a></li>
                    <li><a href="">应用静态资源</a></li>
                </ul>
                
                </div>
                <div class="card-footer" style="text-align:right;color:#999;font-size:0.85rem;background:#fff">
                    <a href="https://shimo.im/docs/gVmQ5A5XoWEUPyuE" target="_blank">查看更多</a>
                </div>
            </div>
        </div>
        
        
        <div class="col-sm-4">
            <div class="card">
                
                <div class="card-header"><i class="zly-medal"></i>&nbsp;&nbsp;制作模板</div>
                
                <div class="card-body">
                
                    <ul style="list-style-type: circle;list-style-position: inside;">
                        <li><a href="">模板说明</a></li>
                        <li><a href="">配置参数说明</a></li>
                        <li><a href="">模板调用规则</a></li>
                        <li><a href="">模板目录规则</a></li>
                    </ul>
                
                </div>
                <div class="card-footer" style="text-align:right;color:#999;font-size:0.85rem;background:#fff">
                    <a href="https://shimo.im/docs/gVmQ5A5XoWEUPyuE" target="_blank">查看更多</a>
                </div>
            </div>
        </div>
        
        
    </div>
</div>
@endsection