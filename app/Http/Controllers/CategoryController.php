<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests;
use DB;
use App\Admin;
use App\Video;
use App\User;
use App\Slider;
use App\Profile;
use App\Payment;
use App\Requestvideo;
use App\RequestedVideo;
use App\Testimonial;
use Auth;
use Carbon\Carbon;
use Validator;
use Hash;
use Mail;
use Session;
use Crypt;
use Snipe\BanBuilder\CensorWords;

class CategoryController extends Controller {

    public function __construct() {
        $this->middleware('user_active');
        $this->middleware('revalidate');
    } 

     
// CategoryController created and the code given below by sandeep
     /* ------------------------Category Delete--------------------- */

    public function delete_category($id) {


        //$videourl[]=$videos_dtl->VideoURL;
        //$videoThumbnail[]=$videos_dtl->VideoThumbnail;
        //$originalVideoUrl[]=$videos_dtl->originalVideoUrl;
        //dd($videos_dtl);
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                 $cat = DB::table('artist_category')->where('category_id', $id)->get();

                    if (count($cat) > 0) {
 
                return redirect('categories')->with('error', "Can't remove Category An artist is associated in this category.");
            } else {
                DB::table('category')->where('id', $id)->delete();
               }
                //return view('admin.artist_list',);
                return redirect('categories')->with('success', "Category removed successfully");
            }
        }
    }

  /* ------------------------view categories list--------------------- */

    public function view_categories_list($id) { 

    $categories = DB::table('category')->get();

    $categories_artist_list = DB::table('profiles')
     ->join('artist_category','profiles.ProfileId','=','artist_category.profile_id')
     ->join('category','category.id','=','artist_category.category_id')
     ->where('artist_category.category_id', $id)->get();
       
    $artist_data['categories'] = $categories;
    $artist_data['categories_artist_list'] = $categories_artist_list;
    if (count($categories_artist_list) <= 0) {
            //dd($categories_artist_list);
    return redirect('admin.view_categories_list');
            } 
            else{

                     // dd($categories_artist_list);
                     return view('admin.view_categories_list',$artist_data);
                
            }
              
            
        
    }

    /* --------------------Update Artist Account---------------- */

    
    /* -----------------------------------Category Update------------------------------------- */

    public function get_edit_category($id) {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin") {
                $categoryData = DB::table('category')
                        ->where('id', $id)->first();
                $catData['catData'] = $categoryData;
              
               //dd($categoryData);
                return view('admin.get_edit_category', $catData);
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                // $userData = User::where('email', Auth::user()->email)->first();
                // $profileData = Profile::where('EmailId', Auth::user()->email)->first();
                // $artistData['userData'] = $userData;
                // $artistData['profileData'] = $profileData;
                // $artistData['baseurl'] = "https://www.videorequestline.com/";
                // return view('frontend.artistDashboard.ProfileUpdate', $artistData);
            }
        } else {
            return redirect('admin');
        }
    }


    
        /* ___________________________________________________________ */

    public function edit_category(Request $request) {
        $data = $request->all();
        $messages = [
            'required' => 'The :attribute field is required.',
            'title.required' => 'The Category Title field is required.'
        ];

        $validator = Validator::make($data, array(
                    'title' => 'required',
                    // 'description' => 'required',
                        ), $messages
        );
        if ($validator->fails()) {
            return redirect('/edit_category/' . $request->Id)
                            ->withErrors($validator)
                            ->withInput();
        } else {
                //$file = $request->file('category');
                //$filename = $file->getClientOriginalName();
                //$profile_path = "images/categories/" . date('U') . '.jpg';
                //$destinationPath = base_path() . '/public/images/categories/';
                //$request->file('category')->move($destinationPath, $profile_path);
                //
                $string = strip_tags($request->title);
                if(strlen($string) < 80)
                {
                    $title = DB::table('category')->where('title', $request->title)->where('id','!=', $request->Id)->first();
                    if($title!=null)
                    {
                      return redirect('edit_category/' . $request->Id)->with('error', 'Category Title Already Exist!');  
                    }
                    else
                    {   
                         DB::table('category')->where('id', $request->Id)->update(['title' => $request->title]);
                        
                        return redirect('edit_category/' . $request->Id)->with('success', 'Successfully Updated!');
                        
                    }
                    
                }
                else
                {
                    return redirect('edit_category/' . $request->Id)->with('error', 'Category Title Length must be less than 80 words!');
                }
                
    }
}


  
     /* ---------------------------------------Category List--------------------------------------- */

    public function category_list() {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('Dashboard');
            } elseif (Auth::user()->type == "User") {
                return redirect('profile');
            } else {
                $categories = DB::table('category')->orderBy('id','asc')->get();
                //dd($categories);
                return view('admin.category_list', ['categories' => $categories]);
            }
        } else {
            return redirect('/');
        }
    }

   
/* ---------------------------------------category Disable--------------------------------------- */

    public function disable_category($category_id) {
        if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('Dashboard');
            } elseif (Auth::user()->type == "User") {
                 return redirect('profile');
            } else {
                
                 $cat = DB::table('artist_category')->where('category_id', $category_id)->get();

                    if (count($cat) > 0) {
 
                return redirect('categories')->with('error', "Can't deactivated Category An artist is associated in this category.");
            } else {

                     $category = DB::table('category')
                        ->where('id', $category_id)
                        ->update(array('status' => '0'));
                   
                  return redirect('categories')->with('success', 'Category deactivated successfully');
                        }
                    }
                }
          
          else {
         return redirect('/');
     }
    }


  
 /* ---------------------------------------Artist Enable--------------------------------------- */

    public function enable_category($category_id) {
       if (Auth::check()) {
            if (Auth::user()->type == "Artist") {
                return redirect('Dashboard');
            } elseif (Auth::user()->type == "User") {
                 return redirect('profile');
            } else {

                     $category = DB::table('category')
                        ->where('id', $category_id)
                        ->update(array('status' => '1'));
                   
                  return redirect('categories')->with('success', 'Category activated successfully');
                       
                    }
                }
          
          else {
         return redirect('/');
     }
    }


    public function post_create_category(Request $request) { 
        $messages = [
            'required' => 'The :attribute field is required.',
            'title.required' => 'The Category Title field is required.'
        ];
        $validator = Validator::make(
                        array(
                    'title' => $request->title,
                   
                        ), array(
                    
                    'title' => 'required',
                   
                        ), $messages
        );
        if ($validator->fails()) {
            return redirect('/create_category')
                            ->withErrors($validator)
                            ->withInput();
        } else {

            $string = strip_tags($request->title);
            if(strlen($string) < 80)
            {
                $title = DB::table('category')->where('title', $request->title)->first();
           
                if ($title!=null) {
                    return redirect('create_category')->with('error', "Category Title Already Exist!");
                } else {

                    /*$file = $request->file('category');
                    $filename = $file->getClientOriginalName();
                    $profile_path = "images/categories/" . date('U') . '.jpg';
                    $destinationPath = base_path() . '/public/images/categories/';
                    $request->file('category')->move($destinationPath, $profile_path);*/                           
                    //DB::table('category')->insert(
                            //array('title' => $request->title, 'description' => $request->description,'category_img'=>$profile_path));
                    DB::table('category')->insert(
                            array('title' => $request->title));
                    return redirect('create_category')->with('success', 'Category Created Successfully!');

                }
                            }
            else
            {
                return redirect('create_category')->with('error', "Category Title Length must be less than 80 words!"); 
            }
            
        }
    }


    public function create_category()
    {
        if (Auth::user()->type == "Admin") {
            return view('admin.create_category');
        } else {
            return redirect('/');
        }
    }
}
