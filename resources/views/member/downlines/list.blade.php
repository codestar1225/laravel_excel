@extends('layouts.member')

@section('htmlheader_title')
Downlines List
@endsection

@section('contentheader_title')
Downlines List
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <div class="box-body">
                    <table id="table-members" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Plan</th>
                                <th style="width:180px;"></th>
                            </tr>
                        </thead>
                        <tbody style="display:none;">
                            @foreach($members as $member)
                            <tr>
                                <td>{{$member->username}}</td>
                                <td>{{$member->name}}</td>
                                <td>{{$member->email}}</td>
                                <td>{{$member->getLevelFor($user->id)}}</td>
                                <td>
                                    @include('partials.userstatus', ['status' => $member->status])
                                </td>
                                <td>{{$member->plan->label}}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="{{ route('member.downlines.show',$member->id)}}"
                                                class="btn btn-block btn-primary">View</a>
                                        </div>
                                        @if($member->status == 0 && $member->sponsor_id == $user->id)
                                        <div class="col-md-6">
                                            <form action="{{ route('member.downlines.destroy', $member->id)}}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-block btn-danger" type="submit">Delete</button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footerscripts')
<script>
    $('#table-members').DataTable({
"bSort" : false
} );
    $('#table-members tbody').show();
</script>
@endsection