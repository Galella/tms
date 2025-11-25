@extends('layouts.app')

@section('title', 'Active Inventory | Terminal Management System')
@section('header', 'Active Inventory')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Active Inventory</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Active Inventory List</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Container Number</th>
                                <th>Terminal</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Block/Row/Tier</th>
                                <th>Date In</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeInventory as $inventory)
                                <tr>
                                    <td>{{ $inventory->container_number }}</td>
                                    <td>{{ $inventory->terminal->name ?? 'N/A' }}</td>
                                    <td>{{ $inventory->customer->name ?? ($inventory->shipping_line->name ?? 'N/A') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $inventory->status === 'FULL' ? 'danger' : 'success' }}">
                                            {{ $inventory->status }}
                                        </span>
                                    </td>
                                    <td>{{ $inventory->block }}/{{ $inventory->row }}/{{ $inventory->tier }}</td>
                                    <td>{{ $inventory->date_in->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No active inventory records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $activeInventory->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection