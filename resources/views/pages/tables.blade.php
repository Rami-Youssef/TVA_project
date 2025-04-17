@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'tables'])

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <h4 class="card-title"> Tableau d'utilisateur</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table tablesorter" id="">
            <thead class="text-primary">
              <tr>
                <th>id</th>
                <th>Name</th>
                <th>email</th>
                <th>role</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td class="text-center">
                  <div class="d-flex justify-content-center" style="gap: 0.5rem;">
                    <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('user.edit', $user->id) }}'">Edit</button>


                    <button class="btn btn-danger btn-sm"  onclick="window.location.href='{{ route('user.delete', $user->id) }}'">Delete</button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
      </div>
    </div>
  </div>
</div>
@endsection
