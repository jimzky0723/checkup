<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Excel;

class ExcelCtrl extends Controller
{
    public function importExcel(Request $req){
        $path = $req->file('import_file')->getRealPath();
        $excelData = Excel::load($path)->get();

        foreach($excelData as $row){
            if(!empty($row)){
                $dateNow = date('Y-m-d H:i:s');
                $user = Auth::user();
                $fname = ($req->fname);
                $mname = ($req->mname);
                $lname = ($req->lname);
                $unique_id = $fname.''.$mname.''.$lname.''.$req->suffix.''.$req->barangay.''.$user->muncity;

                //CAPITOL
                $height = addslashes($req->height);
                $weight = $req->weight;
                $bloodType = $req->bloodType;
                $address = $req->address;
                $chphs_mun_no = Muncity::find($user->muncity)->chphs_mun_no;
                $chphs_brg_no = Barangay::where("province_id","=",$user->province)->where("muncity_id","=",$user->muncity)->where("id","=",$req->barangay)->first()->chphs_brg_no;
                $chphs_no = $req->cluster.'-'.$req->district.'-'.$chphs_mun_no.'-'.$chphs_brg_no.'-'.strtotime($dateNow).'-'.$user->id;
                $chphs_status = $req->chphs_status;
                //

                $q = "INSERT INTO profile(
                unique_id,
                familyID,
                head,
                relation,
                fname,
                mname,
                lname,
                suffix,
                dob,
                sex,
                unmet,
                barangay_id,
                muncity_id,
                province_id,
                created_at,
                updated_at,
                phicID,
                nhtsID, 
                education,
                height,
                weight,
                blood_type,
                address,
                chphs_no,
                chphs_status
                )
                VALUES(
                '$unique_id',
                '$req->familyID',
                'NO', 
                '$req->relation',
                '".$fname."',
                '".$mname."',
                '".$lname."',
                '$req->suffix',
                '".date('Y-m-d',strtotime($req->dob))."',
                '$req->sex','$req->unmet',
                '$req->barangay',
                '$user->muncity',
                '$user->province',
                '$dateNow',
                '$dateNow',
                '$req->phicID',
                '$req->nhtsID',
                '$req->education',
                '$height',
                '$weight',
                '$bloodType',
                '$address',
                '$chphs_no',
                '$chphs_status'
                )
            ON DUPLICATE KEY UPDATE
                familyID = '$req->familyID',
                sex = '$req->sex',
                relation = '$req->relation',
                education = '$req->education',
                unmet = '$req->unmet'
            ";
                DB::select($q);

                $q = "INSERT IGNORE profile_device(profile_id,device) values(
                '$unique_id',
                'web'
            )";
                DB::select($q);

                $q = "INSERT IGNORE servicegroup(profile_id,sex,barangay_id,muncity_id) VALUES(
                '$unique_id',
                '$req->sex',
                '$req->barangay',
                '$user->muncity'
            )";
                $db = 'db_'.date('Y');

                DB::connection($db)->select($q);
            }
        }
        return $excelData;
    }
}
