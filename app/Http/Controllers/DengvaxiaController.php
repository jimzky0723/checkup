<?php

namespace App\Http\Controllers;

use App\Dengvaxia;
use App\Profile;
use App\Province;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf;
use Illuminate\Support\Facades\Session;

class DengvaxiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function verify_dengvaxia($id){
        $tsekap = Profile::find($id);
        $dengvaxia = Dengvaxia::where("unique_id","=",$tsekap->unique_id)->first();

        return view('dengvaxia.verify_dengvaxia',[
            "unique_id" => $tsekap->unique_id,
            "dengvaxia" => $dengvaxia,
            "tsekap_id" => $tsekap->id
        ]);
    }

    public function form_dengvaxia($dengvaxiaID,$unique_id,$tsekap_id){
        $dengvaxia = Dengvaxia::find($dengvaxiaID);

        return view('dengvaxia.form_dengvaxia',[
            "dengvaxia" => $dengvaxia,
            "unique_id" => $unique_id,
            "tsekap_id" => $tsekap_id
        ]);
    }

    public function form_dengvaxia_add($unique_id,$tsekap_id){
        $dengvaxia = \DB::connection('dengvaxia_dummy')->getSchemaBuilder()->getColumnListing('dengvaxia_profiles');
        $tsekap = Profile::where('unique_id',"=",$unique_id)->first();

        $object = new \stdClass();
        foreach($dengvaxia as $key){
            if($key == "unique_id" || $key == "lname" || $key == "fname" || $key == "mname" || $key == "suffix" || $key == "head" || $key == "dob" || $key == "sex" || $key == "barangay_id" || $key == "muncity_id" || $key == "province_id" || $key == "education"){
                $object->$key = $tsekap->$key;
            } else {
                $object->$key = "";
            }
        }

        return view('dengvaxia.form_dengvaxia',[
            "dengvaxia" => $object,
            "unique_id" => $unique_id,
            "tsekap_id" => $tsekap_id
        ]);
    }

    public function post_dengvaxia(Request $request,$dengvaxiaID,$unique_id,$tsekap_id){
        strpos($request->gen_reli, 'Others') !== false ? $religion = $request->gen_reli.' - '.$request->gen_reli_oth : $religion = $request->gen_reli;

        if(isset($request->phic_sponsoredby)){
            if(strpos($request->phic_sponsoredby, 'Sponsored') !== false){
                if($request->phic_sponsored == "Others"){
                    $phic_type = $request->phic_sponsoredby.' '.$request->phic_sponsored.' - '.$request->phic_sponsored_others;
                } else {
                    $phic_type = $request->phic_sponsoredby.' '.$request->phic_sponsored;
                }
            }
        } else {
            $phic_type = "";
        }

        if(isset($request->phic_ben)){
            if($request->phic_ben == "Yes"){
                $phic_ben = $request->phic_ben.' - '.$request->phic_ben_spe;
            }
            else if($request->phic_ben == "No") {
                $phic_ben = $request->phic_ben;
            }
        } else {
            $phic_ben = "";
        }

        $phic_membership = json_encode([
            "status" => $request->phic_status,
            "type" => $phic_type,
            "employment" => $request->phic_employed,
            "benefit" => $phic_ben
        ]);

        if(isset($request->fam_his)){
            foreach($request->fam_his as $row){
                if(isset($request->$row)){
                    $fam_concat = $request->$row;
                } else {
                    $fam_concat = '';
                }
                $fam_his[$row] = $row.' - '.$fam_concat;
            }
            $family_history = json_encode($fam_his);
        } else {
            $family_history = "";
        }

        if(isset($request->med_his)){
            foreach($request->med_his as $row){
                if(isset($request->$row)){
                    $med_concat = $request->$row;
                } else {
                    $med_concat = '';
                }
                $med_his[$row] = $row.' - '.$med_concat;
            }
            $medical_history = json_encode($med_his);
        } else {
            $medical_history = "";
        }

        $request->with_medication == "Yes" ? $with_medication = $request->with_medication.' - '.$request->with_medication_spe : $with_medication = $request->with_medication;
        $bronchial_asthma = json_encode([
            "diagnosed" => $request->diagnosed,
            "no_attacks" => $request->no_attacks,
            "with_medication" => $with_medication,
        ]);

        if(isset($request->Any_Following )){
            foreach($request->Any_Following as $row){
                if(isset($request->$row)){
                    $any_following_concat = $request->$row;
                } else {
                    $any_following_concat = '';
                }
                $Any_Following[$row] = $row.' - '.$any_following_concat;
            }
        } else {
            $Any_Following = "";
        }

        if(isset($request->Labs_Done)){
            foreach($request->Labs_Done as $row){
                if(isset($request->$row)){
                    $labs_done_concat = $request->$row;
                } else {
                    $labs_done_concat = '';
                }
                $Labs_Done[$row] = $row.' - '.$labs_done_concat;
            }
        } else {
            $Labs_Done = "";
        }

        if(isset($request->Medications)){
            foreach($request->Medications as $row){
                $Medications[$row] = $row;
            }
            $tuberculosis = json_encode([
                "Any_Following" => $Any_Following,
                "Diagnosed" => $request->Diagnosed.' - '.$request->Diagnosed_Form,
                "Labs_Done" => $Labs_Done,
                "Medications" => $Medications,
            ]);
        } else {
            $tuberculosis = "";
        }

        if(isset($request->disability_injury)){
            foreach($request->disability_injury as $row){
                $selected_options[$row] = $row;
            }
            $disability_injury = json_encode([
                "selected_options" => $selected_options,
                "with_assistive" => $request->with_assistive_diagnosed.' - '.$request->with_assistive_spe,
                "need_assistive" => $request->need_assistive_diagnosed.' - '.$request->need_assistive_spe,
                "description" => $request->disability_description,
                "medication" => $request->injury_medication
            ]);
        } else {
            $disability_injury = "";
        }

        $hospital_history = [];
        for($i=0;$i<count($request->reason);$i++){
            $hospital_history[] = [
                "reason" => $request->reason[$i],
                "date" => $request->date[$i],
                "place" => $request->place[$i],
                "phicUsed" => $request->phicUsed[$i],
                "costNotCovered" => $request->costNotCovered[$i]
            ];
        }

        $surgical_history = [];
        for($i=0;$i<count($request->operation);$i++){
            $surgical_history[] = [
                "operation" => $request->operation[$i],
            ];
        }

        $personal_history = json_encode([
            "tried_smoking" => $request->tried_smoking,
            "smoking_age_started" => $request->smoking_age_started,
            "smoking_age_quit" => $request->smoking_age_quit,
            "smoking_no_sticks" => $request->smoking_no_sticks,
            "smoking_no_packs" => $request->smoking_no_packs,
            "fat_salt_intake" => $request->fat_salt_intake,
            "daily_vegetable" => $request->daily_vegetable,
            "daily_fruit" => $request->daily_fruit,
            "physical_activity" => $request->physical_activity,
            "tried_alcohol" => $request->tried_alcohol,
            "drunk_in_5mos" => $request->drunk_in_5mos,
            "tried_drugs" => $request->tried_drugs.' - '.$request->tried_drugs_spe,
        ]);

        if(isset($request->gyne_history)){
            foreach($request->gyne_history as $row){
                $gyne_options[$row] = $row;
            }
        } else {
            $gyne_options = [];
        }
        if(isset($request->gyne_history_others)){
            $gyne_options["Others"] = $request->gyne_history_others;
        }
        $gyne_history = json_encode([
            "selected_options" => $gyne_options,
            "age_menarche" => $request->age_menarche,
            "last_period" => $request->last_period,
            "no_pads" => $request->no_pads,
            "duration" => $request->duration,
            "interval" => $request->interval
        ]);

        $dengvaxia_history = [];
        for($i=0;$i<count($request->history_count);$i++){
            $place = "place".$i;
            $dengvaxia_history[] = [
                "history_count" => $request->history_count[$i],
                "place" => $request->$place,
                "date" => $request->vaccine_date[$i],
            ];
        }
        $vaccine_history = json_encode([
            "vaccine_received" => $request->vaccine_received,
            "no_dose" => $request->no_dose,
            "dengvaxia_history" => $dengvaxia_history,
            "supplementation_date" => $request->supplementation_date,
            "capsule_date" => $request->capsule_date,
            "dewormed_date" => $request->dewormed_date,
        ]);

        $other_procedures = $request->other_procedures;
        if(isset($request->xray_result)){
            $key = array_search('Chest X-ray',$request->other_procedures);
            $other_procedures[$key] = $other_procedures[$key].' - '.$request->xray_result;
        }
        if(isset($request->enzymes_result)){
            $key = array_search('Enzymes',$request->other_procedures);
            $other_procedures[$key] = $other_procedures[$key].' - '.$request->enzymes_result;
        }

        $review_system = $request->review_system;
        if(!empty($request->review_system_others)){
            $key = array_search('Others',$request->review_system);
            $review_system[$key] = $review_system[$key].' - '.$request->review_system_others;
        }

        Dengvaxia::updateOrCreate(
            ['id' => $dengvaxiaID], [
                "unique_id" => $unique_id,
                "lname" => $request->lname,
                "fname" => $request->fname,
                "mname" => $request->mname,
                "suffix" => $request->suffix,
                "head" => $request->head,
                "gen_res" => $request->gen_res,
                "gen_con" => $request->gen_con,
                "gen_hou_r" => $request->gen_hou_r,
                "barangay_id" => $request->barangay_id,
                "muncity_id" => $request->muncity_id,
                "province_id" => $request->province_id,
                "sex" => $request->sex,
                "gen_age" => $request->gen_age,
                "gen_reli" => $religion,
                "dob" => $request->dob,
                "birthplace" => $request->birthplace,
                "gen_rel_yrs" => $request->gen_rel_yrs,
                "education" => $request->education,
                "phic_membership" => $phic_membership,
                "family_history" => $family_history,
                "medical_history" => $medical_history,
                "bronchial_asthma" => $bronchial_asthma,
                "tuberculosis" => $tuberculosis,
                "disability_injury" => $disability_injury,
                "hospital_history" => json_encode($hospital_history),
                "surgical_history" => json_encode($surgical_history),
                "personal_history" => $personal_history,
                "mens_gyne_history" => $gyne_history,
                "vaccine_history" => $vaccine_history,
                "other_procedures" => json_encode($other_procedures),
                "review_systems" => json_encode($review_system),
                "platform" => "web",
                "tsekap_id" => $tsekap_id
            ]
        );

        Profile::updateOrCreate(
            ['unique_id' => $unique_id], [
                "lname" => $request->lname,
                "fname" => $request->fname,
                "mname" => $request->mname,
                "suffix" => $request->suffix,
                "head" => $request->head,
                "dob" => $request->dob,
                "sex" => $request->sex,
                "barangay_id" => $request->barangay_id,
                "muncity_id" => $request->muncity_id,
                "province_id" => $request->province_id,
                "education" => $request->education,
                "dengvaxia" => "yes"
            ]
        );

        if( Session::get('dengvaxia_option') == "add" ){
            Session::flash('deng_add',"Successfully Added to dengvaxia..". "<strong style='color: #ff4374'>" .$request->fname.' '.$request->mname.' '.$request->lname."</strong>");
            return redirect('user/population');
        } else {
            Session::flash('deng_updated',"Successfully Updated!");
            return redirect()->back();
        }

    }

    public function sessionProcessPrint($unique_id){
        session_start();
        $_SESSION['unique_id'] = $unique_id;
    }

    public function fpdf(){
        \Fpdf::AddPage();
        \Fpdf::SetFont('Courier', 'B', 18);
        \Fpdf::SetXY(3,10);
        \Fpdf::Cell(0, 0, 'Hello World!');

        \Fpdf::SetXY(3,15);
        \Fpdf::Cell(0, 0, 'Hello World!');
        \Fpdf::Output();

        //return response(\Fpdf::Output(), 200)->header('Content-Type', 'application/pdf');
    }

    public function crossMatching($provinceId,$muncityId){
        $data_tsekap = [];
        $dengvaxia = Dengvaxia::where("province_id","=",$provinceId)
            ->where('muncity_id','=',$muncityId)
            ->where('status','=',1)
            ->get(["id","unique_id","tsekap_id","fname","lname","province_id","muncity_id","dob"]);

        foreach( $dengvaxia as $deng ){
            if($tsekap = Profile::where('province_id','=',$provinceId)
                ->where('muncity_id','=',$muncityId)
                ->where("fname","=",$deng->fname)
                ->where("lname","=",$deng->lname)
                ->where('dob','=',date("Y-m-d",strtotime($deng->dob)))
                ->where('dengvaxia','!=','yes')
                ->first()){

                $data_tsekap[] = $tsekap;

                $dengvaxia = Dengvaxia::where("id","=",$deng->id)->first();
                $dengvaxia->update([
                    "unique_id" => $tsekap->unique_id,
                    "tsekap_id" =>  $tsekap->id
                ]);

                $tsekap->update([
                    "dengvaxia" => "yes"
                ]);
            }
        }

        Session::flash('crossMatch',count($data_tsekap)." patient has been cross match");
        return redirect()->back();
    }


}