<?php
namespace App\Http\Controllers;

/**
 * @package App/Http/Controllers
 *
 * @class OccasionController
 *
 * @author Azim Khan <azim@surmountsoft.com>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use App\User;
use App\Profile;
use App\Occasion;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OccasionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } elseif (Auth::user()->type == "User" && session('current_type') == "User") {
                return redirect('profile');
            } elseif (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" ||
                    session('current_type') == "Artist")
            ) {
                $user = User::where('email', Auth::user()->email)->with('profile')->first();
                $occasions = Occasion::whereArtistProfileId($user->profile_id)->orderBy('id', 'DESC')->get();
                $occasionsData = [];
                $occasionsData['occasions'] = $occasions;
                $occasionsData['users'] = $user;
                $occasionsData['current_date'] = $date = date('d-m-Y');
                return view('frontend.artistDashboard.occasion.index', $occasionsData);
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            if (Auth::user()->type == "Admin" && session('current_type') == "Admin") {
                return redirect('admin_dashboard');
            } else if (Auth::user()->type == "Artist" || (Auth::user()->type == "Admin" || session('current_type') == "Artist")) {
                $artist = Profile::find(Auth::user()->profile_id);
                $artist_data['artist'] = $artist;
                return view('frontend.artistDashboard.occasion.create', $artist_data);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $occasion = new Occasion();
            $validator = $occasion->validator($data);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            Occasion::create($data);
        } catch (\Exception $error) {
            return redirect()->back()->withInput();
        }
        return redirect('addPrice');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $occasion = Occasion::find($id);
        $artist = Profile::find($occasion->artist_profile_id);
        return view('frontend.artistDashboard.occasion.edit', compact('occasion', 'artist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $occasion = new Occasion();
            $validator = $occasion->validator($data);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            Occasion::whereId($request->id)->update([
                'title' => $request->title,
                'price' => $request->price,
            ]);
        } catch (\Exception $error) {
            return redirect()->back()->withInput();
        }
        return redirect('addPrice');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     * @return mixed
     * @internal param $id
     */
    public function getOccasionPrice(Request $request)
    {
        $price = null;
        if ($request->id == 0) {
            $artist = Profile::find($request->artist_profile_id);
            $price = $artist->VideoPrice;
        } else {
            $occasion = Occasion::whereId($request->id)->whereArtistProfileId($request->artist_profile_id)->first();
            if (!is_null($occasion)) {
                $price = $occasion->price;
            }
        }
        return $price;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function delete($id)
    {
        try {
            $occasion = Occasion::find($id);
            $occasion->delete();
        } catch (\Exception $error) {
            return 500;
        }
        return 200;
    }
}
