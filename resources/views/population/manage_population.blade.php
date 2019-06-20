<?php
use App\Http\Controllers\ParameterCtrl as Param;
use App\FamilyProfile;
?>
@extends('app')
@section('content')
    <style>
        .family {
            font-size: 0.9em;
        }
        .family label {
            font-weight: bold;
        }
        .family .info {
            color: #337ab7;
        }
        .family .sub-info {
            font-style: italic;
            color: #a94442;
        }
    </style>
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <h2 class="page-header">Manage Population</h2>
            @if(Session::has('deng_add'))
                <div class="alert alert-success">
                    <font class="text-success">
                        <i class="fa fa-check"></i> {!! Session::get('deng_add') !!}
                    </font>
                </div>
            @endif
            @if(Session::has('crossMatch'))
                <div class="alert alert-success">
                    <font class="text-success">
                        <i class="fa fa-check"></i> {!! Session::get('crossMatch') !!}
                    </font>
                </div>
            @endif
            <form class="form-inline" method="POST" action="{{ asset('admin/population').'/'.$provinceId.'/'.$municipalityId }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <?php $tmp = Session::get('profileKeyword');?>
                    <input type="text" class="form-control" placeholder="Quick Search" name="keyword" value="{{ $tmp['keyword'] }}" autofocus>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default col-xs-12"><i class="fa fa-search"></i> Search</button>
                    <div class="clearfix"></div>
                </div>
                @if(Session::get('profileKeyword'))
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning col-xs-12" name="viewAll" value="true"><i class="fa fa-search"></i> View All</button>
                        <div class="clearfix"></div>
                    </div>
                @endif
                <div class="form-group">
                    <a class="btn btn-info col-xs-12" href="{{ asset('user/population/head') }}"><i class="fa fa-user-plus"></i> Add Family Head Profile</a>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <a class="btn btn-success col-xs-12" href="#filterResult" data-toggle="modal"><i class="fa fa-filter"></i> Filter Result</a>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <a class="btn btn-warning col-xs-12" href="{{ asset('crossMatching').'/'.$provinceId.'/'.$municipalityId }}"><i class="fa fa-cloud-download"></i> Cross Matching</a>
                    <div class="clearfix"></div>
                </div>
            </form>
            <div class="clearfix"></div>
            <div class="page-divider"></div>
            <div class="table-responsive">
                @if(count($profiles))
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Family ID </th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Suffix</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Barangay</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($profiles as $p)
                            <tr>
                                <td nowrap="TRUE">
                                    <a href="{{ asset('user/population/info/'.$p->id) }}" class="btn btn-xs btn-success">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a href="{{ asset('user/population/add/' . $p->familyID) }}" class="btn btn-xs btn-info">
                                        <i class="fa fa-user-plus"></i> Add Member
                                    </a>
                                    @if($p->dengvaxia == "yes")
                                        <a href="#dengvaxia" data-backdrop="static" data-id="{{ $p->id }}" data-unique="{{ $p->unique_id }}" class="btn btn-xs btn-danger"  data-toggle="modal">
                                            <i class="fa fa-user-md"></i> Dengvaxia
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="#familyProfile" data-backdrop="static" data-id="{{ $p->familyID }}" data-toggle="modal" class="title-info">
                                        {{ $p->familyID }}
                                    </a>
                                </td>
                                <td class="<?php if($p->head=='YES') echo 'text-bold text-primary';?>">{{ $p->lname }}</td>
                                <td class="<?php if($p->head=='YES') echo 'text-bold text-primary';?>">{{ $p->fname }}</td>
                                <td class="<?php if($p->head=='YES') echo 'text-bold text-primary';?>">{{ $p->mname }}</td>
                                <td>{{ $p->suffix }}</td>
                                <td>
                                    <?php
                                    $age = Param::getAge($p->dob);
                                    $tmp = '';
                                    ?>
                                    @if($age==0)
                                        <?php
                                        $age = Param::getAgeMonth($p->dob);
                                        $tmp = 'M/o';
                                        ?>
                                        @if($age==0)
                                            <?php
                                            $age = Param::getAgeDay($p->dob);
                                            $tmp = 'D/o';
                                            ?>
                                        @endif
                                    @endif

                                    @if($tmp)
                                        <small class="text-info">({{$age}} {{$tmp}})</small>
                                    @else
                                        {{ $age }}
                                    @endif
                                </td>
                                <td>{{ $p->sex }}</td>
                                <td>{{ \App\Barangay::find($p->barangay_id)->description }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $profiles->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <p class="text-info"><i class="fa fa-info-circle fa-lg text-bold"></i> No data found!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('modal.populationModal')
@endsection

@section('js')
    @include('script.population')
    <?php
    $status = session('status');
    ?>
    @if($status=='added')
        <script>
            Lobibox.notify('success', {
                msg: 'Added successfully!'
            });
        </script>
    @endif

    @if($status=='updated')
        <script>
            Lobibox.notify('success', {
                msg: 'Updated successfully!'
            });
        </script>
    @endif

    @if($status=='deleted')
        <script>
            Lobibox.notify('success', {
                msg: 'Deleted successfully!'
            });
        </script>
    @endif

    <script>
        <?php echo 'var link="'.asset('user/profiles').'";';?>

        $(".select-profile").select2({
            ajax: {
                url: link,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 10) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            templateResult: formatProfile, // omitted for brevity, see the source of this page
            templateSelection: formatProfileSelection, // omitted for brevity, see the source of this page

        });
        $('a[href="#addProfile"]').on('click',function(){
            $('#addProfile').modal('toggle');
            var id = $(this).data('id');
            var description = $(this).data('description');
            $('#familyID').val(id);
            $('#familyName').val(description);
        });


        $('a[href="#infoProfile"]').on('click',function(){
            var id = $(this).data('id');
            $('.loading').show();
            <?php echo 'var url="'.asset('user/population/info').'";';?>
            $.ajax({
                url: url+'/'+id,
                type: 'GET',
                success: function(jim){
                    $('#currentID').val(id);
                    $('#fname').val(jim.fname);
                    $('#mname').val(jim.mname);
                    $('#lname').val(jim.lname);
                    $('#suffix').val(jim.suffix).trigger('change');
                    $('#head').val(jim.head).trigger('change');
                    $('#dob').val(jim.dob);
                    $('#sex').val(jim.sex).trigger('change');
                    $('#barangay').val(jim.barangay_id).trigger('change');
                    $('#currentFamilyID').val(jim.family_id);
                    $('#currentFamilyName').val(jim.description);
                    $('#head').val(jim.head).trigger("chosen:updated");

                    var tmpHead = $('#head').val();
                    if(tmpHead=='YES'){
                        $('#relation').parent('.form-group').hide();
                    }else{
                        $('#relation').parent('.form-group').show();
                    }
                    $('#relation').val(jim.relation).trigger('change');
                    $('.loading').hide();
                }
            });

        });

        $('#head').on('change',function(){
            var tmp = $(this).val();
            console.log(tmp);
            if(tmp=='YES'){
                $('#relation').parent('.form-group').hide();
            }else{
                $('#relation').parent('.form-group').show();
            }
        });

        function formatProfile(profile) {
            if (profile.loading) return profile.text;
            var html = '<div><strong>'+profile.description+'</strong> - <small>('+profile.fname+' '+profile.mname+' '+profile.lname+')</small></div>';
            return html;
        }

        function formatProfileSelection(profile) {
            return profile.description || profile.text;
        }
        $('.filter_familyProfile').on('change',function(){
            if($(this).val()=== 'others'){
                $('.others').removeClass('hide').fadeIn();
            } else {
                $('.others').addClass('hide');
            }
        });


        function validateForm(){
            var familyProfile = $('select[name="familyID"]');
            var brgy = $('select[name="barangay"]');
            var head = $('select[name="head"]');

            if(familyProfile.val()){
                $('select[name="familyID"]').siblings('.chosen-container').css({border:'none'});
            }else{
                $('select[name="familyID"]').siblings('.chosen-container').css({border:'2px solid red'});
            }

            if(brgy.val()){
                $('select[name="barangay"]').siblings('.chosen-container').css({border:'none'});
            }else{
                $('select[name="barangay"]').siblings('.chosen-container').css({border:'2px solid red'});
            }

            if(head.val()){
                $('select[name="head"]').siblings('.chosen-container').css({border:'none'});
            }else{
                $('select[name="head"]').siblings('.chosen-container').css({border:'2px solid red'});
            }
        }

        function validateForm2(){
            var familyProfile = $('select[id="familyID"]');
            var brgy = $('select[id="barangay"]');
            var head = $('select[id="head"]');

            if(familyProfile.val()){
                familyProfile.siblings('.chosen-container').css({border:'none'});
            }else{
                familyProfile.siblings('.chosen-container').css({border:'2px solid red'});
            }

            if(brgy.val()){
                brgy.siblings('.chosen-container').css({border:'none'});
            }else{
                brgy.siblings('.chosen-container').css({border:'2px solid red'});
            }

            if(head.val()){
                head.siblings('.chosen-container').css({border:'none'});
            }else{
                head.siblings('.chosen-container').css({border:'2px solid red'});
            }
        }

        $('.btn-submit').on('click',function(){
            validateForm();
        });

        $('.btn-update').on('click',function(){
            validateForm2();
        });

        $('a[href="#dengvaxia"]').on('click',function(){
            var id = $(this).data('id');
            var unique_id = $(this).data('unique');
            var url = "{{ asset('verify_dengvaxia') }}"+"/"+id+"/"+unique_id;
            $('.verify-dengvaxia').html('<center><img src="<?php echo asset('resources/img/spin.gif');?>" width="100"></center>');
            setTimeout(function(){
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(result){
                        $('.verify-dengvaxia').html(result);
                    }
                });
            },300);
        });

    </script>
@endsection