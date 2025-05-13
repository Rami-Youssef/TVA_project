@extends('layouts.app', ['page' => __('Users'), 'pageSlug' => 'users'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Users</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">Add user</a>
                        </div>
                    </div>                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('user.index') }}" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ $search ?? '' }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="role_filter" class="form-control">
                                <option value="all">All Roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ ($role_filter ?? '') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-default">Filter</button>
                        <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary ml-2">Reset</a>
                    </form>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="row">
                        <div class="col-12 text-right">
                                <div class="btn-group">
                                    <a href="{{ route('users.export.pdf', ['search' => request('search'), 'role_filter' => request('role_filter', 'all')]) }}" class="btn btn-sm btn-info">
                                        <i class="tim-icons icon-paper"></i> PDF
                                    </a>
                                    <a href="{{ route('users.export.excel', ['search' => request('search'), 'role_filter' => request('role_filter', 'all')]) }}" class="btn btn-sm btn-success">
                                        <i class="tim-icons icon-chart-bar-32"></i> Excel
                                    </a>
                                </div>
                            </div>
                    </div>

                    <div class="">
                        <table class="table tablesorter" id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Creation Date</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                        </td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        @if (auth()->user()->id != $user->id)
                                                            <form action="{{ route('user.delete', $user) }}" method="post">
                                                                @csrf
                                                                @method('delete')

                                                                <a class="dropdown-item" href="{{ route('user.edit', $user) }}">Edit</a>
                                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a class="dropdown-item" href="{{ route('profile.edit') }}">Edit</a>
                                                        @endif
                                                    </div>
                                                </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{ $users->appends(request()->except('page'))->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection