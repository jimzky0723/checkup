<?php

namespace App\Http\Controllers;

use App\Barangay;
use App\Muncity;
use App\Profile;
use App\Province;
use App\UserBrgy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChphsCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user_priv');
    }

    public function population(){
        $keyword = Session::get('profileKeyword');
        $muncity = Session::get('profileMuncity');
        $user = Auth::user();
        $profiles = Profile::select('profile.unique_id','profile.familyID','profile.created_at','profile.head','profile.id','profile.lname', 'profile.mname','profile.fname',
                            'profile.suffix','profile.dob','profile.sex','profile.barangay_id','profile.muncity_id','profile.province_id','profile.blood_type','profile.height',
                            'profile.weight','profile.contact_no','profile.house_no','profile.street_name','profile.sitio','profile.purok','profile.chphs_no')
                            ->where('profile.chphs_status','=','yes');

        if($keyword || $keyword!='' || $keyword!=null)
        {
            $profiles = $profiles->where(function($q) use ($keyword){
                $q->where('profile.fname','like',"%$keyword%")
                    ->orwhere('profile.mname','like',"%$keyword%")
                    ->orwhere('profile.lname','like',"%$keyword%")
                    ->orwhere('profile.familyID','like',"%$keyword%");
            });
        }

        $profiles = $profiles->where('profile.province_id',$user->province);
        if(!empty($muncity)){
            $profiles = $profiles->where('profile.muncity_id',$muncity);
        }
        $profiles = $profiles->where('profile.id','>',0)
            ->orderBy('profile.id','desc');

        //CHPHS print
        $temp = $profiles;
        $data = [];
        foreach($temp->get() as $row){
            $chphs_no = $row->chphs_no;
            $clusterNo = '';
            $districtNo = '';
            $mlguNo = '';
            $brgyNo = '';
            $no = '';

            if(isset(explode('-',$chphs_no)[0]))
                $clusterNo = explode('-',$chphs_no)[0];
            if(isset(explode('-',$chphs_no)[1]))
                $districtNo = explode('-',$chphs_no)[1];
            if(isset(explode('-',$chphs_no)[2]))
                $mlguNo = explode('-',$chphs_no)[2];
            if(isset(explode('-',$chphs_no)[3]))
                $brgyNo = explode('-',$chphs_no)[3];
            if(isset(explode('-',$chphs_no)[4]))
                $no = explode('-',$chphs_no)[4];

            $house_no = '';
            $street_name = '';
            $sitio = '';
            $purok = '';
            $barangay = '';
            $municipality = '';
            $province = '';
            if(!empty($row->house_no))
                $house_no = $row->house_no;

            if(!empty($row->street_name)){
                if(!empty($row->house_no)){
                    $street_name = ','.$row->street_name;
                } else {
                    $street_name = $row->street_name;
                }
            }
            if(!empty($row->sitio)){
                if(!empty($row->house_no) || !empty($row->street_name)){
                    $sitio = ','.$row->sitio;
                } else {
                    $sitio = $row->sitio;
                }
            }
            if(!empty($row->purok)){
                if(!empty($row->house_no) || !empty($row->street_name) || !empty($row->sitio)){
                    $purok = ','.$row->purok;
                } else {
                    $purok = $row->purok;
                }
            }
            if(isset(Barangay::find($row->barangay_id)->description)){
                if(!empty($row->house_no) || !empty($row->street_name) || !empty($row->sitio) || !empty($row->purok)){
                    $barangay = ','.Barangay::find($row->barangay_id)->description;
                } else {
                    $barangay = Barangay::find($row->barangay_id)->description;
                }
            }

            if(isset(Muncity::find($row->province_id)->description)) {
                if(!empty($row->house_no) || !empty($row->street_name) || !empty($row->sitio) || !empty($row->purok) || isset(Barangay::find($row->barangay_id)->description)){
                    $municipality = ','.Muncity::find($row->muncity_id)->description;
                } else {
                    $municipality = Muncity::find($row->muncity_id)->description;
                }
            }
            if(isset(Province::find($row->province_id)->description)){
                if(!empty($row->house_no) || !empty($row->street_name) || !empty($row->sitio) || !empty($row->purok) || isset(Barangay::find($row->barangay_id)->description) || isset(Province::find($row->province_id)->description) ){
                    $municipality = ','.Province::find($row->province_id)->description;
                } else {
                    $municipality = Province::find($row->province_id)->description;
                }
            }

            $data[] = [
                "clusterNo" => $clusterNo,
                "districtNo" => $districtNo,
                "mlguNo" => $mlguNo,
                "brgyNo" => $brgyNo,
                "no" => $no,
                "fname" => $row->fname,
                "lname" => $row->lname,
                "mname" => $row->mname,
                "dob" => date('M d, Y',strtotime($row->dob)),
                "sex" => $row->sex,
                "weight" => $row->weight,
                "height" => $row->height,
                "bloodType" => $row->blood_type,
                "contact_no" => $row->contact_no,
                "address" => $house_no.
                            ','.$street_name.
                            ','.$sitio.
                            ','.$purok.
                            ','.$barangay.
                            ','.$municipality.
                            ','.$province,
                "chphs_no" => $row->chphs_no
            ];
        }
        session_start();
        $_SESSION['data'] = $data;
        //

        $profiles = $profiles->paginate(15);
        return view('chphs.population',['profiles' => $profiles]);
    }

    public function searchPopulation(Request $req){
        if($req->viewAll){
            Session::forget('profileKeyword');
            Session::forget('profileMuncity');
            return redirect()->back();
        }

        Session::put('profileKeyword',$req->keyword);
        Session::put('profileMuncity',$req->muncity);
        return self::population();
    }

}