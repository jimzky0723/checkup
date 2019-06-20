<?php
use App\Barangay;
use App\Profile;
use App\UserBrgy;

$brgy = Barangay::where('muncity_id',Auth::user()->muncity);

if(Auth::user()->user_priv==2){
    $tmpBrgy = UserBrgy::where('user_id',Auth::user()->id)->get();
    $brgy = $brgy->where(function($q) use ($tmpBrgy){
        foreach($tmpBrgy as $tmp){
            $q->orwhere('id',$tmp->barangay_id);
        }
    });
    if(count($tmpBrgy)==0){
        $brgy = $brgy->where('id',0);
    }
}
$brgy = $brgy->orderBy('description','asc')
        ->get();
$last = Profile::orderBy('id','desc')->first();
$ctrlNo = date('His');
$ctrlNo = str_pad($ctrlNo, 4, '0', STR_PAD_LEFT);
$brgyNo = str_pad(Auth::user()->barangay, 4, '0', STR_PAD_LEFT);
$idNo = str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT);
//$profileID = $muncityNo.'-'.$brgyNo.'-'.$ctrlNo;
$profileID = date('mdy').'-'.$idNo.'-'.$ctrlNo;
?>
@extends('client')
@section('content')
    <style>
        .table-input tr td:first-child {
            background: #f5f5f5;
            text-align: right;
            vertical-align: middle;
            font-weight: bold;
            padding: 3px;
            width:30%;
        }
        .table-input tr td {
            border:1px solid #bbb !important;
        }
    </style>
    <div class="col-md-9 wrapper">
        <div class="alert alert-jim">
            <h2 class="page-header">
                <i class="fa fa-user-plus"></i>
                Add Family Head
            </h2>
            <div class="page-divider"></div>
            <form method="POST" class="form-horizontal form-submit" id="form-submit" action="{{ asset('user/population/head/save') }}">
                {{ csrf_field() }}
                <table class="table-input table table-bordered table-hover" border="1">
                    <tr>
                        <td>Family Profile ID :</td>
                        <td><input type="text" name="familyProfile" readonly class="form-control" value="{{ $profileID }}" required /></td>
                    </tr>
                    <tr>
                        <td>PhilHealth ID :<br/> <small class="text-info"><em>(If applicable)</em></small></td>
                        <td><input type="text" name="phicID" class="form-control" value="" /></td>
                    </tr>
                    <tr>
                        <td>NHTS ID :<br/> <small class="text-info"><em>(If applicable)</em></small></td>
                        <td><input type="text" name="nhtsID" class="form-control" value="" /></td>
                    </tr>
                    <tr class="has-group">
                        <td>First Name :</td>
                        <td><input type="text" name="fname" class="fname form-control" required /> </td>
                    </tr>
                    <tr>
                        <td>Middle Name :</td>
                        <td><input type="text" name="mname" class="mname form-control" /> </td>
                    </tr>
                    <tr class="has-group">
                        <td>Last Name :</td>
                        <td><input type="text" name="lname" class="lname form-control" required /> </td>
                    </tr>
                    <tr>
                        <td>Suffix :</td>
                        <td>
                            <select name="suffix" class="form-control chosen-select" id="suffix" style="width: 100%">
                                <option value="">Select...</option>
                                <option>Jr.</option>
                                <option>Sr.</option>
                                <option>I</option>
                                <option>II</option>
                                <option>III</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="has-group">
                        <td>Birth Date :</td>
                        <td><input type="date" name="dob" class="form-control dob" required /> </td>
                    </tr>
                    <tr>
                        <td>Sex :</td>
                        <td class="has-group">
                            <label style="cursor: pointer;"><input onclick="showUnmet()" type="radio" name="sex" class="sex" value="Male" required style="display:inline;"> Male</label>
                            &nbsp;&nbsp;&nbsp;<br />
                            <label style="cursor: pointer;"><input onclick="showUnmet()" type="radio" name="sex" class="sex" value="Female" required> Female</label>
                            <span class="span"></span>
                        </td>
                    </tr>
                    <tr class="has-group">
                        <td>Barangay :</td>
                        <td>
                            <select name="barangay" class="form-control chosen-select" required id="suffix" style="width: 100%">
                                <option value="">Select...</option>
                                @foreach($brgy as $row)
                                <option value="{{ $row->id }}">{{ $row->description }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <?php $validBrgy = \App\Http\Controllers\UserCtrl::validateBrgy();?>
                    @if($validBrgy)
                    <tr>
                        <td>Monthly Family Income :</td>
                        <td>
                            <select name="income" class="form-control chosen-select" id="income" style="width: 100%">
                                <option value="">Select...</option>
                                <option value="1">Less than 7,890</option>
                                <option value="2">Between 7,890 to 15,780</option>
                                <option value="3">Between 15,780 to 31,560</option>
                                <option value="4">Between 31,560 to 78,900</option>
                                <option value="5">Between 78,900 to 118,350</option>
                                <option value="6">Between 118,350 to 157,800</option>
                                <option value="7">At least 157,800</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="unmet hide">
                        <td>Unmet Need :</td>
                        <td>
                            <input type="hidden" name="unmet" id="unmet" />
                            <div class="form-inline">
                                <input type="text" id="unmet2" class="form-control" readonly value="Not set" data-toggle="modal" data-target="#unmetNeed" />
                                <button type="button" style="margin:5px 0;" class="btn btn-info" data-toggle="modal" data-target="#unmetNeed">Yes</button>
                                <button type="button" style="margin:5px 0;" class="btn btn-warning" onclick="unmet_need()"> No </button>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>Safe Water Supply :</td>
                        <td>
                            <input type="hidden" name="water" id="water" />
                            <div class="form-inline">
                                <input type="text" id="water2" class="form-control" readonly value="Not set" data-toggle="modal" data-target="#waterLvl" />
                                <button type="button" style="margin:5px 0;" class="btn btn-info" data-toggle="modal" data-target="#waterLvl">Select...</button>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>Sanitary Toilet :</td>
                        <td>
                            <select name="toilet" class="form-control chosen-select" id="toilet" style="width: 100%">
                                <option value="">Select...</option>
                                <option value="non">None</option>
                                <option value="comm">Communal</option>
                                <option value="indi">Individual Household</option>
                            </select>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>Educational Attainment :</td>
                        <td>
                            <select name="education" class="form-control chosen-select" id="education" style="width: 100%">
                                <option value="">Select...</option>
                                <option value="non">No Education</option>
                                <option value="elem">Elementary Level</option>
                                <option value="elem_grad">Elementary Graduate</option>
                                <option value="high">High School Level</option>
                                <option value="high_grad">High School Graduate</option>
                                <option value="college">College Level</option>
                                <option value="college_grad">College Graduate</option>
                                <option value="vocational">Vocational Course</option>
                                <option value="master">Masteral Degree</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>CHPHS System? :</td>
                        <td>
                            <select name="chphs_status" class="form-control" onchange="chphs($(this))" id="chphs_status" style="width: 100%">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>Cluster:</td>
                        <td>
                            <select name="cluster" class="form-control" id="" style="width: 100%" required>
                                @foreach($cluster as $row)
                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>District:</td>
                        <td>
                            <select name="district" class="form-control" id="" style="width: 100%" required>
                                @foreach($district as $row)
                                    <option value="{{ $row->id }}">{{ $row->description }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>House No:</td>
                        <td>
                            <input type="text" class="form-control" name="house_no" >
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>Street Name:</td>
                        <td>
                            <input type="text" class="form-control" name="street_name" >
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>Sition:</td>
                        <td>
                            <input type="text" class="form-control" name="sitio" >
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>Purok:</td>
                        <td>
                            <input type="text" class="form-control" name="purok" >
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>Blood Type:</td>
                        <td>
                            <select name="bloodType" class="form-control" id="" style="width: 100%" >
                                <option value="A-">A-</option>
                                <option value="A+">A+</option>
                                <option value="AB-">AB-</option>
                                <option value="AB+">AB+</option>
                                <option value="O">O</option>
                                <option value="B-">B-</option>
                                <option value="B+">B+</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>Weight(kg):</td>
                        <td>
                            <input type="text" class="form-control" name="weight" >
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>Height(cm):</td>
                        <td>
                            <input type="text" class="form-control" name="height" >
                        </td>
                    </tr>
                    <tr class="chphs hide success">
                        <td>Contact No:</td>
                        <td>
                            <input type="text" class="form-control" name="contact_no" >
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <a href="{{ asset('user/population') }}" class="btn btn-sm btn-default">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-send"></i> Submit
                            </button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    @include('sidebar')
    @include('modal.profile')
    @include('modal.checkProfile')
@endsection

@section('js')
    @include('script.profile')
    <?php
    $status = session('status');
    ?>
    @if($status=='added')
        <script>
            Lobibox.notify('success', {
                msg: 'Successfully added!'
            });
        </script>
    @endif

    <script>
        showUnmet();
        function showUnmet()
        {
            var sex = $('input[name="sex"]:checked').val();
            console.log(sex);
            if(sex==='Female')
            {
                $('.unmet').removeClass('hide');
            }else{
                $('.unmet').addClass('hide');
                $('#unmet').val('0');
                $('#unmet2').val('Not set');
            }
        }

        chphs($("#chphs_status"));
        function chphs(data){
            if(data.val() == "yes")
                $(".chphs").removeClass("hide");
            else
                $(".chphs").addClass("hide");
        }
    </script>
@endsection