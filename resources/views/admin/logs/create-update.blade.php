@extends('layouts.admin')

@section('title')
   Log güncelleme
@endsection

@section('css')
@endsection

@section('content')
    <x-bootstrap.card>
        <x-slot:header>
            <h2 class="card-title">Log Güncelleme</h2>
        </x-slot:header>
        <x-slot:body>
            <div class="example-container">
                <div class="example-content">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="{{ isset($log) ? route('log.edit', ['id' => $log->id]) : '' }}" method="POST" id="logForm">
                        @csrf
                        <label for="process_id" class="form-label">Process ID</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Process ID"
                               name="process_id"
                               id="process_id"
                               value="{{ isset($log) ? $log->process_id : '' }}"
                        >
                        <label for="process_type" class="form-label">Process Type</label>
                        <input type="text"
                               class="form-control form-control-solid-bordered m-b-sm"
                               aria-describedby="solidBoderedInputExample"
                               placeholder="Process Type"
                               name="process_type"
                               id="process_type"
                               value="{{ isset($log) ? $log->process_type : '' }}"
                        >
                        <label for="user_id" class="form-label">Users</label>
                        <select
                            class="form-select form-control form-control-solid-bordered m-b-sm"
                            aria-label="Users"
                            name="user_id"
                            id="user_id"
                        >
                            <option value="{{ null }}">Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id}}" {{ isset($log) && $log->user_id === $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <hr>
                        <div class="col-6 mx-auto mt-2">
                            <button type="submit" class="btn btn-success btn-rounded w-100" id="btnSave">
                               Güncelleme
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </x-slot:body>
    </x-bootstrap.card>
@endsection

@section('js')
@endsection
