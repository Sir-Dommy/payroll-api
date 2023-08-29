@extends('layouts.main_hr')
@section('xara_cbs')

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="col-lg-12">

                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3>New Bank Branch</h3>

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}<br>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="card-block">

                            <form method="POST" action="{{{ url('bankbranches') }}}" accept-charset="UTF-8">@csrf

                                <fieldset>
                                    <div class="form-group">
                                        <label for="username">Bank Branch Code <span style="color:red">*</span> </label>
                                        <input class="form-control" placeholder="" type="text" name="code" id="code" value="{{{ old('code') }}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="username">Bank Branch Name <span style="color:red">*</span> </label>
                                        <input class="form-control" placeholder="" type="text" name="name" id="name" value="{{{ old('name') }}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="username">Bank <span style="color:red">*</span></label>
                                        <select name="bank" id="bank" class="form-control">
                                            <option></option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}"> {{ $bank->bank_name }}</option>
                                            @endforeach
                                        </select>

                                    </div>


                                    <div class="form-actions form-group">

                                        <button type="submit" class="btn btn-primary btn-sm">Create Bank Branch</button>
                                    </div>

                                </fieldset>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
