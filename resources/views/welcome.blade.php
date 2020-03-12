@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="padding-top: 30px">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form id="formUpload" action="{{ url('/') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-success">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="">File (.xls, .xlsx)</label>
                            <input type="file" class="form-control" name="file">
                            <p class="text-danger">{{ $errors->first('file') }}</p>
                            <div class="mb-3"></div>
                            <example-component />
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>
</div>
@endsection
