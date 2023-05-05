<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf
    
        <label for="name">Name:</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
    
        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
    
        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required>
    
        <label for="email">Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    
        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" id="profile_picture">
        <button type="button" id="upload_button">Upload Picture</button>
    
        <button type="submit" id="submit_button">Save</button>
    </form>
    


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Age</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Profile Picture</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->date_of_birth }}</td>
                <td>{{ $user->age }}</td>
                <td>{{ $user->phone_number }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->profile_picture)
                        <img src="{{ $user->profile_picture }}" alt="{{ $user->name }}">
                    @else
                        No Picture
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>