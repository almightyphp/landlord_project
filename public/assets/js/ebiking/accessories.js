$(document).ready(function() {
    $("#price_hide").hide();
    // var count = <?= $count ?>;
    $('#dataTabel').DataTable();
    $(document).on('click', 'a[data-role=insert]', function() {
        console.log("clicked");

        $('#editUserName').val('');
        $('#editUserpassword').val('');
        $('#editUserFullName').val('');
        $('#editUserAddress').val('');
        $('#editUserEmail').val('');
        $('#example-date-input').val('');
        $('#editUserRoleName').val('');
        $('#userId').val('');
        $('#exampleModal').modal('toggle');
    });

    $(document).on('click', 'a[data-role=update]', function() {

        var id = $(this).data('id');
        console.log("clicked" + id);

        $.ajax({
            url: "<?php echo base_url(); ?>/users/editUser",
            method: "post",
            data: {
                id: id
            },
            success: function(response) {
                console.log(response)
                console.log(response['username']);
                var userName = response['username'];
                console.log(userName);
                var userPassword = response['password'];

                var userEmail = response['email'];
                var userFullName = response['fullname'];
                var userAddress = response['address'];
                var userJoinDate = response['joiningdate'];
                var userRoleName = response['type'];

                console.log["hello"];
                console.log(userPassword);
                console.log(userEmail);
                console.log(userFullName);
                console.log(userAddress);
                console.log(userJoinDate);
                console.log(userRoleName);

                $('#editUserName').val(userName);
                $('#editUserpassword').val(userPassword);
                $('#editUserFullName').val(userFullName);
                $('#editUserAddress').val(userAddress);
                $('#editUserEmail').val(userEmail);
                $('#example-date-input').val(userJoinDate);
                $('#editUserRoleName').val(userRoleName);
                $('#userId').val(id);
                $('#exampleModal').modal('toggle');
            }
        });
    });

    $('#save').click(function() {
        var id = $('#userId').val();
        var userName = $('#editUserName').val();
        var userPassword = $('#editUserpassword').val();
        var userEmail = $('#editUserEmail').val();
        var userFullName = $('#editUserFullName').val();
        var userAddress = $('#editUserAddress').val();
        var userJoinDate = $('#example-date-input').val();
        var userRoleName = $('#editUserRoleName').val();


        console.log(id);

        $.ajax({
            url: "<?php echo base_url(); ?>/users/addUser",
            method: "post",
            data: {
                id: id,
                userName: userName,
                userPassword: userPassword,
                userEmail: userEmail,
                userRoleName: userRoleName
            },
            success: function(response) {
                console.log(response);
                if (response == "Data Updated") {
                    console.log(response);
                    $('#' + id).children('td[data-target=userFullName]').text(userFullName);
                    $('#' + id).children('td[data-target=userRoleName]').text(userRoleName);
                    $('#exampleModal').modal('toggle');

                } else {
                    for (i = 0; i < response.length; i++) {
                        var html = "";
                        html += '<tr data-target="fullRow" class = "fullRow" id=' + response[i]['id'] + '><td data-target="idSection"><div class="form-check font-size-16"><input class="form-check-input" value=' + response[i]['id'] + ' type="checkbox" id="orderidcheck01"><label class="form-check-label" for="orderidcheck01"></label></div></td>';
                        html += '<td data-target="idSection2"><a href="javascript: void(0);" id="roleID" class="text-body fw-bold">' + (i + 1) + '</a></td>';
                        html += '<td  data-target="userFullName">' + response[i]['username'] + '</td>';
                        html += '<td  data-target="userRoleName">' + response[i]['type'] + '</td>';
                        html += '<td data-target="roleAction"><div class="d-flex gap-3"><a href="#" id="update" data-role="update" data-id=' + response[i]['id'] + '><i class="mdi mdi-pencil font-size-18"></i></a><a href="#" id="deleteData" data-role="deleteData" data-id="' + response[i]['id'] + '" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="mdi mdi-delete font-size-18"></i></a></div></td>';
                        // html += '<td data-target="roleDetails"><div class="d-flex gap-3"><button type="button" data-role="viewDetails" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-id="' + response['id'] +'">View Details</button></div></td><tr>';
                    }
                    $("#table-data").append(html);
                    $('#exampleModal').modal('toggle');
                    $("#fullTable").load("index #fullTable");

                }
            }
        });
    });

    $(document).on('click', 'a[data-role=deleteData]', function() {
        var id = $(this).data('id');
        console.log("clicked" + id);

        $("#delete_single_row").on('click', function() {

            $.ajax({
                url: "<?php echo base_url(); ?>/users/userDelete",
                method: "post",
                data: {
                    id: id
                },
                success: function(response) {
                    $("#" + id).remove();
                    $('#deleteModal').modal('toggle');
                    console.log(response);
                    $("#fullTable").load("index #fullTable");

                }
            });
        });
    });

    $("#confirm_btn").on('click', function() {
        console.log('clicked me');
        var id = [];

        $(":checkbox:checked").each(function(key) {
            id[key] = $(this).val();
        });

        console.log(id);
        $.ajax({
            url: "<?php echo base_url(); ?>/users/deleteMultiple",
            method: "post",
            data: {
                id: id
            },
            success: function(response) {
                for (let x of id) {
                    $("#" + x).remove();
                }
                console.log(response);
                $('#staticBackdrop').modal('toggle');
                $("#fullTable").load("index #fullTable");

            }
        });
    });

    $(document).on('click', 'a[data-role=viewDetails]', function() {
        var id = $(this).data('id');
        console.log("click" + id);


        $.ajax({
            url: "<?php echo base_url(); ?>/users/viewDetails",
            method: "post",
            data: {
                id: id
            },
            success: function(response) {
                console.log(response['username']);
                var details = "";
                details += '<tr><td id="detailsUserName">' + response['usersname'] + '</td>';
                details += '<td id="detailsPassword">' + response['password'] + '</td>';
                details += '<td id="detailsEmail">' + response['email'] + '</td>';
                details += '<td id="detailsFullName">' + response['fullname'] + '</td>';
                details += '<td id="detailsAddress">' + response['address'] + '</td>';
                details += '<td id="detailsJionDate">' + response['joiningdate'] + '</td>';
                details += '<td id="detailsImage">' + response['image'] + '</td>';
                details += '<td id="detailsRole">' + response['role'] + '</td></tr>';
                var viewDetails = response;
                console.log(viewDetails);
                $('#pUserName').text(response['username']);
                $('#pPassword').text(response['password']);
                $('#pEmail').text(response['email']);
                $('#pAddress').text(response['address']);
                $('#pFullName').text(response['fullname']);
                $('#pJoiningDate').text(response['joiningdate']);
                var userImage = response['image']
                if (userImage == "") {
                    userImage = "user.png";
                }
                console.log(userImage);
                $('#pImage').text(userImage);
                // $('#photoss').html('<img src="<?php echo base_url(); ?>/upload/screenshoot/'+userImage+'" class="img-responsive">');
                $('#pImage').attr("src", "<?php echo base_url(); ?>uploads/users/" + userImage);
                $('#myLargeModalLabel').modal('toggle');
                $("#fullTable").load("index #fullTable");
            }
        })
    });

    $("input[name=is_free]").change(function() {
        if (this.value == 0) {
            $("#price_hide").show();
        } else {
            $("#price_hide").hide();
        }
    });
});