@extends('layouts.app')

@section('title', 'Truck OUT Operations | Terminal Management System')
@section('header', 'Truck OUT Operations')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Truck OUT</li>
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
                    <h3 class="card-title">Truck OUT Records</h3>
                    <div class="card-tools">
                        <a href="{{ route('truck-out.create') }}" class="btn btn-primary">
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
                                <th>Driver Name</th>
                                <th>Date</th>
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
                                    <td>{{ $movement->container_type }}</td>
                                    <td>{{ $movement->operation_type }}</td>
                                    <td>{{ $movement->driver_name ?? 'N/A' }}</td>
                                    <td>{{ $movement->movement_time->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No truck OUT records found.</td>
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