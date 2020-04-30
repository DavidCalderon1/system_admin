@extends('layouts.app')
@section('title', 'Editar Categoría')

@section('content')
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <form action="{{route('inventory.category.update',['category' => $currentInventoryCategory['id']])}}"
              METHOD="POST">
            @csrf
            <input type="hidden" name="id" value="{{$currentInventoryCategory['id']}}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Nombre*</label>
                        <input type="text" name="name" class="form-control"
                               value="{{$currentInventoryCategory['name']}}"
                               onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                        @if($errors->has('name'))
                            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Decripción*</label>
                        <textarea class="form-control" name="description"
                                  onkeyup="javascript:this.value=this.value.toUpperCase();"> {{$currentInventoryCategory['description']}}</textarea>
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
    </div>
@endsection
