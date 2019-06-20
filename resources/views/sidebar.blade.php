<?php
    $user = Auth::user();
    use App\Barangay;
    use App\Muncity;
    use App\Province;
    use App\Http\Controllers\ParameterCtrl as Param;

    $online = Param::countOnlineUsers();
?>
<div class="col-md-3 wrapper">
    <div class="panel panel-jim">
        <div class="panel-heading">
            <h3 class="panel-title">Welcome, {{ strtoupper($user->username) }}</h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Account Name</label>
                <input type="text" readonly class="form-control" value="{{ $user->lname }}, {{ $user->fname }} {{ $user->mname }} {{ $user->suffix }}" />
            </div>

            <div class="form-group">
                <label>Municipality / City</label>
                <input type="text" readonly class="form-control" value="{{ Muncity::find($user->muncity)->description }}" />
            </div>

            <div class="form-group">
                <label>Province</label>
                <input type="text" readonly class="form-control" value="{{ Province::find($user->province)->description }}" />
            </div>
            <div class="form-group">
                <a href="{{ asset('logout') }}" class="btn btn-success col-xs-12"><i class="fa fa-sign-out"></i> Logout</a>
            </div>
        </div>
    </div>
    @if(Auth::user()->user_priv==0 || Auth::user()->user_priv==2)
    <div class="panel panel-jim">
        <div class="panel-heading">
            <h3 class="panel-title">Other Links</h3>
        </div>
        <div class="panel-body">
            <ul class="list-group">
                <?php $validBrgy = \App\Http\Controllers\UserCtrl::validateBrgy();?>
                @if($validBrgy)
                <li class="list-group-item">
                    <a href="{{ url('/resources/apk/PHA Check-App.apk') }}" class="btn btn-warning col-xs-12">
                        <i class="fa fa-android"></i> PHA Check-App v2.0
                    </a>
                    <div class="clearfix"></div>
                </li>
                @endif
                <li class="list-group-item hide">
                    <a href="https://www.dropbox.com/sh/5xvg3h20k9k8hr3/AAC1Ccp7_Ev4kMnyLJ1LXPyxa?dl=0" target="_blank" class="btn btn-warning col-xs-12">
                        <i class="fa fa-tv"></i> NOX Android Emulator
                    </a>
                    <div class="clearfix"></div>
                </li>
                <li class="list-group-item">
                    <a href="https://res05.bignox.com/g3/M00/00/D1/CqypflqVA2GAVryKE9kFoK8x4dw009.exe?filename=nox_setup_v6.0.5.3_full_intl.exe" target="_blank" class="btn btn-warning col-xs-12">
                        <i class="fa fa-tv"></i> NOX Android Emulator
                    </a>
                    <div class="clearfix"></div>
                </li>
                <li class="list-group-item">
                    <a href="#feedback" data-toggle="modal" class="btn btn-info col-xs-12">
                        <i class="fa fa-envelope-o"></i> Send Us a Feedback
                    </a>
                    <div class="clearfix"></div>
                </li>
                <li class="list-group-item">
                    <a href="https://drive.google.com/open?id=0B6JZKCIpWBwDWjZ0VXBIZ3ZaMG8" target="_blank" class="btn btn-success col-xs-12">
                        <i class="fa fa-video-camera"></i> Video Tutorial
                    </a>
                    <div class="clearfix"></div>
                </li>
            </ul>
            <div class="form-group" style="margin-top:10px;">

            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->user_priv==1 || Auth::user()->user_priv==3)
        <div class="panel panel-jim">
            <div class="panel-heading">
                <h3 class="panel-title">Who's Online</h3>
            </div>
            <div class="panel-body text-success">
                <center>
                    <a href="#online" data-toggle="modal" class="online text-success" data-url="{{ asset('report/online') }}"><i class="fa fa-users fa-3x"></i></a><br />
                    <div style="margin-top:10px"></div>
                    <font class="text-bold">
                        <a href="#online" data-toggle="modal" class="online text-success" data-url="{{ asset('report/online') }}">
                            @if($online<=1)
                                {{ $online }} Online User
                            @else
                                {{ $online }} Online Users
                            @endif
                        </a>
                    </font>
                </center>
            </div>
        </div>
    @endif
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="online" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <table class="table table-hover">
                    <caption style="font-weight: bold" class="text-success">Who's Online</caption>
                    <tbody class="onlineContent">
                    <tr>
                        <td>
                            <center><img src="{{ asset('resources/img/spin.gif') }}" width="150" style="padding:20px;"></center>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
