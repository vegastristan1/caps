<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Load Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Load Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isLoggedIn = localStorage.getItem('login') === 'true';
            if (!isLoggedIn) {
                localStorage.clear();
                window.location.href = '/caps';
            }
        });
    </script> -->
</head>

<body>
    <div id="navbar-placeholder"></div>
    <div class="main-content" id="mainContent">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a href="#">Rooms Monitoring</a></li>
                <li><a href="create.html">Room Directory Creator</a></li>
                <li class="active">List of Existing Rooms</li>
            </ul>
        </nav>
        <section class="section-header text-sm md:text-xl">
            <h1>LIST OF EXISTING ROOMS</h1>
        </section>

        <div class="flex items-center justify-between">
            <!-- Left section: Title, chevron, and pen icon -->
            <div class="flex items-center">
                <h1>
                    <button class="bi bi-arrow-clockwise"></button>
                    To Filter room display enter room number starts with
                    <input type="text" class="border rounded px-2 py-1 text-sm" placeholder="Room number" />
                    and click REFRESH to reload the page.
                </h1>
            </div>
            <div class="flex items-center justify-end ">
                <a href="create.html" style="background-color: #174069;"
                    class="bg-blue-900 text-white p-1 md:p-2 text-xs md:text-md flex items-center">+ Add Record
                </a>
            </div>
        </div>

        <div class="overflow-x-auto mt-6">
            <table class="min-w-full table-auto border-collapse">
                <thead style="background-color: #174069;" class="text-white">
                    <tr>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 200px;">Building</th>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 200px;">Floor</th>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 200px;">Room #</th>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 200px;">Late Inspection Date</th>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 200px;">Description</th>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 200px;">Type of Room</th>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 200px;">Status/Remarks</th>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 200px;">For Subject Assignment</th>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 200px;">Total of Capacity (No. of Students)</th>
                        <th class="px-4 py-2 border-b-4 border-orange-500 text-left" style="width: 100px;">Edit/Delete</th>
                    </tr>
                </thead>
                <tbody id="room-list-table">

                </tbody>
            </table>
        </div>
        <div class="border-b-4 border-black my-4"></div>


        <script>
            fetch('/Components/Navbar.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('navbar-placeholder').innerHTML = data;
                    // Load navbar script after inserting HTML
                    var script = document.createElement('script');
                    script.src = '/Components/app.js';
                    document.body.appendChild(script);
                });

            $(document).ready(function() {
                //REQUEST ROOM-LIST
                $.ajax({
                    url: 'http://localhost:3000/assign-room/room-list',
                    method: 'GET',
                    success: function(data) {
                        console.log(data)

                        var tableBody = $('#room-list-table');

                        tableBody.empty();

                        data.forEach(function(item) {
                            var formattedDate = item.last_date_inspection ? new Date(item.last_date_inspection) : '';
                            var subjectAssign = item.subject_assign > 0 ? `<a href="#" class="bi bi-check" style="color: green;"></a>` : `<a href="#" class="bi bi-x" style="color: red;"></a>`

                            if (formattedDate) {
                                var month = formattedDate.getMonth() + 1;
                                var day = formattedDate.getDate();
                                var year = formattedDate.getFullYear();

                                month = month < 10 ? '0' + month : month;
                                day = day < 10 ? '0' + day : day;

                                formattedDate = `${month}-${day}-${year}`;
                            }

                            tableBody.append(`
                        <tr class="bg-white" id=rm-${item.id}>
                            <td class="px-4 py-2 border border-gray-300">${item.building_id || ''}</td>
                            <td class="px-4 py-2 border border-gray-300">${item.room_floor || ''}</td>
                            <td class="px-4 py-2 border border-gray-300">${item.room_no || ''}</td>
                            <td class="px-4 py-2 border border-gray-300">${formattedDate || ''}</td>
                            <td class="px-4 py-2 border border-gray-300">${item.room_descr || ''}</td>
                            <td class="px-4 py-2 border border-gray-300">${item.room_type || ''}</td>
                            <td class="px-4 py-2 border border-gray-300">${item.room_status || ''}</td>
                            <td class="px-4 py-2 border border-gray-300">${subjectAssign}</td>
                            <td class="px-4 py-2 border border-gray-300">${item.room_capacity || ''}</td>
                            <td class="px-4 py-2 border border-gray-300">
                                <a href="#" class="bi bi-pencil icon-link" id="edit-rm" title="Edit" data-id="${item.id}"></a>
                                <a href="#" class="bi bi-trash icon-link" title="Delete" id="delete-rm" data-id="${item.id}"></a>
                            </td>
                        </tr>
                    `);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Request failed:', error);
                    }
                });


                $('#room-list-table').on('click', '#delete-rm', function(e) {
                    e.preventDefault();
                    const roomId = $(this).data('id');

                    if (confirm(`Are you sure you want to delete list ID ${roomId}?`)) {
                        $.ajax({
                            url: `http://localhost:3000/assign-room/delete-list?delete_id=${roomId}`,
                            type: 'POST',
                            success: function(response) {
                                alert('Room deleted successfully!');
                                $(`#rm-${roomId}`).remove(); // Remove the row from the table
                            },
                            error: function(xhr, status, error) {
                                console.error('Failed to delete room:', error);
                                alert('Failed to delete the room.');
                            }
                        });
                    }
                });


                $('#room-list-table').on('click', '#edit-rm', function(e) {
                    e.preventDefault();
                    const roomId = $(this).data('id');

                    window.location.href = `./edit.html?edit_id=${roomId}`
                });
            });
        </script>
</body>

</html>
<style scoped>
    .breadcrumb-nav {
        margin: 0;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .breadcrumb {
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        padding: 0;
    }

    .breadcrumb li {
        margin-right: 10px;
    }

    .breadcrumb li a {
        color: #174069;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb li a:hover {
        color: #20568B;
    }

    .breadcrumb li.active {
        color: orange;
        pointer-events: none;
    }

    .breadcrumb li::after {
        content: ">";
        margin-left: 10px;
        color: #174069;
    }

    .breadcrumb li:last-child::after {
        content: "";
    }

    .section-header {
        background-color: #174069;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;

    }

    .section-header h1 {
        color: white;
        margin: 0;
    }

    .icon-link {
        margin-right: 15px;
        text-decoration: none;
        color: #000000;
    }
</style>