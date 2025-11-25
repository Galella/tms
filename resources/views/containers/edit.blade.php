@extends('layouts.app')

@section('title', 'Edit Container | Terminal Management System')
@section('header', 'Edit Container')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('containers.index') }}">Containers</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Container - {{ $container->container_number }}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('containers.update', $container) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="container_number">Container Number</label>
                            <input type="text" class="form-control" id="container_number" 
                                   value="{{ $container->container_number }}" readonly>
                            <small class="form-text text-muted">Container number cannot be changed</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="size">Size *</label>
                                    <select name="size" id="size" class="form-control @error('size') is-invalid @enderror" required>
                                        <option value="">Select Size</option>
                                        <option value="20" {{ old('size', $container->size) == '20' ? 'selected' : '' }}>20 feet</option>
                                        <option value="40" {{ old('size', $container->size) == '40' ? 'selected' : '' }}>40 feet</option>
                                        <option value="45" {{ old('size', $container->size) == '45' ? 'selected' : '' }}>45 feet</option>
                                    </select>
                                    @error('size')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type *</label>
                                    <input type="text" name="type" 
                                           class="form-control @error('type') is-invalid @enderror" 
                                           id="type" 
                                           placeholder="Enter container type (e.g., DRY, REEFER, TANK)" 
                                           value="{{ old('type', $container->type) }}" required maxlength="50">
                                    @error('type')
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
                                    <label for="ownership">Ownership *</label>
                                    <select name="ownership" id="ownership" class="form-control @error('ownership') is-invalid @enderror" required>
                                        <option value="">Select Ownership</option>
                                        <option value="COC" {{ old('ownership', $container->ownership) == 'COC' ? 'selected' : '' }}>COC (Carrier Owned Container)</option>
                                        <option value="SOC" {{ old('ownership', $container->ownership) == 'SOC' ? 'selected' : '' }}>SOC (Shipper Owned Container)</option>
                                        <option value="FU" {{ old('ownership', $container->ownership) == 'FU' ? 'selected' : '' }}>FU (Full Use)</option>
                                    </select>
                                    @error('ownership')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="iso_code">ISO Code *</label>
                                    <input type="text" name="iso_code" 
                                           class="form-control @error('iso_code') is-invalid @enderror" 
                                           id="iso_code" 
                                           placeholder="Enter ISO code (e.g., 22G1, 45G1)" 
                                           value="{{ old('iso_code', $container->iso_code) }}" required maxlength="4">
                                    @error('iso_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">ISO 6346 equipment category and type code</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="terminal_ids">Associated Terminals</label>
                                    <select name="terminal_ids[]" id="terminal_ids" class="form-control select2 @error('terminal_ids') is-invalid @enderror" multiple>
                                        @foreach($terminals as $terminal)
                                            <option value="{{ $terminal->id }}" 
                                                {{ in_array($terminal->id, old('terminal_ids', $container->terminals->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $terminal->name }} ({{ $terminal->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('terminal_ids')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">Hold Ctrl to select multiple terminals</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Container</button>
                        <a href="{{ route('containers.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<!-- Include Select2 CSS and JS for the multiselect functionality -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select terminals",
        allowClear: true
    });
});
</script>
@endsection