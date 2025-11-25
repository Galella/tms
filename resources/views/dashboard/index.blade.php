@extends('layouts.app')

@section('title', 'Dashboard | Terminal Management System')
@section('header', 'Terminal Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Home</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- Containers IN Card -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ \App\Models\TruckMovement::where('movement_type', 'IN')->whereDate('movement_time', today())->count() }}</h3>
                    <p>Trucks IN Today</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck-loading"></i>
                </div>
                <a href="{{ route('truck-in.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- Containers OUT Card -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ \App\Models\TruckMovement::where('movement_type', 'OUT')->whereDate('movement_time', today())->count() }}</h3>
                    <p>Trucks OUT Today</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck"></i>
                </div>
                <a href="{{ route('truck-out.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- Active Inventory Card -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ \App\Models\ActiveInventory::count() }}</h3>
                    <p>Active Containers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-container-storage"></i>
                </div>
                <a href="{{ route('active-inventory.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- Active Terminals Card -->
            <div class="small-box bg-warning">
                <div class="inner text-white">
                    <h3>{{ Auth::user()->terminals->count() }}</h3>
                    <p>Active Terminals</p>
                </div>
                <div class="icon">
                    <i class="fas fa-terminal"></i>
                </div>
                <a href="{{ route('dashboard') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->

    <!-- Main content row -->
    <div class="row">
        <!-- Left column -->
        <section class="col-lg-8 connectedSortable">
            <!-- Recent Movements Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-1"></i>Recent Truck Movements</h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#movements-all" data-toggle="tab">All</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="movements-all">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Container #</th>
                                            <th>Truck #</th>
                                            <th>Type</th>
                                            <th>Terminal</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(\App\Models\TruckMovement::with(['terminal'])->latest()->take(10)->get() as $movement)
                                            <tr>
                                                <td>{{ $movement->id }}</td>
                                                <td>{{ $movement->container_number }}</td>
                                                <td>{{ $movement->truck_number }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $movement->movement_type === 'IN' ? 'success' : 'danger' }}">
                                                        {{ $movement->movement_type }}
                                                    </span>
                                                </td>
                                                <td>{{ $movement->terminal->name ?? 'N/A' }}</td>
                                                <td>{{ $movement->movement_time->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    <a href="{{ $movement->movement_type === 'IN' ? route('truck-in.show', $movement) : route('truck-out.show', $movement) }}"
                                                       class="btn btn-xs btn-info">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No movement records found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.Left column -->

        <!-- Right column -->
        <section class="col-lg-4 connectedSortable">
            <!-- Stats Overview -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i>Operations Summary</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Import/Export Stats -->
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-exchange-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Import vs Export</span>
                                    <span class="info-box-number">
                                        {{ \App\Models\TruckMovement::where('operation_type', 'IMPORT')->count() }}
                                        <small>IN</small> /
                                        {{ \App\Models\TruckMovement::where('operation_type', 'EXPORT')->count() }}
                                        <small>OUT</small>
                                    </span>
                                </div>
                            </div>

                            <!-- Container Types -->
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-boxes"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Full vs Empty</span>
                                    <span class="info-box-number">
                                        {{ \App\Models\ActiveInventory::where('status', 'FULL')->count() }}
                                        <small>FULL</small> /
                                        {{ \App\Models\ActiveInventory::where('status', 'EMPTY')->count() }}
                                        <small>EMPTY</small>
                                    </span>
                                </div>
                            </div>

                            <!-- Avg Dwell Time -->
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Avg. Dwell Time</span>
                                    <span class="info-box-number">
                                        {{ number_format($activeInventoryAvgDwellDays ?? 0, 1) }}
                                        <small>days</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt mr-1"></i>Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('truck-in.create') }}" class="btn btn-block btn-primary mb-2">
                                <i class="fas fa-truck-loading mr-1"></i> Record Truck IN
                            </a>
                            <a href="{{ route('truck-out.create') }}" class="btn btn-block btn-danger mb-2">
                                <i class="fas fa-truck mr-1"></i> Record Truck OUT
                            </a>
                            <a href="{{ route('containers.index') }}" class="btn btn-block btn-success">
                                <i class="fas fa-container-storage mr-1"></i> View Containers
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.Right column -->
    </div>
    <!-- /.row -->
</div>
@endsection