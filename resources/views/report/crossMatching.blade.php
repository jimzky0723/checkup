@extends('app')
@section('content')
    <div class="col-md-12 wrapper">
        <div class="alert alert-jim">
            <h3 class="page-header">Cross Matching Report Province of Cebu</h3>
            <div class="clearfix"></div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="border: 1px solid #d6e9c6">
                    <tr>
                        <th class="bg-primary">Municipality</th>
                        <th class="bg-primary">Tsekap Profiled</th>
                        <th class="bg-primary">Dengvaxia MasterList</th>
                        <th class="bg-primary">Cross Match</th>
                    </tr>
                        @foreach($municipality as $mun)
                        <tr>
                            <td><strong class="text-italic">{{ $mun->description }}</strong></td>
                            <td>{{ $mun->tsekapProfiled }}</td>
                            <td>{{ count(\App\Dengvaxia::where('province_id','=',2)->where('muncity_id','=',$mun->id)->get(["id"])) }}</td>
                            <td class="bg-success"><strong style="color: #f07637">{{ count(\App\Profile::where('province_id','=',2)->where('muncity_id','=',$mun->id)->where('dengvaxia','=','yes')->get(["id"])) }}</strong></td>
                        </tr>
                        @endforeach
                </table>
            </div>

        </div>
    </div>
@endsection

@section('js')

@endsection