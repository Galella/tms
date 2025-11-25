@extends('layouts.app')

@section('title', 'Truck IN Operations | Terminal Management System')
@section('header', 'Truck IN Operations')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Truck IN</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Truck IN Movements</h3>
                    <div class="card-tools">
                        <a href="{{ route('truck-in.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Container Number</th>
                                <th>Truck Number</th>
                                <th>Terminal</th>
                                <th>Container Type</th>
                                <th>Operation Type</th>
                                <th>Status</th>
                                <th>Movement Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($truckMovements as $movement)
                                <tr>
                                    <td>{{ $movement->id }}</td>
                                    <td>{{ $movement->container_number }}</td>
                                    <td>{{ $movement->truck_number }}</td>
                                    <td>{{ $movement->terminal->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $movement->container_type === 'FULL' ? 'badge-success' : 'badge-info' }}">
                                            {{ $movement->container_type }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->operation_type }}</td>
                                    <td>
                                        <span class="badge badge-primary">IN</span>
                                    </td>
                                    <td>{{ $movement->movement_time->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('truck-in.show', $movement) }}" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No truck IN records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $truckMovements->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection