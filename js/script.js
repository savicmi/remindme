$(function () {
    
    // Hide all inactive tabs
    var init_target = $('.tab-group > li.active').children('a');
    $('.panel-body > div').not(init_target.attr('href')).hide();
    $('.panel-heading h1').text(init_target.last().text());

    // Confirmation page should show the content
    $('.panel2 .panel-body > div').show();

    if ($('#time_zone').length > 0) {
        var fields = {
            action: 'time_zones'
        };

        $.post('remindme.php',
                fields,
                function (data) {
                    $('#time_zone').append(data.options);
                },
                'json'
            );
    }


    // Display Sign-Up, Log-In form and other forms
    $('.tab a').on('click', function (event) {
        // The default action of the event will not be triggered
        event.preventDefault();

        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');

        var target = $(this).attr('href');
        if (target != '#logoutTab') {
            $('.panel-heading h1').text($(this).last().text());
            $('.panel-body > div').not(target).hide();
        }
        $(target).fadeIn(500);
    });

    $("input[required='required']").prev().append('<span class="req">*</span>');

    $("form :input").each(function () {
        $(this).attr('title', tooltipMessage($(this)));
    });

    $('input').tooltip({
        placement: "top",
        trigger: "focus",
        animation: true
    });

    // Register new user
    $('#register').click(function (event) {
        event.preventDefault();        
        var $formObject = $(this).closest('form');
        var valid = validate($formObject.attr('id'));

        if (valid == 0) {
            var fields = {
                action: 'register',
                firstname: $.trim($formObject.find('#firstname').val()),
                lastname: $.trim($formObject.find('#lastname').val()),
                email: $.trim($formObject.find('#email').val()),
                username: $.trim($formObject.find('#username').val()),
                password: $.trim($formObject.find('#password').val())
            };

            $.post('remindme.php',
                fields,
                function (data) {                    
                    if (data.success == 1) {
                        $('#messageModal').on('show.bs.modal', function () {
                            var modal = $(this);
                            modal.find('.modal-title').text('Thank you for registering!');
                            modal.find('.modal-body p').text('Your account has been created and a verification email has been sent to your email address. Please click on the verification link included in the email to activate your account.');
                            modal.find('.modal-content').removeClass('modal-fail').addClass('modal-success');
                        })
                    }
                    else {
                        $('#messageModal').on('show.bs.modal', function () {
                            var modal = $(this);
                            modal.find('.modal-title').text('Account has not been created!');
                            modal.find('.modal-body p').text('The user with the same user name or email address already exists. Please try again.');
                            modal.find('.modal-content').removeClass('modal-success').addClass('modal-fail');
                        })
                    }
                    $("#messageModal").modal({ backdrop: "static" });
                },
                'json'
            );
        }

        
    });

    // Log in
    $('#login').click(function (event) {
        event.preventDefault();
        var $formObject = $(this).closest('form');
        var valid = validate($formObject.attr('id'));

        if (valid == 0) {
            var fields = {
                action: 'login',
                username: $.trim($formObject.find('#username').val()),
                password: $.trim($formObject.find('#password').val())
                //remember: ($formObject.find('#remember').is(":checked") ? 1:0)
            };

            $.post('remindme.php',
                fields,
                function (data) {
                    if (data.success == 1) {
                        // successful login - redirect to user's page
                        window.location.href = 'my_reminder.php';
                    }
                    else {
                        $('#messageModal').on('show.bs.modal', function () {
                            var modal = $(this);
                            modal.find('.modal-title').text('Failed to login!');
                            modal.find('.modal-body p').text(data.message);
                            modal.find('.modal-content').removeClass('modal-success').addClass('modal-fail');
                        })
                    }
                    $("#messageModal").modal({ backdrop: "static" });
                },
                'json'
            );
        }

    });

    // Log in now
    $('#goto-login').click(function (event) {
        var myWindow = window.open("index.php", "_self");
    });

    // Log out button
    $("a[href='#logoutTab']").click(function (event) {
        ajaxLogout();
    });

    // Ajax call for logout
    function ajaxLogout() {
        var fields = {
            action: 'logout'
        };

        $.post('remindme.php',
                fields,
                function (data) {
                    if (data.success == 1) {
                        // successful logout - redirect to user's page
                        window.location.href = 'index.php';
                    }
                },
                'json'
            );
    }

    // My profile button
    $("a[href='#myProfileTab']").click(function (event) {
        var fields = {
            action: 'profile'
        };

        $.post('remindme.php',
                fields,
                function (data) {
                    $('#myProfileTab').find('#username').text(data.username);
                    $('#myProfileTab').find('#date_reg').text(data.date);
                    $('#userProfile').find('#firstname').val(data.first_name);
                    $('#userProfile').find('#lastname').val(data.last_name);
                    $('#userProfile').find('#email').val(data.email);
                },
                'json'
            );
    });

    // Save user data
    $('#save').click(function (event) {
        event.preventDefault();
        var $formObject = $('#userProfile');
        var valid = validate($formObject.attr('id'));

        if (valid == 0) {

            var fields = {
                action: 'save',
                firstname: $.trim($formObject.find('#firstname').val()),
                lastname: $.trim($formObject.find('#lastname').val()),
                email: $.trim($formObject.find('#email').val()),
                password: $.trim($formObject.find('#password').val()),
                timezone: $.trim($( "#time_zone" ).val())
            };

            $.post('remindme.php',
                fields,
                function (data) {
                    if (data.success == 1) {
                        $('#messageModal').on('show.bs.modal', function () {
                            var modal = $(this);
                            modal.find('.modal-title').text('Successful update data!');
                            modal.find('.modal-body p').text('Your data have been successfully updated. You can continue with your activities.');
                            modal.find('.modal-content').removeClass('modal-fail').addClass('modal-success');
                        })
                    }
                    else if (data.success == 2) {
                        $('#messageModal').on('show.bs.modal', function () {
                            var modal = $(this);
                            modal.find('.modal-title').text('Successful update data!');
                            modal.find('.modal-body p').text('Since you have changed your e-mail address, you\'ll have to verify your new email. Please click on the verification link included in the email that was sent to you.');
                            modal.find('.modal-content').removeClass('modal-fail').addClass('modal-success');

                            $('.modal-content').find('button').click(function (event) {
                                ajaxLogout();
                            });
                        })
                    }
                    else {
                        $('#messageModal').on('show.bs.modal', function () {
                            var modal = $(this);
                            modal.find('.modal-title').text('Failed update data!');
                            modal.find('.modal-body p').text('That email address is already taken.');
                            modal.find('.modal-content').removeClass('modal-success').addClass('modal-fail');
                        })
                    }
                    $("#messageModal").modal({ backdrop: "static" });
                },
                'json'
            );
        }
    });

    // Tooltip messages
    function tooltipMessage(object) {
        switch(object.attr('id')) {
            case 'firstname':
                return 'The First name must contains between 2 and 20 letters. Spaces are also allowed.';
                break;
            case 'lastname':
                return 'The Last name must contains between 2 and 20 letters. Spaces are also allowed.';
                break;
            case 'username':
                return 'Username must contains between 4 and 18 characters. It\'s permitted to use english letters, numbers and one underscore between them.';
                break;
            case 'password':
                return 'Password must contains between 6 and 18 characters. It\'s permitted to use english letters and numbers and it starts with a letter. Password is case-sensitive.';
                break;
            default:
                return '';
        }
    }

    // Input data validation
    function validate(form_id) {

        var errors = 0;
        $("form#"+form_id+" :input").each(function () {
            var input = $(this);          

            // Condition for firstname, allow between 2 and 20 letters including spaces
            if (input.attr('id') == 'firstname') { 
                if (/^([a-z ]|[^\u0000-\u007F$]){2,20}$/i.test($.trim(input.val()))) {
                    input.next().slideUp().text('');
                    input.parent().removeClass('has-error');
                }
                else {
                    input.parent().addClass('has-error');
                    errors++;
                    if (input.next().is(":hidden")) {
                        input.next().slideDown("slow").text('Invalid the First name. See tooltip for the correct format.');
                    }
                }
            }

            // Condition for lastname, allow between 2 and 20 letters including spaces
            if (input.attr('id') == 'lastname') {
                if (/^([a-z ]|[^\u0000-\u007F$]){2,20}$/i.test($.trim(input.val()))) {
                    input.next().slideUp().text('');
                    input.parent().removeClass('has-error');
                }
                else {
                    input.parent().addClass('has-error');
                    errors++;
                    if (input.next().is(":hidden")) {
                        input.next().slideDown("slow").text('Invalid the Last name. See tooltip for the correct format.');
                    }
                }
            }

            // Condition for email
            if (input.attr('id') == 'email') {
                if (/^([\w'-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i.test($.trim(input.val()))) {
                    input.next().slideUp().text('');
                    input.parent().removeClass('has-error');
                }
                else {
                    input.parent().addClass('has-error');
                    errors++;
                    if (input.next().is(":hidden")) {
                        input.next().slideDown("slow").text('Invalid Email address.');
                    }
                }
            }

            // Condition for username, allow between 4 and 18 characters (english letters, numbers and one underscore between them)
            if (input.attr('id') == 'username') {
                if (/^([a-z0-9]+_?[a-z0-9]+)+$/i.test($.trim(input.val())) && ($.trim(input.val())).length>=4 && ($.trim(input.val())).length<=18) {
                    input.next().slideUp().text('');
                    input.parent().removeClass('has-error');
                }
                else {
                    input.parent().addClass('has-error');
                    errors++;
                    if (input.next().is(":hidden")) {
                        input.next().slideDown("slow").text('Invalid username. See tooltip for the correct format.');
                    }
                }
            }

            // Condition for password, allow between 6 and 18 characters (it starts with english letter and then letters and numbers are allowed.)
            if (input.attr('id') == 'password') {
                if (/^[a-z][a-z0-9]{5,17}$/i.test($.trim(input.val()))) {
                    input.next().slideUp().text('');
                    input.parent().removeClass('has-error');
                }
                else {
                    if (($.trim(input.val())).length === 0 && form_id == 'userProfile') {
                        // keep the the current password
                        input.next().slideUp().text('');
                        input.parent().removeClass('has-error');
                    }
                    else {
                        input.parent().addClass('has-error');
                        errors++;
                        if (input.next().is(":hidden")) {
                            input.next().slideDown("slow").text('Invalid password. See tooltip for the correct format.');
                        }
                    }   
                }
            }

            // Checking for equality of passwords
            if (input.attr('id') == 'password2') {
                if (/^[a-z][a-z0-9]{5,17}$/i.test($.trim(input.val())) && $.trim(input.val()) === $.trim($("input[id='password']").val())) {
                    input.next().slideUp().text('');
                    input.parent().removeClass('has-error');
                }
                else {
                    if (($.trim(input.val())).length === 0 && ($.trim($("input[id='password']").val())).length === 0 && form_id == 'userProfile') {
                        // keep the the current password
                        input.next().slideUp().text('');
                        input.parent().removeClass('has-error');
                    } else {
                        input.parent().addClass('has-error');
                        errors++;
                        if (input.next().is(":hidden")) {
                            input.next().slideDown("slow").text('Passwords are not equal.');
                        }
                    }
                }
            }                    

        });
        return errors;
        
    }

});