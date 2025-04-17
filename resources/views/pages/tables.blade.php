@extends('layouts.app', ['page' => __('Tables'), 'pageSlug' => 'tables'])
{
  $users = 
}
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <h4 class="card-title"> Tableau d'utilisateur</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table tablesorter " id="">
            <thead class=" text-primary">
              <tr>
                <th>
                  id
                </th>
                <th>
                  Name
                </th>
                <th>
                  email
                </th>
                <th>
                  role
                </th>
                <th class="text-center">
                  Action
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <td>
                   {{ $user->id }}
                </td>
                <td>
                  {{ $user->name }}
                </td>
                <td>
                  {{ $user->email }}
                </td>
                <td class="text-center">
                  {{ $user->role }}
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
