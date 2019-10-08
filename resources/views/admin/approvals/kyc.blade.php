@extends('layouts.admin')

@section('htmlheader_title')
KYC Approvals
@endsection

@section('contentheader_title')
KYC Approvals
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-control" id='searchByStatus'>
                                <option value=''>-- Filter by Status--</option>
                                <option value='Pending'>Pending</option>
                                <option value='Approved'>Approved</option>
                                <option value='Rejected'>Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-hover" id="table-approvals">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>member ID</th>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Proof</th>
                                <th style="width:200px;"></th>
                            </tr>
                        </thead>
                        <tbody style="display:none;">
                            @foreach($approvals as $approval)
                            <tr>
                                <td>{{Carbon\Carbon::parse($approval['created_at'])->format('Y-m-d')}}</td>
                                <td>{{$approval['user']['name']}}</td>
                                <td>{{$approval['user']['username']}}</td>
                                <td>{{$approval['user']['id_type']}} : {{$approval['user']['id_number']}}</td>
                                <td>@include('partials.approvalstatus', ['status' => $approval['status']])</td>
                                <td>
                                    @if($approval['content'])
                                    <a href="{{ url($approval['content'])}}" target="_blank"><img
                                            src="{{ url($approval['content'])}}"
                                            class="attachment-img" /></a>
                                    @endif
                                </td>
                                <td>
                                    <div class="row">
                                        @if($approval['status'] == 0)
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.approvals.updatekyc', $approval['id'])}}"
                                                method="post">
                                                @csrf
                                                @method("PATCH")
                                                <input name="status" type="hidden" value="1" />
                                                <button class="btn btn-block btn-success" type="submit">Approve</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.approvals.updatekyc', $approval['id'])}}"
                                                method="post">
                                                @csrf
                                                @method("PATCH")
                                                <input name="status" type="hidden" value="2" />
                                                <button class="btn btn-block btn-danger" type="submit">Reject</button>
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
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var status = $('#searchByStatus').val();
            var search = $('.dataTables_filter input[type=search]').val();
            if(status.length > 0){
                if(data[4] !== status){
                    return false;
                }
            }
            if(search.length > 0){
                if(((data[1] + data[3]).toLowerCase()).indexOf(search.toLowerCase()) == -1)
                {
                    return false;
                }
            }
            return true;
        }
    );

    $(document).ready(function(){
        var table = $('#table-approvals').DataTable({
            order: [[ 0, "desc" ]]
        })
        $('#table-approvals tbody').show();
        $('#searchByStatus').change(function(){
            table.draw();
        })
    });
</script>
@endsection