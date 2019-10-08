@extends('layouts.admin')

@section('htmlheader_title')
Investment Plans
@endsection

@section('contentheader_title')
Investment Plans
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <a href="{{route('admin.plans.create')}}" class="btn btn-addon pull-right btn-success"><i
                            class="fa fa-plus"></i>New Plan</a>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Label</th>
                                <th>Price</th>
                                <th>Investor Profit</th>
                                <th>Company Profit</th>
                                <th>FLC Bonus</th>
                                <th style="width:180px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                            <tr>
                                <td>{{$plan->label}}</td>
                                <td>{{$plan->price}} {{$plan->price_type}}</td>
                                <td>{{$plan->user_comm}} %</td>
                                <td>{{$plan->company_comm}} %</td>
                                <td>{{$plan->bonus}} FLC</td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6">
                                                <a href="{{ route('admin.plans.edit',$plan->id)}}"
                                                        class="btn btn-block btn-primary">Edit</a>
                                        </div>
                                        <div class="col-md-6">
                                                <form action="{{ route('admin.plans.destroy', $plan->id)}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-block btn-danger" type="submit">Delete</button>
                                                    </form>
                                        </div>
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