@extends('layouts.admin')

@section('htmlheader_title')
Closing Approvals
@endsection

@section('contentheader_title')
Closing Approvals
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
                                <th style="width:60px;">Date</th>
                                <th>Username</th>
                                <th>Plan</th>
                                <th>Sponsor</th>
                                <th>ETH Balance</th>
                                <th>FLC Balance</th>
                                <th>Status</th>
                                <th style="width:200px;"></th>
                            </tr>
                        </thead>
                        <tbody style="display:none;">
                            @foreach($approvals as $approval)
                            <tr>
                                <td>{{Carbon\Carbon::parse($approval['created_at'])->format('Y-m-d')}}</td>
                                <td>{{$approval['user']['username']}}</td>
                                <td>{{$approval['user']['plan']['label']}}</td>
                                <td>{{$approval['user']['sponsor']['username']}}</td>
                                <td>{{$approval['ETH_wallet']}}</td>
                                <td>{{$approval['FELTA_wallet']}}</td>
                                <td>@include('partials.approvalstatus', ['status' => $approval['status']])</td>
                                <td>
                                    <div class="row">
                                        @if($approval['status'] == 0)
                                        <div class="col-md-6">
                                                <a href="{{ route('admin.approvals.editclosing',$approval['id'])}}"
                                                class="btn btn-block btn-primary">View</a>
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.approvals.updateclosing', $approval['id'])}}"
                                                method="post">
                                                @csrf
                                                @method("PATCH")
                                                <input name="status" type="hidden" value="2" />
                                                <button class="btn btn-block btn-danger" type="submit">Reject</button>
                                            </form>
                                        </div>
                                        @elseif($approval['status'] == 1)
                                        <div class="col-md-6">
                                                <a href="{{ route('admin.approvals.editclosing',$approval['id'])}}"
                                                class="btn btn-block btn-primary">View</a>
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
                if(data[6] !== status){
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