@extends('layout.back.index')

@section('title', '后台管理')

@section('content')
	<div class="row">
        <div class="col-md-offset-3 col-md-6">
            <form action="admin/login" class="form-horizontal" method="post">
                {{ csrf_field() }}
            	<span class="heading">后台管理</span>
                <div class="form-group">
                    <input type="text" class="form-control" id="inputEmail3" name="username" placeholder="用户名">
                    <i class="fa fa-user"></i>
                </div>
                <div class="form-group help">
                    <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="密　码">
                    <i class="fa fa-lock"></i>
                    <a href="#" class="fa fa-question-circle"></a>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default" style="width:100%;" name="login_submit" value="login_btn">登录</button>
                </div>
            </form>
        </div>
    </div>
@endsection