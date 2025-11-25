@extends('layouts.app')

@section('title', 'Add Truck OUT Operation | Terminal Management System')
@section('header', 'Add Truck OUT Operation')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('truck-out.index') }}">Truck OUT</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Enter Truck OUT Details</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('truck-out.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="terminal_id">Terminal *</label>
                                    <select class="form-control @error('terminal_id') is-invalid @enderror" id="terminal_id" name="terminal_id" required>
                                        <option value="">Select Terminal</option>
                                        @foreach(Auth::user()->terminals as $terminal)
                                            <option value="{{ $terminal->id }}" {{ old('terminal_id') == $terminal->id ? 'selected' : '' }}>
                                                {{ $terminal->name }} ({{ $terminal->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="container_number">Container Number *</label>
                                    <input type="text" class="form-control @error('container_number') is-invalid @enderror"
                                           id="container_number" name="container_number"
                                           placeholder="Enter container number (e.g., ABCD1234567)"
                                           value="{{ old('container_number') }}" required>
                                    @error('container_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="truck_number">Truck Number *</label>
                                    <input type="text" class="form-control @error('truck_number') is-invalid @enderror"
                                           id="truck_number" name="truck_number"
                                           placeholder="Enter truck number"
                                           value="{{ old('truck_number') }}" required>
                                    @error('truck_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="operation_type">Operation Type *</label>
                                    <select class="form-control @error('operation_type') is-invalid @enderror" id="operation_type" name="operation_type" required>
                                        <option value="">Select Operation Type</option>
                                        <option value="EXPORT" {{ old('operation_type') == 'EXPORT' ? 'selected' : '' }}>EXPORT</option>
                                        <option value="IMPORT" {{ old('operation_type') == 'IMPORT' ? 'selected' : '' }}>IMPORT</option>
                                        <option value="STUFFING" {{ old('operation_type') == 'STUFFING' ? 'selected' : '' }}>STUFFING</option>
                                        <option value="RESTUFFING" {{ old('operation_type') == 'RESTUFFING' ? 'selected' : '' }}>RESTUFFING</option>
                                        <option value="GATE" {{ old('operation_type') == 'GATE' ? 'selected' : '' }}>GATE</option>
                                    </select>
                                    @error('operation_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <input type="number" class="form-control @error('customer_id') is-invalid @enderror"
                                           id="customer_id" name="customer_id"
                                           placeholder="Enter customer ID"
                                           value="{{ old('customer_id') }}">
                                    @error('customer_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipping_line_id">Shipping Line</label>
                                    <input type="number" class="form-control @error('shipping_line_id') is-invalid @enderror"
                                           id="shipping_line_id" name="shipping_line_id"
                                           placeholder="Enter shipping line ID"
                                           value="{{ old('shipping_line_id') }}">
                                    @error('shipping_line_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="driver_name">Driver Name</label>
                                    <input type="text" class="form-control @error('driver_name') is-invalid @enderror"
                                           id="driver_name" name="driver_name"
                                           placeholder="Enter driver name"
                                           value="{{ old('driver_name') }}">
                                    @error('driver_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="chassis_number">Chassis Number</label>
                                    <input type="text" class="form-control @error('chassis_number') is-invalid @enderror"
                                           id="chassis_number" name="chassis_number"
                                           placeholder="Enter chassis number"
                                           value="{{ old('chassis_number') }}">
                                    @error('chassis_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror"
                                      id="remarks" name="remarks"
                                      rows="3"
                                      placeholder="Enter remarks">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('truck-out.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection