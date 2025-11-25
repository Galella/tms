@extends('layouts.app')

@section('title', 'Add Truck IN Operation | Terminal Management System')
@section('header', 'Add Truck IN Operation')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('truck-in.index') }}">Truck IN</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Record Truck IN Operation</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('truck-in.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="terminal_id">Terminal *</label>
                                    <select class="form-control @error('terminal_id') is-invalid @enderror" id="terminal_id" name="terminal_id" required>
                                        <option value="">Select Terminal</option>
                                        @foreach($terminals as $terminal)
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
                                    <small class="form-text text-muted">Must follow ISO 6346 format (3-letter owner code + category digit + 6 digits + check digit)</small>
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
                                    <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id">
                                        <option value="">Select Customer (for FULL containers)</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <select class="form-control @error('shipping_line_id') is-invalid @enderror" id="shipping_line_id" name="shipping_line_id">
                                        <option value="">Select Shipping Line (for EMPTY containers)</option>
                                        @foreach($shippingLines as $line)
                                            <option value="{{ $line->id }}" {{ old('shipping_line_id') == $line->id ? 'selected' : '' }}>
                                                {{ $line->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <label for="container_type">Container Type *</label>
                                    <select class="form-control @error('container_type') is-invalid @enderror" id="container_type" name="container_type" required>
                                        <option value="">Select Container Type</option>
                                        <option value="FULL" {{ old('container_type') == 'FULL' ? 'selected' : '' }}>FULL</option>
                                        <option value="EMPTY" {{ old('container_type') == 'EMPTY' ? 'selected' : '' }}>EMPTY</option>
                                    </select>
                                    @error('container_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="movement_time">Movement Time *</label>
                                    <input type="datetime-local" class="form-control @error('movement_time') is-invalid @enderror" 
                                           id="movement_time" name="movement_time" 
                                           value="{{ old('movement_time', now()->format('Y-m-d\TH:i')) }}" required>
                                    @error('movement_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="block">Block *</label>
                                    <input type="text" class="form-control @error('block') is-invalid @enderror" 
                                           id="block" name="block" 
                                           placeholder="Enter block" 
                                           value="{{ old('block') }}" required>
                                    @error('block')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="row">Row *</label>
                                    <input type="text" class="form-control @error('row') is-invalid @enderror" 
                                           id="row" name="row" 
                                           placeholder="Enter row" 
                                           value="{{ old('row') }}" required>
                                    @error('row')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tier">Tier *</label>
                                    <input type="text" class="form-control @error('tier') is-invalid @enderror" 
                                           id="tier" name="tier" 
                                           placeholder="Enter tier" 
                                           value="{{ old('tier') }}" required>
                                    @error('tier')
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
                        <a href="{{ route('truck-in.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection