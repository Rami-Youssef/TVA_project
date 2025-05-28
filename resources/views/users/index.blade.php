@extends('layouts.app', ['page' => __('Utilisateurs'), 'pageSlug' => 'users'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Utilisateurs</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('user.create') }}" class="btn btn-success"><i class="fas fa-plus"></i></a>
                        </div>
                    </div>                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('user.index') }}" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par nom ou email" value="{{ $search ?? '' }}">
                        </div>
                        <div class="form-group mr-2">
                            <select name="role_filter" class="form-control">
                                <option value="all">Tous les Rôles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ ($role_filter ?? '') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-default">Filtrer</button>
                        <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary ml-2">Réinitialiser</a>
                    </form>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="row">
                        <div class="col-12 text-right">
                            <div class="dropdown">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="tim-icons icon-cloud-download-93 mr-1"></i> Exporter
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                                    <h6 class="dropdown-header">Tous les utilisateurs</h6>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('users.export.pdf', ['search' => request('search'), 'role_filter' => request('role_filter', 'all')]) }}">
                                        <i class="tim-icons icon-paper mr-2"></i> Format PDF
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('users.export.excel', ['search' => request('search'), 'role_filter' => request('role_filter', 'all')]) }}">
                                        <i class="tim-icons icon-chart-bar-32 mr-2"></i> Format Excel
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">Page courante uniquement</h6>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('users.export.current.pdf', ['search' => request('search'), 'role_filter' => request('role_filter', 'all'), 'page' => $users->currentPage()]) }}">
                                        <i class="tim-icons icon-paper mr-2"></i> Format PDF
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('users.export.current.excel', ['search' => request('search'), 'role_filter' => request('role_filter', 'all'), 'page' => $users->currentPage()]) }}">
                                        <i class="tim-icons icon-chart-bar-32 mr-2"></i> Format Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <table class="table tablesorter" id="">                            <thead class="text-primary">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Creation Date</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>                                        <td>
                                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $user->email }}" target="_blank">{{ $user->email }}</a>
                                        </td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>                                        <td class="text-center">
                                            <div class="d-flex justify-content-center" style="gap: 0.5rem;">
                                                @if (auth()->user()->id != $user->id)
                                                    <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('user.edit', $user) }}'">
                                                        Modifier
                                                    </button>
                                                    <form action="{{ route('user.delete', $user) }}" method="post" style="display: inline;">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('profile.edit') }}'">
                                                        Modifier
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-center" aria-label="...">
                        {{ $users->appends(request()->except('page'))->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection