<?php
/**
 * HomeController
 */

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Theme;

class DashboardController extends Controller {
       
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    public function index() {
        
        return view('backend.dashboard.index', [
            'totalUser'  => User::all()->count(),
            'totalTheme' => Theme::all()->count()
        ]);
    }
    
}