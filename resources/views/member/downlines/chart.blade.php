@extends('layouts.member')

@section('htmlheader_title')
Hierarchy Chart
@endsection

@section('contentheader_title')
Hierarchy Chart
@endsection

@section('main-content')
<style>
    .orgchart{overflow:auto;}
    </style>
<div class="container-fluid spark-screen">
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <input id="input-search" type="text" placeholder="Search with username" class="form-control" />
                </div>
                <div class="col-md-2">
                    <a href="#" class="btn btn-primary btn-block" onclick="return searchTree();">Search</a>
                </div>
                <div class="col-md-2 pull-right">
                <a href="{{route('member.downlines.create')}}" class="btn btn-add btn-success"><i class="fa fa-user-plus"></i> New Member</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div id="chart-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerscripts')
<script>
    rootTree = {!! $rootTree !!};
    validNames = {!! $validNames !!};
    var oc = $('#chart-container').orgchart({
  'data' : rootTree,
  'depth': 3,
  'nodeTitle': 'username',
  'nodeContent': 'body'
});

function searchTree(){
    var username = $('#input-search').val();
    if(username.length == 0){
        username = '{{$username}}';
    }
    if(validNames.indexOf(username) === -1){
        alert("Member not found");
    }
    else{
        var d = getData(username, [rootTree]);
        oc.init({data:d});
    }
    return false;
}

function getData(name, org){
    for(var i = 0; i < org.length; i++){
        var node = org[i];
        if(node.username == name){
            return node;
            break;
        }
        else
        {
            if(node.children){
                var n = getData(name, node.children);
                if(n){
                    return n;
                }
            }
        }
    }
}
    
</script>
@endsection