$(document).ready(function () {

    $.validator.addMethod("smallLetters", function (value, element) {
        var regexp = new RegExp("[a-z]");
        return value.match(regexp);
    }, "Пароль должен содержать минимум одну маленькую английскую букву");

    $.validator.addMethod("bigLetters", function (value, element) {
        var regexp = new RegExp("[A-Z]");
        return value.match(regexp);
    }, "Пароль должен содержать минимум одну большую английскую букву");

    $.validator.addMethod("hasNumber", function (value, element) {
        var regexp = new RegExp("[0-9]");
        return value.match(regexp);
    }, "Пароль должен содержать минимум одну цифру");

    $.validator.addMethod("password", function (value, element) {
        var regexp = new RegExp("^[a-zA-Z0-9]+$");
        return value.match(regexp);
    }, "Пароль некорректен");


    $("#studentEdit").validate({
        rules: {
            login: {
                required: true,
                minlength: 3,
                remote: {
                    url: "/verification/login/" + $('#login').attr('user-id'),
                    type: "post",
                }
            },
            password: {
                required: function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                },
                minlength:  function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return 8;
                },
                smallLetters:  function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                },
                bigLetters:  function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                },
                hasNumber:  function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                },
                password: function () {
                    if ($("#password").hasClass('d-none')) {
                        return false;
                    }
                    return true;
                }
            },
            name: {
                required: true
            },
            patronymic: {
                required: true
            },
            surname: {
                required: true
            },
            student_ticket: {
                required: true
            },
            email: {
                email: true,
                remote: {
                    url: "/admin/verification/email/" + $('#email').attr('user-id'),
                    type: "post",
                }
            }
        },
        messages: {
            login: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пожалуйста, введите не менее 3 символов",
                remote: "Этот логин уже занят"
            },
            password: {
                required: "Это поле обязательно для заполнения",
                minlength: "Пожалуйста, введите не менее 8 символов"
            },
            name: {
                required: "Это поле обязательно для заполнения"
            },
            patronymic: {
                required: "Это поле обязательно для заполнения"
            },
            surname: {
                required: "Это поле обязательно для заполнения"
            },
            student_ticket: {
                required: "Это поле обязательно для заполнения"
            },
            email: {
                email: "Некорректный email",
                remote: "Этот email уже занят"
            }
        },
    });
});