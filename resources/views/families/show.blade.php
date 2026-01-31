@extends('layouts.sideBar')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('messages.family') }}: {{ $familyCode }}</h3>
                    <div>
                        <a href="{{ route('families.export', $familyCode) }}" class="btn btn-success">{{ __('messages.export_pdf') }}</a>
                        <a href="{{ route('families.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($membersData) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.name') }}</th>
                                        <th>{{ __('messages.membership_code') }}</th>
                                        <th>{{ __('messages.email') }}</th>
                                        <th>{{ __('messages.total_atraf') }}</th>
                                        <th>{{ __('messages.completed_atraf') }}</th>
                                        <th>{{ __('messages.last_etraf_date') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($membersData as $memberData)
                                    <tr>
                                        <td>{{ $memberData['user']->name }}</td>
                                        <td>{{ $memberData['user']->membership_code }}</td>
                                        <td>{{ $memberData['user']->email }}</td>
                                        <td>{{ $memberData['total_atraf'] }}</td>
                                        <td>{{ $memberData['completed_atraf'] }}</td>
                                        <td>{{ $memberData['last_etraf_date'] ? \Carbon\Carbon::parse($memberData['last_etraf_date'])->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('users.show', $memberData['user']->id) }}" class="btn btn-sm btn-info">{{ __('messages.view') }}</a>
                                            <a href="{{ route('atraf.user-report', $memberData['user']->id) }}" class="btn btn-sm btn-primary">{{ __('messages.user_report') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">{{ __('messages.no_data_found') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
