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
            tab.classList.remove('text-violet-400', 'border-b-2', 'border-blue-600', 'bg-white');
            tab.classList.add('text-gray-600');
        });
    
        // Show the selected tab's content
        document.getElementById('content' + tabIndex).classList.remove('hidden');
    
        // Highlight the active tab
        const activeTab = document.getElementById('tab' + tabIndex);
        activeTab.classList.add('text-violet-400', 'border-b-2', 'border-blue-600', 'bg-white');
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


// function send data
$(document).ready(function() {
   showTabs();
   submitForm1();
});
