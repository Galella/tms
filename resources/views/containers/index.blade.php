@extends('layouts.app')

@section('title', 'Container Management | Terminal Management System')
@section('header', 'Container Management')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Containers</li>
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Container List</h3>
                    <div class="card-tools">
                        <a href="{{ route('containers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Container
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search containers..." 
                                   value="{{ request('search') }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-2">
                            <select name="size" class="form-control select2" onchange="this.form.submit()">
                                <option value="">All Sizes</option>
                                <option value="20" {{ request('size') == '20' ? 'selected' : '' }}>20 ft</option>
                                <option value="40" {{ request('size') == '40' ? 'selected' : '' }}>40 ft</option>
                                <option value="45" {{ request('size') == '45' ? 'selected' : '' }}>45 ft</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="ownership" class="form-control select2" onchange="this.form.submit()">
                                <option value="">All Ownership</option>
                                <option value="COC" {{ request('ownership') == 'COC' ? 'selected' : '' }}>COC</option>
                                <option value="SOC" {{ request('ownership') == 'SOC' ? 'selected' : '' }}>SOC</option>
                                <option value="FU" {{ request('ownership') == 'FU' ? 'selected' : '' ?>>FU</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="terminal" class="form-control select2" onchange="this.form.submit()">
                                <option value="">All Terminals</option>
                                @foreach($terminals as $terminal)
                                    <option value="{{ $terminal->id }}" 
                                        {{ request('terminal') == $terminal->id ? 'selected' : '' }}>
                                        {{ $terminal->name }} ({{ $terminal->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('containers.index') }}" class="btn btn-default w-100">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Container Number</th>
                                    <th>Size</th>
                                    <th>Type</th>
                                    <th>Ownership</th>
                                    <th>ISO Code</th>
                                    <th>Terminals</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($containers as $container)
                                    <tr>
                                        <td>{{ $container->container_number }}</td>
                                        <td>{{ $container->size }} ft</td>
                                        <td>{{ $container->type }}</td>
                                        <td>
                                            @switch($container->ownership)
                                                @case('COC')
                                                    <span class="badge badge-primary">COC</span>
                                                    @break
                                                @case('SOC')
                                                    <span class="badge badge-success">SOC</span>
                                                    @break
                                                @case('FU')
                                                    <span class="badge badge-warning">FU</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $container->iso_code }}</td>
                                        <td>
                                            @if($container->terminals->count() > 0)
                                                @foreach($container->terminals as $terminal)
                                                    <span class="badge badge-info">{{ $terminal->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('containers.show', $container) }}" class="btn btn-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('containers.edit', $container) }}" class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('containers.destroy', $container) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this container?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No containers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $containers->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection