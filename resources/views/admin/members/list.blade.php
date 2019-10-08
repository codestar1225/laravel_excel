@extends('layouts.admin')

@section('htmlheader_title')
Members
@endsection

@section('contentheader_title')
Members
@endsection
<!-- <style>
.dataTables_wrapper .dt-buttons {
  float:right;
}
</style> -->

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
                                <th>Name</th>
                                <th>User ID</th>
                                <th>Plan</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Sponsor</th>
                                <th>Joined</th>
                                <th>Satus</th>
                            </tr>
                        </thead>
                        <tbody style="display:none;" >
                            @foreach($members as $member)
                            <tr>
                                <td><a href="{{ route('admin.members.show',$member['id'])}}"
                                    class="">{{$member['name']}}</a></td>
                                <td><a href="{{ route('admin.members.show',$member['id'])}}"
                                class="">{{$member['username']}}</a></td>
                                <td>{{$member['plan']['label']}}</td>
                                <td>{{$member['email']}}</td>
                                <td>{{$member['contact']}}</td>
                                <td>
                                    @if($member['sponsor'])
                                    <a href="{{ route('admin.members.show',$member['sponsor']['id'])}}"
                                    class="">{{$member['sponsor']['username']}}</a>
                                    @endif
                                </td>
                                <td>{{Carbon\Carbon::parse($member['created_at'])->format('Y-m-d')}}</td>
                                <td>
                                    @include('partials.userstatus', ['status' => $member['status']])
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
    // $('#table-members').DataTable();

    $('#table-members').DataTable( {
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'excel',
                text: 'Excel'
            }
        ]
    } );

    $('#table-members tbody').show();
</script>
@endsection