<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order; // Added this to fetch order data
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Controller extends \Illuminate\Routing\Controller
{
    // --- BASIC NAVIGATION ---
    public function index(){
        return view('index');
    }

    public function showCustomerSignup() {
        return view('customer.cs_signup', ['role' => 'customer']);
    }

    public function showAdminSignup() {
        return view('admin.ad_signup', ['role' => 'admin']);
    }

    // --- CUSTOMER SIGNUP ---
    public function customerSignup(Request $request) 
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|min:10',
            'email'    => 'nullable|email|unique:users,email', // Optional for customers
            'password' => 'required|min:8|confirmed', 
        ]);

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('customer.cs_login')
            ->with('success', 'Account created! Welcome to Sweetflakes.');
    }

    // --- ADMIN ENROLLMENT ---
    public function adminSignup(Request $request) 
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string',
            'email'        => 'required|email|unique:users,email', // Mandatory for staff
            'password'     => 'required|min:8|confirmed',
            'admin_secret' => 'required',
        ]);

        // Verify Secret Key
        if ($request->admin_secret !== env('ADMIN_SETUP_CODE', 'SF_SECRET_2025')) {
            return back()->withErrors(['admin_secret' => 'Invalid staff authorization key.'])->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        Auth::login($user);

        return redirect()->route('admin.ad_login')
            ->with('success', 'Staff account created successfully!');
    }

    // --- LOGIN CUSTOMER ---
    public function showCustomerLogin() { return view('customer.cs_login'); }

    // --- LOGIN ADMIN ---
    public function showAdminLogin() { return view('admin.ad_login'); }

    // --- CUSTOMER LOGIN ---
    public function customerLogin(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Safety check: Ensure an admin isn't logging in through the customer portal
            if ($user->role === 'customer') {
                $request->session()->regenerate();
                return redirect()->route('customer.cs_welcome')->with('success', 'Logged in successfully! Welcome to Sweetflakes.');
            }

            // If an admin tries to login here, log them out and redirect
            Auth::logout();
            return back()->withErrors(['name' => 'Please use the Staff Portal for management accounts.']);
        }

        return back()->withErrors(['name' => 'Invalid artisan credentials.'])->onlyInput('name');
    }

    // --- ADMIN LOGIN ---
    public function adminLogin(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'name' => 'required|string',
            'admin_secret' => 'required',
        ]);

        // 2. Find the user by name first
        $user = User::where('name', $request->name)->first();

        // 3. Manual Verification
        if ($user->role === 'admin') {
            
            // Check if the provided secret matches the one in your .env
            if ($request->admin_secret === env('ADMIN_SETUP_CODE', 'SF_SECRET_2025')) {
                
                // Log the user in manually
                Auth::login($user);
                
                $request->session()->regenerate();
                return redirect()->route('admin.ad_dashboard')
                    ->with('success', 'Welcome back to the Command Center.');
            }

            return back()->withErrors(['admin_secret' => 'Invalid staff authorization key.'])->withInput();
        }

        return back()->withErrors(['name' => 'Staff record not found.'])->withInput();
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Protects against session fixation
        return redirect()->route('admin.ad_login')->with('success', 'Logged out safely.');
    }

    public function customerLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Protects against session fixation
        return redirect()->route('customer.cs_login')->with('success', 'Logged out safely.');
    }
}