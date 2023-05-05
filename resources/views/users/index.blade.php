<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WEB DEVELOPER TECHNICAL EXAM</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" class="form-container">
        @csrf
          
        <div class="form-row">
          <div class="form-col">
            <label for="name">Name:</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
          </div>
          
          <div class="form-col">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required style="
            height: 20px;
            padding-right: 10px;
        ">
          </div>
          
          <div class="form-col">
            <label for="phone_number">Phone Number:</label>
            <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required minlength="12" maxlength="12" pattern="[0-9]{12}">
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-col">
            <label for="email">Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required {{ $errors->has('email') || !Illuminate\Support\Facades\Validator::make(['email' => old('email')], ['email' => 'unique:users,email'])->passes() ? 'class=error' : '' }}>
            @if ($errors->has('email'))
              <span class="error-message">{{ $errors->first('email') }}</span>
            @endif
            @if (!Illuminate\Support\Facades\Validator::make(['email' => old('email')], ['email' => 'unique:users,email'])->passes())
              <span class="error-message">Email already exists</span>
            @endif
          </div>
          
          <div class="form-col profile-pic">
            <label for="profile_picture">Profile Picture:</label>
            <div class="pic-container">
              <img src="{{ asset('default-profile-pic.jpg') }}" alt="Default profile picture">
              <input type="file" name="profile_picture" id="profile_picture" hidden>
              
            </div>
          </div>
        </div>
        
        <button type="submit" id="submit_button">Save</button>
      </form>
      
    
    
    <table>
        <thead>
            <tr>
                <th>Profile Picture</th>
                <th>ID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Age</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        @if ($user->profile_picture)
                            <img src="{{ asset($user->profile_picture) }}" alt="{{ $user->name }}'s profile picture" height="50">
                        @else
                            <span>No picture</span>
                        @endif
                    </td>
                    <td>{{ $user->id }}</td>
                    <td class="editable" contenteditable="false">{{ $user->name }}</td>
                    <td class="editable" contenteditable="false">{{ $user->date_of_birth }}</td>
                    <td>{{ $user->age }}</td>
                    <td class="editable" contenteditable="false">{{ $user->phone_number }}</td>
                    <td class="editable" contenteditable="false">{{ $user->email }}</td>
                    <td>
                        <button class="edit" data-id="{{ $user->id }}">Edit</button>
                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display: inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('export') }}" class="export-link">Export Table</a>


<script>
    const editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(button => {
    button.addEventListener('click', function() {
    const row = this.parentNode.parentNode;
    const nameCell = row.querySelector('.editable:nth-child(3)');
    const dobCell = row.querySelector('.editable:nth-child(4)');
    const phoneCell = row.querySelector('.editable:nth-child(6)');
    
    const editButton = row.querySelector('.edit');

    if (nameCell.contentEditable === 'false') {
        nameCell.contentEditable = 'true';
        dobCell.innerHTML = '<input type="date" value="' + dobCell.textContent + '">';
        phoneCell.innerHTML = '<input type="number" value="' + phoneCell.textContent + '" min="12" max="12">';
        
        editButton.textContent = 'Save';
        nameCell.focus();
    } else {
        nameCell.contentEditable = 'false';
        dobCell.innerHTML = dobCell.firstChild.value;
        phoneCell.innerHTML = phoneCell.firstChild.value;
        
        editButton.textContent = 'Edit';

        const id = this.getAttribute('data-id');
        const name = nameCell.textContent;
        const dob = dobCell.textContent;
        const phone = phoneCell.textContent;

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        formData.append('name', name);
        formData.append('date_of_birth', dob);
        formData.append('phone_number', phone);

        fetch('{{ route("users.update", ":id") }}'.replace(':id', id), {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            nameCell.textContent = data.name;
            dobCell.innerHTML = data.date_of_birth;
            phoneCell.innerHTML = data.phone_number;
            
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});
});
</script>

</body>
</html>