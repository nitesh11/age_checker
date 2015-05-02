var age_checker = {};
(function ($) {

  $(document).ready(function() {

    var agegate_bg = $('.age-checker-bg img').attr('src');
    $('#age_checker').css('background-image', 'url(' + agegate_bg + ')');
    $('.age-checker-bg').remove();

    $('#age_checker_country').change(function() {
      var selected_country = $(this).find('option:selected').val();
      var cookie_name = "country_selected=" + selected_country + "; path=/";
      document.cookie = cookie_name;
      location.reload();
    });
  });

  // Function to verify the age limits.
  age_checker.verify = function () {

    var now   = new Date(Drupal.settings.age_checker.currentdate);
    var date  = now.getDate();
    var month = now.getMonth() + 1;
    var year  = now.getFullYear();
    var age_checker_day   = $("#age_checker_day").val();
    var age_checker_month = $("#age_checker_month").val();
    var age_checker_year  = $("#age_checker_year").val();
    var age               = year - age_checker_year;
    var dobdate           = new Date(age_checker_year, age_checker_month - 1, age_checker_day).getTime();
    var today             = now.getTime();
    var threshold_age     = Drupal.settings.age_checker.threshold_age;
    var day_placeholder = Drupal.settings.age_checker.day_placeholder;
    var month_placeholder = Drupal.settings.age_checker.month_placeholder;
    var year_placeholder = Drupal.settings.age_checker.year_placeholder;
    var leapyear          = ((age_checker_year % 4 === 0) && (age_checker_year % 100 !== 0)) || (age_checker_year % 400 === 0);
    var blank_err_message = Drupal.settings.age_checker.blank_err_message;
    var under_age_err_msg = Drupal.settings.age_checker.under_age_err_msg;
    var dateformat_error  = Drupal.settings.age_checker.dateformat_error;
    var date_range_err_msg = Drupal.settings.age_checker.date_range_err_msg;
    var remember_me = $('#age_checker_remember_me:checked').val();
    var destination = Drupal.settings.age_checker.destination;

    if ( age_checker_month > month ) {
      age--;
    }
    else {
      if( age_checker_month == month && age_checker_day > date )
        age--;
    }
    // if current year, form not set
    if ( age_checker_month === '' || age_checker_day === '' || age_checker_year === '' || age_checker_month === month_placeholder || age_checker_day === day_placeholder || age_checker_year === year_placeholder ) {
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(blank_err_message);
      return false;
    }
    else if ( (age_checker_year < 1900) || (age_checker_year > year) ) {
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(date_rnge_err_msg);
      return false;
    }
    else if ( age_checker_year.length != 4 ) {
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(dateformat_error);
      return false;
    }
    else if ( (age_checker_month < 1 || age_checker_month > 12) ){
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(dateformat_error);
      return false;
    }
    else if ( age_checker_day < 1 || age_checker_day > 31 ) {
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(dateformat_error);
      return false;
    }
    else if ( (age_checker_month == 4 || age_checker_month == 6 || age_checker_month == 9 || age_checker_month == 11) && age_checker_day == 31 ) {
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(dateformat_error);
      return false;
    }
    else if ( age_checker_month == 2 && (age_checker_day == 29 && !leapyear || age_checker_day>29) ) {
      // check for february 29th
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(dateformat_error);
      return false;
    }
    else if( today - dobdate < 0 ) {
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(date_range_err_msg);
      return false;
    }
    else if (!parseInt(age_checker_month, 10) || !parseInt(age_checker_day, 10) || !parseInt(age_checker_year, 10) ) {
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(dateformat_error);
      return false;
    }
    else if ( age < threshold_age ) {
      document.getElementById('age_checker_error_message').innerHTML = Drupal.t(under_age_err_msg);
      setTimeout("age_checker.deny()", 2000);
      return false;
    }
    else {
      // age limit ok
      if(remember_me == 'undefined') {
        var cookie_name = "age_checker=1; path=/; expires: " + Drupal.settings.age_checker.cookie_expiration;
        document.cookie = cookie_name;
      }
      window.location = destination;
    }
    return true;
  };

  age_checker.deny = function() {
    // Redirect user elsewhere.
    var language_code = jQuery('#languagecode').val();
    var language_value;
    if( language_code === undefined || language_code === null || language_code.length <= 0 ) {
      language_value = '';
    }
    else {
      language_value = language_code + "/";
    }
    var redirect_url = Drupal.settings.age_checker.redirecturl;
    if ( redirect_url.indexOf('http://') > -1 || redirect_url.indexOf('https://') > -1 ) {
      window.location = redirect_url;
      return false;
    }
    else if ( redirect_url.indexOf('node') > -1 ) {
      window.location = Drupal.settings.basePath + redirect_url;
      return false;
    }
    else {
      window.location = Drupal.settings.basePath + language_value  +  redirect_url;
      return false;
    }
  };

})(jQuery);


function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) === ' ')
      c = c.substring(1);
    if (c.indexOf(name) === 0)
      return c.substring(name.length, c.length);
  }
  return "";
}

