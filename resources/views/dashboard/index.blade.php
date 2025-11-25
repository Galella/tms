@extends('layouts.app')

@section('title', 'Dashboard | Terminal Management System')
@section('header', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- IN Count box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ \App\Models\TruckMovement::where('movement_type', 'IN')->count() }}</h3>
                    <p>Truck IN Today</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck-loading"></i>
                </div>
                <a href="{{ route('truck-in.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- OUT Count box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ \App\Models\TruckMovement::where('movement_type', 'OUT')->count() }}</h3>
                    <p>Truck OUT Today</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck"></i>
                </div>
                <a href="{{ route('truck-out.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- Active Inventory box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ \App\Models\ActiveInventory::count() }}</h3>
                    <p>Active Inventory</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- Terminal box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ Auth::user()->terminals->count() }}</h3>
                    <p>Terminals</p>
                </div>
                <div class="icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
</div>
@endsection