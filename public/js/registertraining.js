// Function tab Tab
function showTabs(){
    showTab(1);

    document.getElementById('tab1').addEventListener('click', function() {
        showTab(1);
    });
    document.getElementById('tab2').addEventListener('click', function() {
        showTab(2);
    });
    document.getElementById('tab3').addEventListener('click', function() {
        showTab(3);
    });
    
    function showTab(tabIndex) {
        // Hide all tab content
        document.querySelectorAll('.tab-content').forEach(function(content) {
            content.classList.add('hidden');
        });
    
        // Remove active class from all tabs
        document.querySelectorAll('ul li a').forEach(function(tab) {
            tab.classList.remove('text-violet-400', 'border-b-2', 'border-violet-400', 'bg-white');
            tab.classList.add('text-gray-600');
        });
    
        // Show the selected tab's content
        document.getElementById('content' + tabIndex).classList.remove('hidden');
    
        // Highlight the active tab
        const activeTab = document.getElementById('tab' + tabIndex);
        activeTab.classList.add('text-violet-400', 'border-b-2', 'border-violet-400', 'bg-white');
        activeTab.classList.remove('text-gray-600');
    }
}

function submitForm1() {
    $('#submitBtn').click(function (e) {
        e.preventDefault(); // Prevent default form submission

        var formData = {
            '_token': $('input[name="_token"]').val(),
            'id': $('#trainingId').val(),  // Menambahkan ID untuk update
            'name_pic': $('#name_pic').val(),
            'name_company': $('#name_company').val(),
            'email_pic': $('#email_pic').val(),
            'phone_pic': $('#phone_pic').val(),
        };

        console.log(formData);  // Log the data being sent

        // Clear previous response message
        $('#responseMessage').removeClass('hidden').text('');

        $.ajax({
            url: '/dashboard/user/training/form/save', // URL untuk save
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    $('#responseMessage').addClass('text-green-500').removeClass('text-red-500')
                        .text('Data berhasil disimpan atau diperbarui!');
                } else {
                    $('#responseMessage').addClass('text-red-500').removeClass('text-green-500')
                        .text('Gagal menyimpan data. Silakan coba lagi.'); 
                } 
            },
            error: function (xhr, status, error) {
                $('#responseMessage').addClass('text-red-500').removeClass('text-green-500')
                    .text('Terjadi kesalahan. Coba lagi.');
            }
        });
    });
} 

let form_id = 1; // Misalnya, ini adalah form_id yang dapat Anda ambil dari server atau halaman saat ini

function addInputField() {
    // Create a new div to hold the input and buttons
    const newInputGroup = document.createElement('div');
    newInputGroup.classList.add('flex', 'items-center', 'space-x-2', 'mb-2');
    
    // Create the input field
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.classList.add('form-control', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-md');
    newInput.placeholder = 'Nama Peserta';
    
    // Set an ID to each input so you can identify them later
    newInput.setAttribute('id', 'input-participant-' + Date.now()); // unique id based on timestamp
    
    // Create the add button
    const newAddButton = document.createElement('button');
    newAddButton.classList.add('text-lg', 'font-bold', 'text-blue-500', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-md', 'hover:bg-gray-100');
    newAddButton.textContent = '+';
    newAddButton.onclick = addInputField; // Add the same functionality to the new button
    
    // Create the remove button
    const newRemoveButton = document.createElement('button');
    newRemoveButton.classList.add('text-lg', 'font-bold', 'text-red-500', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-md', 'hover:bg-gray-100');
    newRemoveButton.textContent = '-';
    newRemoveButton.onclick = function() {
        newInputGroup.remove(); // Remove the entire input group when the remove button is clicked
    };
    
    // Append input, add button, and remove button to the new input group
    newInputGroup.appendChild(newInput);
    newInputGroup.appendChild(newAddButton);
    newInputGroup.appendChild(newRemoveButton);
    
    // Append the new input group to the container
    document.getElementById('input-fields-container').appendChild(newInputGroup);
}

function sendForm2(){
    let participants = [];
    // Loop through all the input fields and collect their values
    const inputs = document.querySelectorAll('#input-fields-container input');
    
    inputs.forEach(input => {
        participants.push({
            name: input.value,
            form_id: form_id // Each participant will get the same form_id
        });
    });

    // Now send this data to the server using an AJAX call
    $.ajax({
        url: '/your-api-endpoint', // Ganti dengan endpoint yang sesuai
        method: 'POST',
        data: {
            participants: participants,
            _token: '{{ csrf_token() }}' // Pastikan CSRF token tersedia
        },
        success: function(response) {
            console.log('Data berhasil dikirim', response);
        },
        error: function(error) {
            console.error('Error mengirim data', error);
        }
    });
}

// function send data
$(document).ready(function() {
   showTabs();
   submitForm1();
   addInputField();
   sendForm2();
});
