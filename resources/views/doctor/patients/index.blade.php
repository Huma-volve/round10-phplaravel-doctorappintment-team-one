@extends('master')


@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Patients</h6>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        <tr>
                            <th scope="row">{{ $patient->id }}</th>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->email }}</td>
                            <td>{{ $patient->phone }}</td>
                            <td class="d-flex gap-1">
                                <a style="font-size:10px; background-color: #e1f3ff" class="p-1 px-2 rounded text-center"
                                    href="{{ route('doctor.patients.show', $patient->id) }}">
                                    <i class="fa-regular fa-eye text-primary"></i>
                                </a>
                                <a style="font-size:10px; background-color: #fff3cf" class="p-1 px-2 rounded text-center"
                                    href="{{ route('doctor.patients.edit', $patient->id) }}" style="">
                                    <i class="fa-regular fa-pen-to-square text-warning"></i>
                                </a>
                                <form style="font-size:10px" action="{{ route('doctor.patients.delete', $patient->id) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="font-size:10px; background-color: #ffe0e3"
                                        class="border-0 py-1 px-2 rounded">
                                        <i class="fa-regular fa-trash-can text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection