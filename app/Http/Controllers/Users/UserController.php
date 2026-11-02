<?php

namespace App\Http\Controllers\Users;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request): view
    {
        // Retrieve users, filtered by the options chosen on the page
        $users = User::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = '%' . $request->search . '%';
                $q->where(fn ($q) => $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('id', 'like', $search)
                    ->orWhere('phone', 'like', $search));
            })
            // Filter by role and status if those filters are applied
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'active'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
            // Pass the users to the view
        return view('users.index', compact('users'));
    }
    public function all(Request $request): view
    {
        // Retrieve all users without any filters
        $users = User::all();
        // Pass the users to the view
        return(view('users.all', compact('users')));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form to create a new user
        return view('users.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // Validate and create a new user record
        $data = $request->validated();
        // Set the role to user and hash the password before saving
        $data['password'] = bcrypt($data['password']);
        // Create the user and redirect to the user's show page with a success message
        $user = User::create($data);
        // Redirect to the user's show page with a success message
        return redirect()->route('users.show', $user)->with('success', 'User created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Get the user
        $user = User::findOrFail($id);
        // Redirect to the user's show page with a success message
        return view('users.show', compact('user'));
    }
    public function toggleStatus($id): RedirectResponse
    {
        // Flip the user between active and inactive
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "{$user->name} {$status}.");
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        // If password is provided, hash it; otherwise keep the current one
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('users.show', $user)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
