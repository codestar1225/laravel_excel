@extends('layouts.admin')

@section('htmlheader_title')
Tabular Chart
@endsection

@section('contentheader_title')
Tabular Chart
@endsection

@section('main-content')
<link rel="stylesheet" href="{{ asset('/plugins/jstree/themes/default/style.min.css')}}">
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
<script src="{{ asset('/plugins/jstree/jstree.min.js')}}"></script>

<script>
    rootTree = {!! $rootTree !!};
    validNames = {!! $validNames !!};
    $('#chart-container').jstree({
        'core' : {
            'data' : rootTree
        }
    }).on('loaded.jstree',function(e, data){
        data.instance.open_node($('.jstree-node:first', data.instance.element));
    }).on('refresh.jstree',function(e, data){
        data.instance.open_node($('.jstree-node:first', data.instance.element));
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
        $('#chart-container').jstree(true).settings.core.data = d;
        $('#chart-container').jstree(true).refresh();
    }
    return false;
}

function getData(name, org){
    for(var i = 0; i < org.length; i++){
        var node = org[i];
        if(node.text == name){
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