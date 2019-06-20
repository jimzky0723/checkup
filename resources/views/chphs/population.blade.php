<?php
use App\Http\Controllers\ParameterCtrl as Param;
use App\FamilyProfile;
use App\Barangay;
use App\Muncity;
use App\Province;
?>
@extends('app')
@section('content')
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <h2 class="page-header">List of Population</h2>
            <form class="form-inline" method="POST" action="{{ asset('chphs/population') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Quick Search" name="keyword" value="{{ Session::get('profileKeyword') }}" autofocus>
                    </div>
                    <div class="form-group">
                        <select name="province" class="filterProvince form-control chosen-select-static">
                            @if(Auth::user()->user_priv==1)
                                <?php $provinces = App\Province::get(); ?>
                                <option value="">Select Province...</option>
                                @foreach($provinces as $p)
                                    <option @if(Session::get('profileProvince')==$p->id) selected @endif value="{{ $p->id }}">{{ $p->description }}</option>
                                @endforeach
                            @else
                                <option value="{{ Auth::user()->province }}">{{ App\Province::find(Auth::user()->province)->description }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="tmpMuncity" value="{{ Session::get('profileMuncity') }}" />
                        <select name="muncity" class="filterMuncity form-control chosen-select-static">
                            <option value="">Select Municipal/City</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-default col-xs-12"><i class="fa fa-search"></i> Search</button>
                        <div class="clearfix"></div>
                    </div>
                    @if(!empty(Session::get('profileKeyword')))
                    <div class="form-group">
                        <button type="submit" name="viewAll" value="viewAll" class="btn btn-warning col-xs-12"><i class="fa fa-search"></i> View All</button>
                        <div class="clearfix"></div>
                    </div>
                    @endif
                    <div class="form-group">
                        <a href="{{ asset('print/chphs/print.php') }}" target="_blankprint/chphs/print.php" type="button" class="btn btn-danger col-xs-12"><i class="fa fa-print"></i> Print</a>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!-- DILE PA PWEDE MAGAMIT UPLOAD FROM EXCEL KAY TUNGOD SA FAMILY ID SA TSEKAP
                <div class="form-group" style="float: right">
                    <form class="form-inline" method="POST" id="importExcel" action="{{ URL::to('importExcel') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="file" name="import_file" class="form-control" value="{{ Session::get('profileKeyword') }}">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success col-xs-12"><i class="fa fa-download"></i> Upload</button>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
                -->
            </form>
            <div class="clearfix"></div>
            <div class="page-divider"></div>
            <div class="table-responsive">
                @if(count($profiles))
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Date Added</th>
                            <th>Complete Name</th>
                            <th>DOB</th>
                            <th>Sex</th>
                            <th>Weight(kg)</th>
                            <th>Height(cm)</th>
                            <th>Blood Type</th>
                            <th>Location</th>
                            <th>CHPHS NO</th>
                            <th class="text-center">Device</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($profiles as $p)
                            <tr>
                                <td nowrap="TRUE">
                                    {{ date('M d, Y',strtotime($p->created_at)) }}<br />
                                    <font class="text-success">
                                        ({{ date('h:i A',strtotime($p->created_at)) }})
                                    </font>
                                </td>
                                <td class="<?php if($p->head=='YES') echo 'text-bold text-primary';?>">
                                    {{ $p->fname }} {{ $p->mname }} {{ $p->lname }} {{ $p->suffix }}
                                    <br />
                                    <a href="#familyProfile" data-backdrop="static" data-id="{{ $p->familyID }}" data-toggle="modal" class="title-info">
                                        <small>
                                            {{ $p->familyID }}
                                        </small>
                                    </a>
                                </td>
                                <td>
                                    {{ date('M d, Y',strtotime($p->dob)) }}
                                </td>
                                <td>{{ $p->sex }}</td>
                                <td>{{ $p->weight }}</td>
                                <td>{{ $p->height }}</td>
                                <td>{{ $p->blood_type }}</td>
                                <td>
                                    {{
                                    $p->house_no.
                                    ','.$p->street_name.
                                    ','.$p->sitio.
                                    ','.$p->purok.
                                    ','.Barangay::find($p->barangay_id)->description.
                                    ','.Muncity::find($p->muncity_id)->description.
                                    ','.Province::find($p->province_id)->description
                                    }}
                                </td>
                                <td>{{ $p->chphs_no }}</td>
                                <td class="text-center">
                                    <?php
                                    $device = \App\Http\Controllers\PopulationCtrl::getDevice($p->unique_id);
                                    ?>
                                    @if($device=='web')
                                        <i class="fa fa-tv text-aqua"></i>
                                    @elseif($device=='mobile')
                                        <i class="fa fa-android text-success"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $profiles->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <p class="text-info"><i class="fa fa-info-circle fa-lg text-bold"></i> You don't have any profiles in your location!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        <?php echo 'var link="'.asset('user/profiles').'";';?>
        $('a[href="#familyProfile"]').on('click',function(){
                <?php echo 'var url="'.asset('population/profiles').'";';?>
            var id = $(this).data('id');
            $('.family-list').html('<center><img src="<?php echo asset('resources/img/spin.gif');?>" width="100"></center>');
            $.ajax({
                url: url+'/'+id,
                type: 'GET',
                success: function(jim){
                    var content = '<ul class="list-group">';
                    jQuery.each(jim,function(i,val){
                        content += '<li class="list-group-item">';
                        content += val.lname+', '+val.fname+' '+val.mname+' '+val.suffix;
                        content += '<br/><small>('+val.relation+')</small>';
                        content += '</li>';
                    });
                    content += '</ul>';
                    $('.family-list').html(content);
                }
            });

        });
    </script>
    <script>
        filterProvince($('.filterProvince').val());
        $('.filterProvince').on('change',function(){
            var id = $(this).val();
            filterProvince(id);
        });

        function filterProvince(id,muncity)
        {
            var muncity = $('#tmpMuncity').val();
            var url = "<?php echo asset('location/muncity'); ?>"+"/"+id;
            $('.filterMuncity').empty()
                .append($('<option>', {
                    value: "",
                    text : "Select Municipal / City..."
                }));

            $('.filterBarangay').empty()
                .append($('<option>', {
                    value: "",
                    text : "Select Barangay..."
                }));
            if(id){
                $('.loading').show();
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        jQuery.each(data, function(i,val){
                            $('.filterMuncity').append($('<option>', {
                                value: val.id,
                                text : val.description,
                                selected : function(){
                                    if(muncity==val.id){
                                        return true;
                                    }else{
                                        return false;
                                    }
                                }
                            }));
                            $('.filterMuncity').chosen().trigger('chosen:updated');
                        });
                        setTimeout(function(){
                            $('.loading').hide();
                        },200);
                    }
                });
            }
        }

        $("#importExcel").submit(function(event){
            console.log("rtayong");
        });
    </script>
@endsection