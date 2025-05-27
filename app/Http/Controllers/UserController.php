<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Exports\UserCurrentPageExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // Or your preferred PDF library

class UserController extends Controller
{
    
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }
    /**
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role'),
        ]);
        return redirect()->route('user.getAllUsers')->withStatus(__('User successfully created.'));
    }
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findorFail($id);
        return view('users.userEdit', compact('user'));
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated()); // Use validated data from UserRequest
        return redirect()->route('user.getAllUsers')->withStatus(__('User successfully updated.'));
    }


    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request, User $user)
    {
        $user->update(['password' => Hash::make($request->get('password'))]); 
        return redirect()->route('user.getAllUsers')->withStatus(__('User password successfully updated.'));
    }

    public function destroy(Request $request, User $user)
    {
        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }
    
        $user->delete();
        return redirect()->route('user.getAllUsers')->withStatus(__('User successfully deleted.'));
    }

    private function getFilteredUsersQuery(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role_filter');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter && $roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }
        
        return $query->orderBy('created_at', 'desc');
    }

    /**get all users*/
    public function getAllUsers(Request $request)
    {
        return $this->index($request);
    }
    
    /**
     * Display a listing of users with filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $this->getFilteredUsersQuery($request);
        $users = $query->paginate(10);

        // Append all current filters to pagination links
        $users->appends($request->all());
        
        // Get distinct roles for the filter dropdown
        $roles = User::select('role')->distinct()->pluck('role');

        return view('users.index', [
            'users' => $users,
            'search' => $request->input('search'),
            'role_filter' => $request->input('role_filter'),
            'roles' => $roles
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = $this->getFilteredUsersQuery($request);
        $users = $query->get();
        
        $pdf = Pdf::loadView('users.pdf', compact('users'));
        return $pdf->download('users-'.date('Y-m-d_H-i-s').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role_filter');

        return Excel::download(new UsersExport($search, $roleFilter), 'users-'.date('Y-m-d_H-i-s').'.xlsx');
    }
    
    public function exportCurrentPagePdf(Request $request)
    {
        $query = $this->getFilteredUsersQuery($request);
        $page = $request->input('page', 1);
        $perPage = 10; // Same as in index method
        
        // Get just the users from the current page
        $users = $query->paginate($perPage, ['*'], 'page', $page);
        
        $pdf = Pdf::loadView('users.pdf', ['users' => $users]);
        return $pdf->download('users-page-'.$page.'-'.date('Y-m-d_H-i-s').'.pdf');
    }

    public function exportCurrentPageExcel(Request $request)
    {
        // First, get the filtered query
        $query = $this->getFilteredUsersQuery($request);
        
        // Get the pagination parameters
        $page = (int) $request->input('page', 1);
        $perPage = 10; // Same as in index method
        
        // Get just the IDs of users on the current page
        $paginatedUsers = $query->paginate($perPage, ['id'], 'page', $page);
        $userIds = $paginatedUsers->pluck('id')->toArray();
        
        // Log for debugging
        \Log::info('Exporting current page Excel', [
            'page' => $page,
            'perPage' => $perPage,
            'count' => count($userIds),
            'userIds' => $userIds
        ]);

        // Create a new instance of UserCurrentPageExport with the specific user IDs
        return Excel::download(
            new UserCurrentPageExport($userIds), 
            'users-page-'.$page.'-'.date('Y-m-d_H-i-s').'.xlsx'
        );
    }
}
