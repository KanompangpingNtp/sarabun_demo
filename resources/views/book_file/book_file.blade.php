@extends('layout.layout')
@section('layout')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<div class="container">
    <table class="table table-bordered table-striped" id="data_table">
        <thead>
            <tr>
                <th class="text-center">เลขที่</th>
                <th class="text-center">วันที่</th>
                <th class="text-center">เรื่อง</th>
                <th class="text-center">action</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach($receivedbooks as $receivedbook)
            <tr>
                <td>{{ $receivedbook->id}}</td>
                <td>{{ $receivedbook->received_date}}</td>
                <td>{{ $receivedbook->subject}}</td>
                <td>
                    <a href="{{ route('viewFile', $receivedbook->id) }}" class="btn btn-info"><i class="bi bi-eye"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="{{asset('js/follow_book.js')}}"></script>

@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" defer></script>
