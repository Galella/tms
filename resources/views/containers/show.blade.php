@extends('layouts.app')

@section('title', 'View Container | Terminal Management System')
@section('header', 'Container Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('containers.index') }}">Containers</a></li>
    <li class="breadcrumb-item active">{{ $container->container_number }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Container Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('containers.index') }}" class="btn btn-default">
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
                                <p class="form-control-static">{{ $container->container_number }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Size:</label>
                                <p class="form-control-static">{{ $container->size }} feet</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type:</label>
                                <p class="form-control-static">{{ $container->type }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ownership">Ownership:</label>
                                <p class="form-control-static">
                                    @switch($container->ownership)
                                        @case('COC')
                                            <span class="badge badge-primary">COC (Carrier Owned Container)</span>
                                            @break
                                        @case('SOC')
                                            <span class="badge badge-success">SOC (Shipper Owned Container)</span>
                                            @break
                                        @case('FU')
                                            <span class="badge badge-warning">FU (Full Use)</span>
                                            @break
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="iso_code">ISO Code:</label>
                                <p class="form-control-static">{{ $container->iso_code }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="terminals">Associated Terminals:</label>
                                <p class="form-control-static">
                                    @if($container->terminals->count() > 0)
                                        @foreach($container->terminals as $terminal)
                                            <span class="badge badge-info">{{ $terminal->name }} ({{ $terminal->code }})</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-secondary">No terminals assigned</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="created_at">Date Created:</label>
                                <p class="form-control-static">{{ $container->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="updated_at">Last Updated:</label>
                                <p class="form-control-static">{{ $container->updated_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <a href="{{ route('containers.edit', $container) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Container
                    </a>
                    <a href="{{ route('containers.index') }}" class="btn btn-default">
                        <i class="fas fa-list"></i> View All Containers
                    </a>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection