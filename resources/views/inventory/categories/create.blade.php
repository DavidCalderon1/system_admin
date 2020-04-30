@extends('layouts.app')
@section('title', 'Crear Categoría')

@section('content')
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <form action="{{route('inventory.category.store')}}" METHOD="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Nombre*</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{old('name')}}"
                               onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                        @if($errors->has('name'))
                            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Decripción*</label>
                        <textarea class="form-control" name="description"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();"></textarea>
                        @if($errors->has('description'))
                            <small class="form-text text-danger">{{ $errors->first('description') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <br>
            <input type="submit" class="btn btn-success" value="{{__('users.view.button_create')}}">
            <a href="{{route('inventory.category.index')}}"
               class="btn btn-danger">{{__('users.view.button_cancel')}}</a>
        </form>
        <script>
            window.onload = function () {
                document.getElementById("name").focus();
            };
        </script>
    </div>
@endsection
