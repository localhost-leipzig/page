<?php
define('EMAIL', 'ping@localhost-leipzig.de');

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
    // Set a 400 (bad request) response code and exit.
    http_response_code(400);
    echo "Oops! There was a problem with your submission. Please complete the form and try again.";
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
    // Set a 200 (okay) response code.
    http_response_code(200);
    echo "Danke wir melden uns so schnell wie möglich.";
  } else {
    // Set a 500 (internal server error) response code.
    http_response_code(500);
    echo "Oops! Something went wrong and we couldn't send your message.";
  }
} else {
  // Not a POST request, set a 403 (forbidden) response code.
  http_response_code(403);
  echo "There was a problem with your submission, please try again.";
}
