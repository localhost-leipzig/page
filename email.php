<?php
define('EMAIL', 'ping@localhost-leipzig.de');

function respondWith($code, $message) {
  if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    http_response_code($code);
    echo $message;
  } else {
    header('Location: index.html');
  }
}

// Only process POST reqeusts.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $filter = [
    'name' => FILTER_SANITIZE_STRING,
    'email' => FILTER_VALIDATE_EMAIL,
    'subject' => FILTER_SANITIZE_STRING,
    'message' => FILTER_SANITIZE_STRING
  ];
  $input = filter_input_array(INPUT_POST, $filter);

  $valid = true;
  foreach ($filter as $k => $v) {
    if ($input[$k] === false) {
      $valid = false;
      break;
    }
  }

  // Check that data was sent to the mailer.
  if (!$valid) {
    respondWith(400, "Hmm, hier wurde nicht alles korrekt ausgefüllt. Bitte versuche es nochmal.");
    exit;
  }

  // Build the email content.
  $email_content = sprintf("Name: %s\n", $input['name']);
  $email_content .= sprintf("Email: %s\n\n", $input['email']);
  $email_content .= sprintf("Message:\n%s\n", $input['message']);

  // Build the email headers.
  $email_headers = sprintf('From: %s <%s>', $input['name'], $input['email']);

  // Send the email.
  $result = mail(EMAIL, $input['subject'], $email_content, $email_headers);
  if ($result !== false) {
    respondWith(200, "Danke wir melden uns so schnell wie möglich.");
  } else {
    respondWith(500, "Hmm, leider konnte die Nachricht nicht versendet werden. Ruf doch einfach mal an 0341/22.");
  }
} else {
  respondWith(403, "Leider kann diese Anfrage nicht bearbeitet werden.");
}
