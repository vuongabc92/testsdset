<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserController extends Controller {
     
    public $user;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user) {
        $this->user = $user;
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    public function index(Request $request) {
        $maxPerPage = config('backend.pagination.max_per_page');
        $filter     = $request->query();
        
        if (count($filter)) {
            $users = $this->user;
            
            if (isset($filter['q']) && $filter['q'] !== '') {
                $users = $users->join('user_profile', 'user_profile.user_id', '=', 'users.id')
                               ->where(function($query) use($filter) {
                                   $query->where('user_profile.slug', 'like',  '%' . $filter['q'] . '%')
                                         ->orWhere('users.email', 'like', '%' . $filter['q'] . '%')
                                         ->orWhere('users.username', 'like', '%' . $filter['q'] . '%');
                               });
                      
            }
            
            if (isset($filter['status']) && $filter['status'] !== '') {
                $users = $users->where('users.activated', $filter['status']);
            }
            
            $users = $users->select('users.id', 'users.username', 'users.email', 'users.activated')->paginate($maxPerPage);
            
            if (isset($filter['q'])) {
                $users->appends(['q' => $filter['q']]);
            }
            
            if (isset($filter['status'])) {
                $users->appends(['status' => $filter['status']]);
            }
            
        } else {
            $users = User::paginate($maxPerPage);
        }
        
        return view('backend.users.index', [
            'users'      => $users,
            'maxPerPage' => $maxPerPage,
            'filterQ'    => isset($filter['q'])      ? $filter['q']      : null,
            'filterStat' => isset($filter['status']) ? $filter['status'] : null,
        ]);
    }
    
    public function view($id) {
        
        $user = User::find($id);
        
        if (null === $user) {
            return redirect()->back();
        }
        
        return view('backend.users.view',[
            'user' => $user
        ]);
    }
    
    public function updateStatus(Request $request) {
        $userId = (int) $request->get('user_id');
        $user   = User::find($userId);
        
        if (null === $user) {
            return redirect()->back();
        }
        
        if ($user->activated) {
            $user->activated = 0;
        } else {
            $user->activated = 1;
        }
        
        $user->save();
        
        return redirect()->back();
    }

}