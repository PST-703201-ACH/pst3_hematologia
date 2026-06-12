@extends('layouts.admin')

@section('title', 'Dashboard | HireDesk Laravel')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard</h1>
            <p class="text-muted mb-0">Welcome to HireDesk Laravel job board starter.</p>
        </div>

        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>0</h3>
                    <p>Total Jobs</p>
                </div>

                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>

                <a href="#" class="small-box-footer">
                    View Jobs <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>0</h3>
                    <p>Applications</p>
                </div>

                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>

                <a href="#" class="small-box-footer">
                    View Applications <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>0</h3>
                    <p>Employers</p>
                </div>

                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>

                <a href="#" class="small-box-footer">
                    View Employers <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>0</h3>
                    <p>Applicants</p>
                </div>

                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>

                <a href="#" class="small-box-footer">
                    View Applicants <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Recent Jobs</h3>

                    <div class="card-tools">
                        <a href="#" class="btn btn-tool btn-sm">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>Senior Laravel Developer</td>
                            <td>Remote Tech Inc.</td>
                            <td>
                                <span class="badge badge-primary">Remote</span>
                            </td>
                            <td>
                                <span class="badge badge-success">Published</span>
                            </td>
                        </tr>

                        <tr>
                            <td>PHP Backend Engineer</td>
                            <td>CloudWorks Ltd.</td>
                            <td>
                                <span class="badge badge-info">Full-time</span>
                            </td>
                            <td>
                                <span class="badge badge-warning">Draft</span>
                            </td>
                        </tr>

                        <tr>
                            <td>Laravel API Developer</td>
                            <td>Global SaaS Studio</td>
                            <td>
                                <span class="badge badge-primary">Remote</span>
                            </td>
                            <td>
                                <span class="badge badge-success">Published</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <a href="#" class="btn btn-sm btn-primary float-right">
                        View All Jobs
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Phase 1 Checklist</h3>
                </div>

                <div class="card-body">
                    <ul class="todo-list" data-widget="todo-list">
                        <li>
                            <span class="handle">
                                <i class="fas fa-check text-success"></i>
                            </span>
                            <span class="text">Laravel project created</span>
                        </li>

                        <li>
                            <span class="handle">
                                <i class="fas fa-check text-success"></i>
                            </span>
                            <span class="text">AdminLTE 3.2.0 integrated</span>
                        </li>

                        <li>
                            <span class="handle">
                                <i class="fas fa-check text-success"></i>
                            </span>
                            <span class="text">Reusable Blade layout added</span>
                        </li>

                        <li>
                            <span class="handle">
                                <i class="fas fa-check text-success"></i>
                            </span>
                            <span class="text">Dashboard page created</span>
                        </li>

                        <li>
                            <span class="handle">
                                <i class="fas fa-check text-success"></i>
                            </span>
                            <span class="text">No Vite dependency used</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Next Phase</h3>
                </div>

                <div class="card-body">
                    <p class="mb-2">
                        Phase 2 will add authentication, login, registration, logout, and protected dashboard access.
                    </p>

                    <a href="#" class="btn btn-dark btn-block disabled">
                        Phase 2 Coming Next
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection