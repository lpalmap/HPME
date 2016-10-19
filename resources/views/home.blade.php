@extends('layouts.master')
@section('content')
<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner" style="min-height:1200px;">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Bienvenido. {{Session::get('rol')}}</h2>
                    </div>
                </div>
                <hr />
            </div>
        </div>
@endsection