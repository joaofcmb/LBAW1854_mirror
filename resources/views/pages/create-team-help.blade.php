@extends('layouts.app')

@section('title', 'Help')

@section('body')
    <div class="navbar-dark sticky-top">
        <nav class="navbar navbar-expand-sm py-0 px-3 px-sm-1 px-lg-3">
            <a class="navbar-brand" href="{{ route('index') }}">
                <img src="{{ asset('img/logo.png') }}" width="35" class="d-inline-block" alt="Website Logo">
                <span class="font-weight-bold font-italic">EPMA</span>
            </a>
        </nav>
    </div>

    <div id="menu-option" class="container-fluid d-flex justify-content-between py-4">
        <a href="{{ URL::previous() }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
    </div>

    <div class="container d-flex flex-wrap flex-column align-content-center px-md-5 pb-5 mb-3" style="min-height: 80%;">
        <div id="help-text" class="text-center">
            <p>Help</p>
        </div>
        <div class="mx-md-5 px-sm-5" >
            <p><span>1. </span>Uma team é composta por um líder e membros</p>
            <p><span>2. </span>Uma equipa é identificada pelo nome, sendo este obrigatório, e pode ser categorizada por skill</p>
            <p><span>3. </span>É obrigatório a team ter um líder e este tem que ser único, isto é, apenas é líder de uma equipa</p>
            <p><span>4. </span>No lado esquerdo são apresentados os utilizadores que não têm equipa</p>
            <p><span>5. </span>No lado direito é apresentada constituição atual da equipa</p>
            <p><span>6. </span>Para adicionar um utilizador à equipa clica em <i class="fas fa-plus"></i></p>
            <p><span>7. </span>Para remover um membro da equipa clica em <i class="fas fa-fw fa-times text-danger"></i></p>
            <p><span>8. </span>Para promover um membro da equipa a lider clica em <i class="fas fa-user-tie" style="color:grey;"></i></p>
            <p><span>9. </span>Para guardar as alterções efetuadas clica em 
                <button id="action-btn" class="btn btn-outline-secondary"> Update / Create </a></button></p>
        </div>
    </div>
@endsection