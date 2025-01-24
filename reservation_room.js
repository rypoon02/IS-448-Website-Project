document.addEventListener('DOMContentLoaded', function () {
    fetchBuildings();
});

function fetchBuildings() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'reservation_room_getbuilding.php', true);
    xhr.onload = function () {
        if (this.status === 200) {
            var buildings = JSON.parse(this.responseText);
            var buildingSelect = document.getElementById('res_building');
            buildingSelect.innerHTML = '<option disabled selected value="">Select Building</option>';
            buildings.forEach(function (building) {
                var option = document.createElement('option');
                option.value = building;
                option.textContent = building;
                buildingSelect.appendChild(option);
            });
        }
    };
    xhr.send();
}

function filterRoomsByTime(rooms, startTime, endTime) {
    return rooms.filter(function (room) {
        return startTime >= room.room_open &&
            endTime <= room.room_close &&
            room.room_occupied != 1; // Filter out occupied rooms
    });
}


function filterAndCreateButtons() {
    var buildingName = document.getElementById('res_building').value;
    var startTime = document.getElementById('start_time').value;
    var endTime = document.getElementById('end_time').value;
    var whiteboard = document.getElementById('whiteboard').checked ? 'whiteboard' : '';
    var smartboard = document.getElementById('smartboard').checked ? 'smartboard' : '';

    if (!buildingName || !startTime || !endTime) return;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_filtered_rooms.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            var rooms = JSON.parse(this.responseText);
            var filteredRooms = filterRoomsByTime(rooms, startTime, endTime);
            var filteredRoomsDiv = document.getElementById('filteredRooms');
            filteredRoomsDiv.innerHTML = '';

            filteredRooms.forEach(function (room) {
                var form = document.createElement('form');
                form.method = 'post';
                form.action = 'reservation_room.php';

                var roomDetails = `Room: ${room.room_name}, Capacity: ${room.room_capacity}, Equip: ${room.room_equip}, Time: ${room.room_open} - ${room.room_close}`;
                var h3 = document.createElement('h3');
                h3.textContent = roomDetails;
                form.appendChild(h3);

                var buttonDiv = document.createElement('div');
                buttonDiv.classList.add('button-container');

                var button = document.createElement('input');
                button.type = 'submit';
                button.value = 'Reserve';
                button.classList.add('reserve-button');

                var hiddenRoomId = document.createElement('input');
                hiddenRoomId.type = 'hidden';
                hiddenRoomId.name = 'room_id';
                hiddenRoomId.value = room.room_id;

                var hiddenBuildingName = document.createElement('input');
                hiddenBuildingName.type = 'hidden';
                hiddenBuildingName.name = 'building_name';
                hiddenBuildingName.value = buildingName;

                var hiddenStartTime = document.createElement('input');
                hiddenStartTime.type = 'hidden';
                hiddenStartTime.name = 'start_time';
                hiddenStartTime.value = startTime;

                var hiddenEndTime = document.createElement('input');
                hiddenEndTime.type = 'hidden';
                hiddenEndTime.name = 'end_time';
                hiddenEndTime.value = endTime;

                var hiddenWhiteboard = document.createElement('input');
                hiddenWhiteboard.type = 'hidden';
                hiddenWhiteboard.name = 'whiteboard';
                hiddenWhiteboard.value = whiteboard;

                var hiddenSmartboard = document.createElement('input');
                hiddenSmartboard.type = 'hidden';
                hiddenSmartboard.name = 'smartboard';
                hiddenSmartboard.value = smartboard;

                // Add hidden field for redirection
                var hiddenRedirect = document.createElement('input');
                hiddenRedirect.type = 'hidden';
                hiddenRedirect.name = 'redirect';
                hiddenRedirect.value = 'reservation_info_main.html';

                buttonDiv.appendChild(button);
                form.appendChild(buttonDiv);
                form.appendChild(hiddenRoomId);
                form.appendChild(hiddenBuildingName);
                form.appendChild(hiddenStartTime);
                form.appendChild(hiddenEndTime);
                form.appendChild(hiddenWhiteboard);
                form.appendChild(hiddenSmartboard);
                form.appendChild(hiddenRedirect);
                filteredRoomsDiv.appendChild(form);
            });
        }
    };
    xhr.send(`building_name=${encodeURIComponent(buildingName)}&start_time=${encodeURIComponent(startTime)}&end_time=${encodeURIComponent(endTime)}&whiteboard=${whiteboard}&smartboard=${smartboard}`);
}


function getRoom() {
    var buildingName = document.getElementById('res_building').value;
    var startTime = document.getElementById('start_time').value;
    var endTime = document.getElementById('end_time').value;

    if (!buildingName || !startTime || !endTime) return;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'reservation_room_getroom.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            var rooms = JSON.parse(this.responseText);
            var filteredRooms = filterRoomsByTime(rooms, startTime, endTime);
            var roomSelect = document.getElementById('res_room_number');
            roomSelect.innerHTML = '<option disabled selected value="">Select Room</option>';
            filteredRooms.forEach(function (room) {
                var option = document.createElement('option');
                option.value = room.room_id;
                option.textContent = room.room_name;
                roomSelect.appendChild(option);
            });
        }
    };
    xhr.send('building_name=' + encodeURIComponent(buildingName) + '&start_time=' + encodeURIComponent(startTime) + '&end_time=' + encodeURIComponent(endTime));
}

