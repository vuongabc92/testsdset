<?php
namespace App\Http\Controllers\Frontend;

use App\Models\Theme;
use App\Models\Page;

class IndexController extends Controller {
       
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    public function index() {
        $perPage    = config('frontend.lazy_loading.per_page');
        $configSlug = config('backend.page.slug.home');
        $page       = Page::where('slug', $configSlug)->first();
        $themes     = Theme::where('activated', 1)->paginate($perPage);
        $themeCount = Theme::where('activated', 1)->skip(0)->take($perPage + 1)->count();
         
        return view('frontend.index.index', [
            'themes'   => $themes,
            'page'     => $page,
            'isPaging' => $themeCount > $themes->count()
        ]);
    }
    
    public function contact() {
        $configSlug = config('backend.page.slug.contact');
        $contact    = Page::where('slug', $configSlug)->first();
        
        return view('frontend.index.contact', [
            'page' => $contact
        ]);
    }
    
    public function developer() {
        $configSlug = config('backend.page.slug.developer');
        $developer  = Page::where('slug', $configSlug)->first();
        
        return view('frontend.index.developer', [
            'page' => $developer
        ]);
    }
    
    public function privacyPolicy() {
        $configSlug = config('backend.page.slug.privacy');
        $page       = Page::where('slug', $configSlug)->first();
        
        return view('frontend.index.privacy', [
            'page' => $page
        ]);
    }
    
    public function termsAndConditions() {
        $configSlug = config('backend.page.slug.terms');
        $page       = Page::where('slug', $configSlug)->first();
        
        return view('frontend.index.terms', [
            'page' => $page
        ]);
    }
    
}