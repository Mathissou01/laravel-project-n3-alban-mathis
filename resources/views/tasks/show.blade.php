@extends('layouts.dashboard')

@push('page-styles')
    {{--- ---}}
@endpush

@section('content')
<!-- BEGIN: Header -->
<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                        Details de la tâche
                    </h1>
                </div>
            </div>

            {{-- @include('partials._breadcrumbs') --}}
        </div>
    </div>
</header>

<div class="container-xl px-2 mt-n10">
    <div class="row">
        <div class="col-xl-4">
            <!-- task image card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Image</div>
                <div class="card-body text-center">
                    <!-- task image -->
                    <img class="img-account-profile mb-2" src="{{ asset('assets/img/tasks/default.webp') }}" alt="" id="image-preview" />
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <!-- BEGIN: task Information -->
            <div class="card mb-4">
                <div class="card-header">
                   Information de la tâche
                </div>
                <div class="card-body">
                    <!-- Form Group (task name) -->
                    <div class="mb-3">
                        <label class="small mb-1">Nom</label>
                        <div class="form-control form-control-solid">{{ $task->name }}</div>
                    </div>
                    <!-- Form Row -->
                    <div class="row gx-3 mb-3">
                        <!-- Form Group (type of task category) -->
                        <div class="col-md-6">
                            <label class="small mb-1">Category</label>
                            <div class="form-control form-control-solid">{{ $task->category->name  }}</div>
                        </div>
            
                        <div class="col-md-6">
                            <label class="small mb-1">Date</label>
                            <div class="form-control form-control-solid">{{ $task->date  }}</div>
                        </div>
                    </div>
                    <!-- Submit button -->
                    <a class="btn btn-primary" href="{{ route('tasks.index') }}">Retour</a>
                </div>
            </div>
            <!-- END: task Information -->
        </div>
    </div>
</div>
<!-- END: Main Page Content -->
@endsection

@push('page-scripts')
    {{--- ---}}
@endpush
