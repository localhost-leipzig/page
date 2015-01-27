'use strict';

(function (window, document, $, google) {
  // Shrink navigation
  var $document = $(document),
    checkScrolling = function () {
      if ($document.scrollTop() > 50) {
        $('nav').addClass('navbar-shrink');
      } else {
        $('nav').removeClass('navbar-shrink');
      }
    };
  $(window).scroll(checkScrolling);
  checkScrolling();

  // Closes the Responsive Menu on Menu Item Click
  $('.navbar-collapse ul li a').click(function () {
    $('.navbar-toggle:visible').click();
  });

  $('.add-subject').bind('click', function(e) {
    var $el = $(this),
        subject = $el.data('subject'),
        $formName = $('#name'),
        $formSubject = $('#subject');

    if (subject) {
      $formSubject.val(subject);
      $formName[0].focus();
    }
  });

  // Form validation
  $('#contact-form').bind('submit', function(e) {
    e.preventDefault();

    var $form = $(this),
        $modal = $('#contact .modal'),
        data = $form.serialize();

    $.ajax({
      url: this.action,
      type: 'POST',
      data: data,
      cache: false
    }).done(function(response) {
      $('.modal-title', $modal).text("Nachricht erfolgreich gesendet.");
      $('.modal-body', $modal).text(response);
      $modal.modal('show');

      // Clear the form.
      $('#name').val('');
      $('#email').val('');
      $('#subject').val('');
      $('#message').val('');
    }).fail(function(data) {
      $('.modal-title', $modal).text("Fehler beim senden.");
      $('.modal-body', $modal).text(data.responseText);
      $modal.modal('show');
    });
  });

  // GoogleMaps integration
  // create a LatLng object containing the coordinate for the center of the map
  var latlng = new google.maps.LatLng(51.3347931, 12.3814102);

  // prepare the map properties
  var options = {
    zoom: 16,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    navigationControl: true,
    mapTypeControl: false,
    scrollwheel: false,
    disableDoubleClickZoom: true
  };

  // initialize the map object
  var map = new google.maps.Map(document.getElementById('map'), options);

  // add Marker
  var marker1 = new google.maps.Marker({
    position: latlng,
    map: map
  });

  // add listener for a click on the pin
  google.maps.event.addListener(marker1, 'click', function () {
    infowindow.open(map, marker1);
  });

  // add information window
  var infowindow = new google.maps.InfoWindow({
    content: '<div class="info"><strong>localhost Leipzig GbR</strong><br><br>Sternwartenstraße 31<br>04103 Leipzig</div>'
  });

})(window, document, jQuery, google);
