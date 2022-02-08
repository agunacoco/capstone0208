@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>

                <input type="file" name="poster" id="poster" />
                <form action="{{ route('concerts.store') }}" method="post" enctype="multipart/form-data" >
                    {{-- get 외의 접근에서는 꼭 이 토큰을 함께 전송해야만 접근이 가능. --}}
                    {{-- 로그인을 할 때 id와 pw를 넣고 로그인을 하면 서버가 그것을 확인해서 id와 pw가 맞으면 이 사용자가 유효한 사용자라는 토큰을 발행 해줍니다. --}}

                    <div>
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
