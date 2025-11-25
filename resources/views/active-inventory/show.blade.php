@extends('layouts.app')

@section('title', 'View Active Inventory | Terminal Management System')
@section('header', 'Active Inventory Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('active-inventory.index') }}">Active Inventory</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Active Inventory Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('active-inventory.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="container_number">Container Number:</label>
                                <p class="form-control-static">{{ $activeInventory->container_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="terminal">Terminal:</label>
                                <p class="form-control-static">{{ $activeInventory->terminal->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer">Customer:</label>
                                <p class="form-control-static">{{ $activeInventory->customer->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shipping_line">Shipping Line:</label>
                                <p class="form-control-static">{{ $activeInventory->shippingLine->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <p class="form-control-static">
                                    <span class="badge badge-{{ $activeInventory->status === 'FULL' ? 'danger' : 'success' }}">
                                        {{ $activeInventory->status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location">Location:</label>
                                <p class="form-control-static">{{ $activeInventory->block }}/{{ $activeInventory->row }}/{{ $activeInventory->tier }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_in">Date In:</label>
                                <p class="form-control-static">{{ $activeInventory->date_in->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="created_at">Created At:</label>
                                <p class="form-control-static">{{ $activeInventory->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <a href="{{ route('active-inventory.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection