@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('messages.families') }}</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('families.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search') }}..." value="{{ $search }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">{{ __('messages.search') }}</button>
                            </div>
                        </div>
                    </form>

                    @if(count($families) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.family_code') }}</th>
                                        <th>{{ __('messages.members_count') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($families as $family)
                                    <tr>
                                        <td>{{ $family['code'] }}</td>
                                        <td>{{ count($family['members']) }}</td>
                                        <td>
                                            <a href="{{ route('families.show', $family['code']) }}" class="btn btn-sm btn-info">{{ __('messages.view') }}</a>
                                            <a href="{{ route('families.export', $family['code']) }}" class="btn btn-sm btn-success">{{ __('messages.export_pdf') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">{{ $search ? __('messages.no_data_found') : __('messages.please_search') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
