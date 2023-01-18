var adminPanelUrl = $("#admin_url").val();
var adminImageUrl = $("#admin_image_url").val();
var ajaxCheck = false;

var somethingWrongMessage       = 'Something went wrong, please try again later.';
var confirmChangeStatusMessage  = 'Are you sure, you want to change the status?';

var confirmActiveStatusMessage  = 'Are you sure, you want to active?';
var confirmInactiveStatusMessage= 'Are you sure, you want to inactive?';
var confirmDeleteMessage        = 'Are you sure, you want to delete?';
var confirmCancelMessage        = 'Are you sure, you want to cancel?';
var confirmCompleteMessage      = 'Are you sure, you want to complete?';

var confirmParentDeleteMessage  = 'Are you sure, you want to delete? Related records will also delete.';

var confirmActiveSelectedMessage    = 'Are you sure, you want to active selected records?';
var confirmInactiveSelectedMessage  = 'Are you sure, you want to inactive selected records?';
var confirmDeleteSelectedMessage    = 'Are you sure, you want to delete selected records?';
var confirmCancelSelectedMessage    = 'Are you sure, you want to cancel selected records?';
var confirmCompleteSelectedMessage  = 'Are you sure, you want to complete selected records?';
var confirmDefaultMessage           = 'Are you sure, you want to set it default?';

var overallErrorMessage         = '';
var pleaseFillOneField          = 'You missed 1 field. It has been highlighted.';
var pleaseFillMoreFieldFirst    = 'You have missed ';
var pleaseFillMoreFieldLast     = ' fields. Please fill before submitted.';

var copiedToClipBoard           = 'Copied to clipboard';

var btnYes              = 'Yes';
var btnNo               = 'No';
var btnOk               = 'Ok';
var btnConfirmYesColor  = '#22ca80';
var btnCancelNoColor    = '#6c757d';
var successMessage      = 'Success';
var errorMessage        = 'Error';
var warningMessage      = 'Warning';
var infoMessage         = 'Info';
var btnSubmitting       = 'Submitting...';
var btnUpdating         = 'Updating...';
var btnSubmitPreloader  = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
var btnUpdatingPreloader = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
var btnSavingPreloader  = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
var btnLoadingPreloader = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
var btnSendingPreloader = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';

$.validator.addMethod("valid_email", function(value, element) {
    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid email.");

// Phone number eg. (+91)9876543210
$.validator.addMethod("valid_number", function(value, element) {
    if (/^(?=.*[0-9])[- +()0-9]+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid phone number.");

// Minimum 8 digit,small+capital letter,number,specialcharacter
$.validator.addMethod("valid_password", function(value, element) {
    if (/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

// Alphabet or number
$.validator.addMethod("valid_coupon_code", function(value, element) {
    if (/^[a-zA-Z0-9]+$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

// Positive number
$.validator.addMethod("valid_positive_number", function(value, element) {
    if (/^[0-9]+$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

// Integer and decimal
$.validator.addMethod("valid_amount", function(value, element) {
    if (/^[0-9]\d*(\.\d+)?$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

// Youtube url checking
$.validator.addMethod("valid_youtube_url", function(value, element) {
    if (/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

// Ckeditor
$.validator.addMethod("ckrequired", function (value, element) {  
    var idname = $(element).attr('id');  
    var editor = CKEDITOR.instances[idname];  
    var ckValue = GetTextFromHtml(editor.getData()).replace(/<[^>]*>/gi, '').trim();  
    if (ckValue.length === 0) {  
        //if empty or trimmed value then remove extra spacing to current control  
        $(element).val(ckValue);
    } else {  
        //If not empty then leave the value as it is  
        $(element).val(editor.getData());  
    }  
    return $(element).val().length > 0;  
}, "Please enter description.");
  
function GetTextFromHtml(html) {  
    var dv = document.createElement("DIV");
    dv.innerHTML = html;  
    return dv.textContent || dv.innerText || "";  
}


$(document).ready(function() {
    setTimeout(function() {
        $('.notification').slideUp(1000).delay(3000);
    }, 3000);

    // Password checker
    $('.password-checker').on('keyup', function () {
        var getVal = $(this).val();
        var dataAttrId = $(this).data('pcid');
        
        if (getVal != '') {
            if (/^[a-zA-Z_\-]+$/.test(getVal)) {  // weak
                $('#'+dataAttrId).html('<div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%"></div>');
            }
            if (/^(?=.*?[a-z])(?=.*?[A-Z]).{3,}$/.test(getVal)) {   // medium
                $('#'+dataAttrId).html('<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div>');
            }
            if (/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9]).{5,}$/.test(getVal)) {   // strong
                $('#'+dataAttrId).html('<div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>');
            }
            if (/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/.test(getVal)) {    // very strong
                $('#'+dataAttrId).html('<div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>');
            }
        } else {
            $('#'+dataAttrId).html('<div class="progress" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>');
        }
    });

    // Start :: Admin Login Form //
    $("#adminLoginForm").validate({
        ignore: [],
        debug: false,
        rules: {
            email: {
                required: true,
                valid_email: true
            },
            password: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Please enter email.",
                valid_email: "Please enter valid email."
            },
            password: {
                required: "Please enter password.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'password') {
                error.insertAfter($(element).parents('div#password_div'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnLoadingPreloader);
            $('#btn-processing').attr('disabled', true);
            form.submit();
        }
    });
    // End :: Admin Login Form //
    
    // Start :: Forgot Password Form //
    $("#forgotPasswordForm").validate({
        ignore: [],
        debug: false,
        rules: {
            email: {
                required: true,
                valid_email: true
            },
        },
        messages: {
            email: {
                required: "Please enter email.",
                valid_email: "Please enter valid email."
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSendingPreloader);
            $('#btn-processing').attr('disabled', true);
            form.submit();
        }
    });
    // End :: Forgot Password Form //

    // Start :: Reset Password Form //
    $("#resetPasswordForm").validate({
        ignore: [],
        debug: false,
        rules: {
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "Please enter new password.",
                valid_password: "Min. 8, alphanumeric and special character."
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character.",
                equalTo: "Password should be same as new password.",
            }
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('#btn-processing').attr('disabled', true);
            form.submit();
        }
    });
    // End :: Reset Password Form //

    // Start :: Profile Form //
    $("#updateProfileForm").validate({
        ignore: [],
        debug: false,
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter first name."
            },
            last_name: {
                required: "Please enter last name."
            },
            email: {
                required: "Please enter email.",
                valid_email: "Please enter valid email.",
            },
            phone_no: {
                required: "Please enter phone number.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-updating').html(btnUpdatingPreloader);
            $('#btn-updating').attr('disabled', true);
            $('#btn-cancel').addClass('pointer-none');
            form.submit();
        }
    });
    // End :: Profile Form //

    // Start :: Admin Password Form //
    $("#updateAdminPassword").validate({
        ignore: [],
        debug: false,
        rules: {
            current_password: {
                required: true,
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            }
        },
        messages: {
            current_password: {
                required: "Please enter current password.",
            },
            password: {
                required: "Please enter new password.",
                valid_password: "Min. 8, alphanumeric and special character."
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character.",
                equalTo: "Password should be same as new password.",
            }
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'current_password') {
                error.insertAfter($(element).parents('div#current_password_div'));
            } else if ($(element).attr('id') == 'password') {
                error.insertAfter($(element).parents('div#password_div'));
            } else if ($(element).attr('id') == 'confirm_password') {
                error.insertAfter($(element).parents('div#confirm_password_div'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-updating').html(btnUpdatingPreloader);
            $('#btn-updating').attr('disabled', true);
            $('#btn-cancel').addClass('pointer-none');
            form.submit();
        }
    });
    // End :: Admin Password Form //

    // Start :: Customer Form //
    $("#createCustomerForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            full_name: {
                required: true
            },
            email: {
                required: true,
                valid_email: true
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            },
        },
        messages: {
            full_name: {
                required: "Please enter name."
            },
            email: {
                required: "Please enter email.",
                valid_email: "Please enter valid email.",
            },
            password: {
                required: "Please enter new password.",
                valid_password: "Min. 8, alphanumeric and special character."
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character.",
                equalTo: "Password should be same as new password.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'distribution_area_id') {
                error.insertAfter($(element).parents('div#distribution-area-div'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });

    $("#updateCustomerForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            full_name: {
                required: true
            },
            email: {
                required: true,
                valid_email: true
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            },
        },
        messages: {
            full_name: {
                required: "Please enter name."
            },
            email: {
                required: "Please enter email.",
                valid_email: "Please enter valid email.",
            },
            password: {
                required: "Please enter new password.",
                valid_password: "Min. 8, alphanumeric and special character."
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character.",
                equalTo: "Password should be same as new password.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'distribution_area_id') {
                error.insertAfter($(element).parents('div#distribution-area-div'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });
    // End :: Customer Form //

    // Start :: Advertiser Form //
    $("#createAdvertiserForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            category_id: {
                required: true
            },
            full_name: {
                required: true
            },
            email: {
                required: true,
                valid_email: true
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            },
            booking_amount: {
                required: true,
                valid_amount:true,
            },
        },
        messages: {
            category_id: {
                required: "Please select category."
            },
            full_name: {
                required: "Please enter name."
            },
            email: {
                required: "Please enter email.",
                valid_email: "Please enter valid email.",
            },
            password: {
                required: "Please enter new password.",
                valid_password: "Min. 8, alphanumeric and special character."
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character.",
                equalTo: "Password should be same as new password.",
            },
            booking_amount: {
                required: "Please enter booking amount",
                valid_amount: "Please enter valid amount",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'category_id') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });

    $("#updateAdvertiserForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            category_id: {
                required: true
            },
            full_name: {
                required: true
            },
            email: {
                required: true,
                valid_email: true
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            },
            booking_amount: {
                required: true,
                valid_amount:true,
            },
        },
        messages: {
            category_id: {
                required: "Please select category."
            },
            full_name: {
                required: "Please enter name."
            },
            email: {
                required: "Please enter email.",
                valid_email: "Please enter valid email.",
            },
            password: {
                required: "Please enter new password.",
                valid_password: "Min. 8, alphanumeric and special character."
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character.",
                equalTo: "Password should be same as new password.",
            },
            booking_amount: {
                required: "Please enter booking amount",
                valid_amount: "Please enter valid amount",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'category_id') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });
    // End :: Advertiser Form //

    // Start :: Sub Admin Form //
    $("#createSubAdminForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                valid_email: true
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            },
            'role[]': {
                required: true,
            },
            'service_ids[]': {
                required: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter first name."
            },
            last_name: {
                required: "Please enter last name."
            },
            email: {
                required: "Please enter email.",
                valid_email: "Please enter valid email.",
            },
            password: {
                required: "Please enter new password.",
                valid_password: "Min. 8, alphanumeric and special character."
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character.",
                equalTo: "Password should be same as new password.",
            },
            'role[]': {
                required: "Please select role.",
            },
            'service_ids[]': {
                required: "Please select service.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'role' || $(element).attr('id') == 'service_ids') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });

    $("#updateSubAdminForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
                valid_email: true
            },
            'role[]': {
                required: true,
            },
            'service_ids[]': {
                required: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter first name."
            },
            last_name: {
                required: "Please enter last name."
            },
            email: {
                required: "Please enter email.",
                valid_email: "Please enter valid email.",
            },
            'role[]': {
                required: "Please select role.",
            },
            'service_ids[]': {
                required: "Please select service.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'role' || $(element).attr('id') == 'service_ids') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });
    // End :: Sub Admin Form //

    // Start :: Role Form //
    $("#createRoleForm").validate({
        ignore: [],
        debug: false,
        rules: {
            name: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter role."
            }
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });

    $("#updateRoleForm").validate({
        ignore: [],
        debug: false,
        rules: {
            name: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter role."
            }
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });
    // End :: Role Form //

    // Start :: Site Settings Form //
    $("#updateSettingsForm").validate({
        ignore: [],
        debug: false,
        rules: {
            from_email: {
                required: true,
                valid_email: true
            },
            to_email: {
                required: true,
                valid_email: true
            },
            'website_title': {
                required: true
            },
        },
        messages: {
            from_email: {
                required: "Please enter from email."
            },
            to_email: {
                required: "Please enter to email."
            },
            'website_title': {
                required: "Please enter website title."
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-updating').html(btnUpdatingPreloader);
            $('#btn-updating').attr('disabled', true);
            $('#btn-cancel').addClass('pointer-none');
            form.submit();
        }
    });
    // End :: Site Settings Form //

    // Start :: CMS Form //
    $("#createCmsForm").validate({
        ignore: [],
        debug: false,
        rules: {
            'page_name': {
                required: true
            },
            'title': {
                required: true
            },
            // description: {
            //     ckrequired: true,
            // },
        },
        messages: {
            'page_name': {
                required: "Please enter page name.",
            },
            'title': {
                required: "Please enter title.",
            },
            // description: {
            //     ckrequired: "Please enter description."
            // },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'description') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });

    $("#updateCmsForm").validate({
        ignore: [],
        debug: false,
        rules: {
            'page_name': {
                required: true
            },
            'title': {
                required: true
            },
            // description: {
            //     ckrequired: true,
            // },
        },
        messages: {
            'page_name': {
                required: "Please enter page name.",
            },
            'title': {
                required: "Please enter title.",
            },
            // description: {
            //     ckrequired: "Please enter description."
            // },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'description') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }            
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });
    // End :: CMS Form //

    // Start :: Category Form //
    $("#createCategoryForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            title: {
                required: true,
            },
            image: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title.",
            },
            image: {
                required: "Please upload an image.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });

    $("#updateCategoryForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });
    // End :: Category Form //

    // Start :: FAQ Form //
    $("#createFaqForm").validate({
        ignore: [],
        debug: false,
        rules: {
            question: {
                required: true
            },
            answer: {
                ckrequired: true,
            },
        },
        messages: {
            question: {
                required: "Please enter question.",
            },
            answer: {
                ckrequired: "Please enter answer."
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'description') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });

    $("#updateFaqForm").validate({
        ignore: [],
        debug: false,
        rules: {
            question: {
                required: true
            },
            answer: {
                ckrequired: true,
            },
        },
        messages: {
            question: {
                required: "Please enter question.",
            },
            answer: {
                ckrequired: "Please enter answer."
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'description') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }            
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });
    // End :: FAQ Form //

    // Start :: Event Category Form //
    $("#createEventCategoryForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });

    $("#updateEventCategoryForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            title: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });
    // End :: Event Category Form //

    // Start :: Event Form //
    $("#createEventForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            event_category_id: {
                required: true,
            },
            advertiser_id: {
                required: true,
            },
            title: {
                required: true,
            },
            price: {
                required: true,
                valid_amount:true,
            },
            start_date: {
                required: true,
            },
            // image: {
            //     required: true,
            // },
        },
        messages: {
            event_category_id: {
                required: "Please select event category.",
            },
            advertiser_id: {
                required: "Please select advertiser.",
            },
            title: {
                required: "Please enter title.",
            },
            price: {
                required: "Please enter price",
                valid_amount: "Please enter valid amount",
            },
            start_date: {
                required: "Please select date & time.",
            },
            // image: {
            //     required: "Please upload an image.",
            // },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'event_category_id' || $(element).attr('id') == 'advertiser_id') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });

    $("#updateEventForm").validate({
        ignore: ":hidden",
        debug: false,
        rules: {
            event_category_id: {
                required: true,
            },
            advertiser_id: {
                required: true,
            },
            title: {
                required: true,
            },
            price: {
                required: true,
                valid_amount:true,
            },
            start_date: {
                required: true,
            },
        },
        messages: {
            event_category_id: {
                required: "Please select event category.",
            },
            advertiser_id: {
                required: "Please select advertiser.",
            },
            title: {
                required: "Please enter title.",
            },
            price: {
                required: "Please enter price",
                valid_amount: "Please enter valid amount",
            },
            start_date: {
                required: "Please select date & time.",
            },
        },
        errorClass: 'error invalid-feedback',
        errorElement: 'div',
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(form, validator) {
            var numberOfInvalids = validator.numberOfInvalids();
            if (numberOfInvalids) {
                overallErrorMessage = numberOfInvalids == 1 ? pleaseFillOneField : pleaseFillMoreFieldFirst + numberOfInvalids + pleaseFillMoreFieldLast;
            } else {
                overallErrorMessage = '';
            }
            toastr.error(overallErrorMessage, errorMessage+'!');
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'event_category_id' || $(element).attr('id') == 'advertiser_id') {
                error.insertAfter($(element).parents('div.form-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $('#btn-processing').html(btnSavingPreloader);
            $('.preloader').show();
            form.submit();
        }
    });
    // End :: Event Form //
    

    /***************************** Start :: Data table and Common Functionalities ****************************/
    // Start :: Check / Un-check all for Admin Bulk Action (DO NOT EDIT / DELETE) //
	$('.checkAll').click(function() {
		if ($(this).is(':checked')) {
			$('.delete_checkbox').prop('checked', true);
		} else {
			$('.delete_checkbox').prop('checked', false);
		}
	});
	$(document).on('click', '.delete_checkbox', function(){
		var length = $('.delete_checkbox').length;
		var totalChecked = 0;
		$('.delete_checkbox').each(function() {
			if ($(this).is(':checked')) {
				totalChecked += 1;
			}
		});
		if (totalChecked == length) {
			$(".checkAll").prop('checked', true);
		}else{
			$('.checkAll').prop('checked', false);
		}
	});
    // End :: Check / Un-check all for Admin Bulk Action (DO NOT EDIT / DELETE) //

    /*************************************** End :: Data table and Common Functionalities ***************************************/

    /*Date range used in Admin user listing (filter) section*/
    //Restriction on key & right click
    // $('#registered_date').keydown(function(e) {
    //     var keyCode = e.which;
    //     if ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || keyCode === 8 || keyCode === 122 || keyCode === 32 || keyCode == 46) {
    //         e.preventDefault();
    //     }
    // });
    // $('#registered_date').daterangepicker({
    //     autoUpdateInput: false,
    //     timePicker: false,
    //     timePicker24Hour: true,
    //     timePickerIncrement: 1,
    //     startDate: moment().startOf('hour'),
    //     //endDate: moment().startOf('hour').add(24, 'hour'),
    //     locale: {
    //         format: 'YYYY-MM-DD'
    //     }
    // }, function(start_date, end_date) {
    //     $(this.element[0]).val(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
    // });

    // $('#purchase_date').daterangepicker({
    //     autoUpdateInput: false,
    //     timePicker: false,
    //     timePicker24Hour: true,
    //     timePickerIncrement: 1,
    //     startDate: moment().startOf('hour'),
    //     //endDate: moment().startOf('hour').add(24, 'hour'),
    //     locale: {
    //         format: 'YYYY-MM-DD'
    //     }
    // }, function(start_date, end_date) {
    //     $(this.element[0]).val(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
    // });

    // $('#contract_duration').daterangepicker({
    //     autoUpdateInput: false,
    //     timePicker: false,
    //     timePicker24Hour: true,
    //     timePickerIncrement: 1,
    //     startDate: moment().startOf('hour'),
    //     //endDate: moment().startOf('hour').add(24, 'hour'),
    //     locale: {
    //         format: 'YYYY-MM-DD'
    //     }
    // }, function(start_date, end_date) {
    //     $(this.element[0]).val(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
    // });

    /*Date range used in Coupon listing (filter) section*/
    //Restriction on key & right click
    // $('.date_restriction').keydown(function(e) {
    //     var keyCode = e.which;
    //     if ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || keyCode === 8 || keyCode === 122 || keyCode === 32 || keyCode == 46) {
    //         e.preventDefault();
    //     }
    // });
    // $('.date_restriction').daterangepicker({
    //     autoUpdateInput: false,
    //     timePicker: true,
    //     timePicker24Hour: true,
    //     timePickerIncrement: 1,
    //     startDate: moment().startOf('hour'),
    //     //endDate: moment().startOf('hour').add(24, 'hour'),
    //     locale: {
    //         format: 'YYYY-MM-DD HH:mm'
    //     }
    // }, function(start_date, end_date) {
    //     $(this.element[0]).val(start_date.format('YYYY-MM-DD HH:mm') + ' - ' + end_date.format('YYYY-MM-DD HH:mm'));
    // });

    // $('.datePicker').daterangepicker({
    //     singleDatePicker: true,
    //     showDropdowns: false,
    //     showWeekNumbers: false,
    //     showISOWeekNumbers: false,
    //     timePicker: true,
    //     timePicker24Hour: true,
    //     timePickerSeconds: false,
    //     autoApply: true,
    //     autoUpdateInput: true,
    //     alwaysShowCalendars: false,
    //     // startDate: moment().startOf('hour'),
    //     // endDate: moment().startOf('hour').add(24, 'hour'),
    //     // minDate: moment().startOf('hour'),
    //     locale: {
    //         format: 'YYYY-MM-DD HH:mm'
    //     }
    // }, function(start, end, label) {
    //     // $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm') + ' - ' + picker.endDate.format('YYYY-MM-DD HH:mm'));
    //     // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    // });

    // $('.datePickerInEdit').daterangepicker({
    //     singleDatePicker: true,
    //     showDropdowns: false,
    //     showWeekNumbers: false,
    //     showISOWeekNumbers: false,
    //     timePicker: true,
    //     timePicker24Hour: true,
    //     timePickerSeconds: false,
    //     autoApply: true,
    //     autoUpdateInput: true,
    //     alwaysShowCalendars: false,
    //     // startDate: moment().startOf('hour'),
    //     // endDate: moment().startOf('hour').add(24, 'hour'),
    //     minDate: moment().startOf('hour'),
    //     locale: {
    //         format: 'YYYY-MM-DD HH:mm'
    //     }
    // }, function(start, end, label) {
    //     // $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm') + ' - ' + picker.endDate.format('YYYY-MM-DD HH:mm'));
    //     // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    // });

    // $('.dateRangePicker').daterangepicker({
    //     singleDatePicker: false,
    //     showDropdowns: false,
    //     showWeekNumbers: false,
    //     showISOWeekNumbers: false,
    //     timePicker: true,
    //     timePicker24Hour: true,
    //     timePickerSeconds: false,
    //     autoApply: true,
    //     autoUpdateInput: true,
    //     alwaysShowCalendars: false,
    //     startDate: moment().startOf('hour'),
    //     // endDate: moment().startOf('hour').add(24, 'hour'),
    //     ranges: {
    //         'Today': [moment(), moment()],
    //         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //         'This Month': [moment().startOf('month'), moment().endOf('month')],
    //         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //     },
    //     locale: {
    //         format: 'YYYY-MM-DD HH:mm'
    //     }
    // }, function(start, end, label) {
    //     // $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm') + ' - ' + picker.endDate.format('YYYY-MM-DD HH:mm'));
    //     // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    // });

    // $('.dateRangePickerInEdit').daterangepicker({
    //     singleDatePicker: false,
    //     showDropdowns: false,
    //     showWeekNumbers: false,
    //     showISOWeekNumbers: false,
    //     timePicker: true,
    //     timePicker24Hour: true,
    //     timePickerSeconds: false,
    //     autoApply: true,
    //     autoUpdateInput: true,
    //     alwaysShowCalendars: false,
    //     // startDate: moment().startOf('hour'),
    //     // endDate: moment().startOf('hour').add(24, 'hour'),
    //     ranges: {
    //         'Today': [moment(), moment()],
    //         'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //         'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //         'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //         'This Month': [moment().startOf('month'), moment().endOf('month')],
    //         'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //     },
    //     locale: {
    //         format: 'YYYY-MM-DD HH:mm'
    //     }
    // }, function(start, end, label) {
    //     // $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm') + ' - ' + picker.endDate.format('YYYY-MM-DD HH:mm'));
    //     // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    // });

    // $("#settlement_status").select2();
});

// Start :: Admin List Actions //
function listActions(routePrefix, actionRoute, id, actionType, dTable) {
    var message = actionUrl = '';

    if (actionRoute != '') {
        actionUrl = adminPanelUrl+'/'+routePrefix+'/'+actionRoute+'/'+id;
    }

    if (actionType == 'active') {
        message = confirmActiveStatusMessage;
    } else if (actionType == 'inactive') {
        message = confirmInactiveStatusMessage;
    } else if (actionType == 'cancel') {
        message = confirmCancelMessage;
    } else if (actionType == 'complete') {
        message = confirmCompleteMessage;
    } else if (actionType == 'delete') {
        message = confirmDeleteMessage;
    } else {
        message = somethingWrongMessage;
    }
    
    if (actionUrl != '') {
        swal.fire({
            text: message,
            type: 'warning',
            allowOutsideClick: false,
            confirmButtonColor: btnConfirmYesColor,
            cancelButtonColor: btnCancelNoColor,
            showCancelButton: true,
            confirmButtonText: btnYes,
            cancelButtonText: btnNo,
        }).then((result) => {
            if (result.value) {
                $('.preloader').show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: actionUrl,
                    method: 'GET',
                    data: {},
                    success: function (response) {
                        $('.preloader').hide();
                        if (response.type == 'success') {
                            dTable.draw();
                            toastr.success(response.message, response.title+'!');
                        } else if (response.type == 'warning') {
                            toastr.warning(response.message, response.title+'!');
                        } else {
                            toastr.error(response.message, response.title+'!');
                        }
                    }
                });
            }
        });
    } else {
        toastr.error(message, errorMessage+'!');
    }
}
// End :: Admin List Actions //

// Start :: Admin List Bulk Actions //
function bulkActions(routePrefix, actionRoute, selectedIds, actionType, dTable) {
    var message = actionUrl = '';
    
    if (actionRoute != '') {
        actionUrl = adminPanelUrl+'/'+routePrefix+'/'+actionRoute;
    }

    if (actionType == 'active') {
        message = confirmActiveSelectedMessage;
    } else if (actionType == 'inactive') {
        message = confirmInactiveSelectedMessage;
    } else if (actionType == 'cancel') {
        message = confirmCancelSelectedMessage;
    } else if (actionType == 'complete') {
        message = confirmCompleteSelectedMessage;
    } else if (actionType == 'delete') {
        message = confirmDeleteSelectedMessage;
    } else {
        message = somethingWrongMessage;
    }
    
    if (actionUrl != '') {
        swal.fire({
            text: message,
            type: 'warning',
            allowOutsideClick: false,
            confirmButtonColor: btnConfirmYesColor,
            cancelButtonColor: btnCancelNoColor,
            showCancelButton: true,
            confirmButtonText: btnYes,
            cancelButtonText: btnNo,
        }).then((result) => {
            if (result.value) {
                $('.preloader').show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: actionUrl,
                    method: 'POST',
                    data: {
                        actionType: actionType,
                        selectedIds: selectedIds,
                    },
                    success: function (response) {
                        $('.preloader').hide();
                        if (response.type == 'success') {
                            $('.checkAll').prop('checked', false);
                            dTable.draw();
                            toastr.success(response.message, response.title+'!');
                        } else if (response.type == 'warning') {
                            $('.checkAll').prop('checked', false);
                            dTable.draw();
                            toastr.warning(response.message, response.title+'!');
                        } else {
                            toastr.error(response.message, response.title+'!');
                        }
                    }
                });
            }
        });
    } else {
        toastr.error(message, errorMessage+'!');
    }
}
// End :: Admin List Bulk Actions //

// Start :: Admin Gallery Actions //
function galleryAction(routePrefix, actionRoute, id, albumId, rowId, actionType, selectedDefaultImage) {
    var message = actionUrl = '';

    if (actionRoute != '') {
        actionUrl = adminPanelUrl+'/'+routePrefix+'/'+actionRoute;
    }

    if (actionType == 'set-default') {
        message = confirmDefaultMessage;
    } else if (actionType == 'delete-image') {
        message = confirmDeleteMessage;
    } else {
        message = somethingWrongMessage;
    }
    
    if (actionUrl != '') {
        swal.fire({
            text: message,
            type: 'warning',
            allowOutsideClick: false,
            confirmButtonColor: btnConfirmYesColor,
            cancelButtonColor: btnCancelNoColor,
            showCancelButton: true,
            confirmButtonText: btnYes,
            cancelButtonText: btnNo,
        }).then((result) => {
            if (result.value) {
                $('.preloader').show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: actionUrl,
                    method: 'POST',
                    data: {
                        id: id,
                        albumId: albumId,
                        actionType: actionType
                    },
                    success: function (response) {
                        $('.preloader').hide();
                        if (response.type == 'success') {
                            if (actionType == 'delete-image') {
                                $('#image_'+rowId).remove();
                                toastr.success(response.message, response.title+'!');
                            } else if (actionType == 'set-default') {
                                $('.delete_block_'+rowId).remove();
                                window.location.reload();
                            }                            
                        } else {
                            toastr.error(response.message, response.title+'!');
                        }
                    }
                });
            } else {
                window.location.reload();
            }
        });
    } else {
        toastr.error(message, errorMessage+'!');
    }
}
// End :: Admin Gallery Actions //

// Start :: Admin Delete uploaded image //
$(document).on('click', '.delete-uploaded-preview-image', function() {
    var primaryId   = $(this).data('primaryid');
    var imageId     = $(this).data('imageid');
    var dbField     = $(this).data('dbfield');
    var routePrefix = $(this).data('routeprefix');
    var message = actionUrl = '';

    if (primaryId != '' && routePrefix != '') {
        actionUrl = adminPanelUrl+'/'+routePrefix+'/'+'delete-uploaded-image';
    }

    message = confirmDeleteMessage;
    
    if (actionUrl != '') {
        swal.fire({
            text: message,
            type: 'warning',
            allowOutsideClick: false,
            confirmButtonColor: btnConfirmYesColor,
            cancelButtonColor: btnCancelNoColor,
            showCancelButton: true,
            confirmButtonText: btnYes,
            cancelButtonText: btnNo,
        }).then((result) => {
            if (result.value) {
                $('.preloader').show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: actionUrl,
                    method: 'POST',
                    data: {
                        primaryId: primaryId,
                        dbField: dbField,
                    },
                    success: function (response) {
                        $('.preloader').hide();
                        if (response.type == 'success') {
                            $('.preview_img_div_'+dbField).html('');
                            $('.preview_img_div_'+dbField).html('<img id="'+dbField+'_preview" class="mt-2" style="display: none;" />');
                            toastr.success(response.message, response.title+'!');
                        } else if (response.type == 'warning') {
                            toastr.warning(response.message, response.title+'!');
                        } else {
                            toastr.error(response.message, response.title+'!');
                        }
                    }
                });
            }
        });
    } else {
        toastr.error(message, errorMessage+'!');
    }
});
// Start :: Admin Delete upladed image //

// Start :: Delete uploaded cropped image //
$(document).on('click', '.delete-uploaded-cropped-image', function() {
    var primaryId       = $(this).data('primaryid');
    var dbField         = $(this).data('dbfield');
    var imgContainer    = $(this).data('img-container');
    var routePrefix     = $(this).data('routeprefix');
    var message = actionUrl = '';

    if (primaryId != '' && routePrefix != '') {
        actionUrl = adminPanelUrl+'/'+routePrefix+'/'+'delete-uploaded-image';
    }
    
    message = confirmDeleteMessage;
    
    if (actionUrl != '') {
        swal.fire({
            text: message,
            type: 'warning',
            allowOutsideClick: false,
            confirmButtonColor: btnConfirmYesColor,
            cancelButtonColor: btnCancelNoColor,
            showCancelButton: true,
            confirmButtonText: btnYes,
            cancelButtonText: btnNo,
        }).then((result) => {
            if (result.value) {
                $('.preloader').show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: actionUrl,
                    method: 'POST',
                    data: {
                        primaryId: primaryId,
                        dbField: dbField,
                    },
                    success: function (response) {
                        $('.preloader').hide();
                        if (response.type == 'success') {
                            $('#preview-crop-image').html('');
                            $('#preview-crop-image').removeAttr('style');
                            $('#preview-crop-image').attr('style', 'height:'+imgContainer+'px; padding: 42px 2px 20px 2px; position: relative;');
                            if (dbField == 'profile_pic') {
                                $('#profile-pic-holder').html('<img id="admin-avatar" src="'+adminImageUrl+'/users/avatar5.png" class="rounded-circle" width="40" />');
                            }
                            toastr.success(response.message, response.title+'!');
                        } else if (response.type == 'warning') {
                            toastr.warning(response.message, response.title+'!');
                        } else {
                            toastr.error(response.message, response.title+'!');
                        }
                    }
                });
            }
        });
    } else {
        toastr.error(message, errorMessage+'!');
    }
});
// End :: Delete upladed cropped image //

// Start :: Slug generation on key up event //
$('.slug-generation').focusout(function () {
    var modelName   = $(this).data('model');
    var getTitle    = $(this).val();    
    var id          = '';
    if ($('#id').length) {
        var id = $('#id').val();
    }
    generateSlug(modelName, getTitle, id);
});
function generateSlug(modelName, title, id) {
    if (title.length > 3) {
        var actionUrl = adminPanelUrl+'/'+'generate-slug';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: actionUrl,
            method: 'POST',
            data: {
                modelName: modelName,
                title: title,
                id: id
            },
            success: function (response) {
                if (response.type === 'success') {
                    $('#slug').val(response.slug);
                } else {
                    $('#slug').val('');
                }
            }
        });
    } else {
        $('#slug').val('');
    }
}
// End :: Slug generation on key up event //


function sweetalertMessageRender(target, message, type, confirm = false) {
    let options = {
        icon: type,
        title: 'warning!',
        text: message,
        
    };
    if (confirm) {
        options['showCancelButton'] = true;
        options['confirmButtonText'] = 'Yes';
    }

    return Swal.fire(options)
    .then((result) => {
        if (confirm == true && result.value) {
            window.location.href = target.getAttribute('data-href'); 
        } else {
            return (false);
        }
    });
}

// Click to copy
$(document).on('click', '.clickToCopy', function(e) {
    e.preventDefault();
    var type        = $(this).data('type');
    var copyText    = $(this).data('values');

    document.addEventListener('copy', function(e) {
        e.clipboardData.setData('text/plain', copyText);
        e.preventDefault();
    }, true);
  
    document.execCommand('copy');

    toastr.success(copiedToClipBoard);

    if (type == 'email') {
        $('#email').focus();
    } else {
        $('#password').focus();
    }
});