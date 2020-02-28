@extends('layouts.app')

@section('title', (__('label.create') . ' Menu | '))

@section('content')

                    <div class="row">
                        <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card shadow mb-4 border-left-primary">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">{{ __('label.add_new') }} Menu</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                        <a href="{{ route('menus.index') }}" class="btn btn-secondary btn-icon-split btn-sm"
                                            title="{{ __('label.back') }}">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-arrow-left"></i>
                                            </span>
                                            <span class="text">{{ __('label.back') }}</span>
                                        </a>
                                    </div>
                                    <form method="POST" action="{{ route('menus.store') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        @include ('admin.menus.form', ['formMode' => 'create'])
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

@endsection
