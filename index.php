<!DOCTYPE html>
<html>

<head>
  <title>Sheffield Hackspace Membership Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="https://www.sheffieldhackspace.org.uk/assets/favicons/favicon.ico" />

  <link rel="stylesheet" href="public/picnic.css" />
  <style>
    * {
      box-sizing: border-box;
    }

    @font-face {
      font-family: "Big Noodle Titling";
      src: url("public/big_noodle_titling.ttf");
    }

    @font-face {
      font-family: "DINPro";
      src: url("public/DINPro-Medium.ttf");
    }

    body {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    body>* {
      flex-shrink: 0;
    }

    header,
    main,
    footer {
      max-width: 40rem;
      margin: 1rem;
      background-color: hsl(199, 80.80%, 87.10%);
      color: black;
    }

    #bg-image {
      position: fixed;
      z-index: -1;
      filter: saturate(100%) blur(5px);
      filter: saturate(40%) blur(5px);
    }

    header {
      max-width: 95vw;
      font-family: "Big Noodle Titling";
      padding: 2rem;
      border-radius: 1rem;
      min-width: min(80vw, 30rem);
      text-align: center;
      font-size: 2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    header img {
      height: 3rem;
      width: auto;
    }

    .incomplete {
      color: red;
    }

    main {
      font-family: "DINPro";
      padding: 2rem;
      border-radius: 1rem;
      width: min(40rem, 90vw);
    }

    form label.above {
      display: inline-block;
      margin-top: 1rem;
    }

    .required::after {
      content: " *";
      color: red;
    }

    form input {
      max-width: 100%;
    }

    form .privacy {
      opacity: 0.8;
      font-size: 1rem;
      font-style: italic;
    }

    form button.submit {
      margin: 0 auto;
    }

    form hr.bottom {
      margin-top: 3rem;
    }

    .optional {
      margin-left: 1rem;
      width: calc(100% - 1rem);
    }

    footer {
      font-family: "DINPro";
      padding: 2rem;
      border-radius: 1rem;
      font-size: 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    footer p {
      margin: 0.25rem 0;
    }

    @media (width < 50rem) {
      header {
        font-size: 1rem;
      }

      header img {
        display: block;
      }

    }
  </style>
</head>

<body>
  <img id="bg-image" src="public/soldering-header.jpg" />
  <header>
    <h1>
      <img
        src="https://www.sheffieldhackspace.org.uk/assets/images/logo.svg"
        height="40"
        width="40" />
      Sheffield Hackspace Membership Form
    </h1>
  </header>
  <?php

  ?>
  <main>
    <h2>Instructions</h2>
    <p>To become a member you must:</p>
    <ol>
      <li>fill in and submit this form</li>
      <li>
        pay the <span class="incomplete">current membership rates</span>
      </li>
    </ol>
    <p>For the process for becoming a keyholder (24/7 access), see <a href="https://wiki.shhm.uk/dokuwiki/doku.php?id=faq">the wiki</a>.</p>
    <hr />
    <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $env = parse_ini_file('.env');

      $msg = "";
      foreach ($_POST as $key => $value) {
        $msg = $msg . htmlspecialchars($key) . ": " . htmlspecialchars($value) . ", ";
      }

      $message = $msg;

      try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = $env["SMTP_HOST"];
        $mail->SMTPAuth   = true;
        $mail->Username   = $env["SMTP_USERNAME"];
        $mail->Password   = $env["SMTP_PASSWORD"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $env["SMTP_PORT"];

        //Recipients
        $mail->setFrom($env["SMTP_FROM"], 'Mailer');
        $mail->addAddress($env["SMTP_TO"]);

        //Content
        $mail->Subject = 'New Membership Form Entry';
        $mail->Body    = $msg;

        if (!$env["DISABLE_EMAIL"]) {
          $mail->send();
        } else {
          echo "<span style='color:#f008;'>no email sent… DISABLE_EMAIL set in env file</span>";
        }
    ?>
        <p>Your application was sent!</p>
        <a href="/">back</a>
      <?php
      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    } else {

      ?>
      <h2>Membership Sign-Up</h2>
      <form method="POST" action="" autocomplete="off">
        <label class="above required" for="name">Full Name</label>
        <input
          type="text"
          name="name"
          id="name"
          autocomplete="name"
          required="true" />

        <label class="above required" for="address">Address ("<a href="https://en.wikipedia.org/wiki/Service_address">Service address</a>")</label>
        <input type="text" name="address" id="address" required="true" />

        <label class="above required" for="email">Email</label>
        <input
          type="text"
          name="email"
          id="email"
          autocomplete="email"
          required="true" />

        <p class="optional">Have you been a member of a hackspace before? (optional)</p>
        <input
          type="radio"
          name="memberbefore"
          id="memberbefore_yes"
          value="yes" />
        <label for="memberbefore_yes" class="checkable optional">Yes</label>
        <input
          type="radio"
          name="memberbefore"
          id="memberbefore_no"
          value="no" />
        <label for="memberbefore_no" class="checkable optional">No</label>

        <label class="above optional" for="discord">Discord Username (optional)</label>
        <input
          class="optional"
          type="text"
          name="discord"
          id="discord"
          autocomplete="discord" />

        <label class="above optional" for="interests">
          Do you have any specific interests? (optional)
        </label>
        <textarea class="optional" name="interests" id="interests"></textarea>

        <p class="privacy">
          Sheffield Hackspace does not currently have a privacy policy (<a href="https://wiki.shhm.uk/dokuwiki/doku.php?id=info:privacy">why?</a>).
        </p>
        <p class="privacy">
          Sheffield Hackspace will use the data above to: manage membership, subscriptions, and space access; keep track of tool inductions; and to send emails about general meetings and other organisational changes.
        </p>
        <p class="privacy">
          Sheffield Hackspace stores this information on a password-protected <a href="https://nextcloud.com/">NextCloud</a> Drive. According to Chapter 2 (113 & 113A) of the <a href="https://www.legislation.gov.uk/ukpga/2006/46/part/8/chapter/2">Companies Act 2006</a>, we must legally collect a name and service address for each member, which must be kept for ten years. After termination of membership, any other data will be stored for <span class="incomplete">up to three years</span> in case of return, unless you request its deletion by emailing <a href="mailto:trustees@sheffieldhackspace.org.uk">trustees@sheffieldhackspace.org.uk</a>.
        </p>

        <input type="checkbox" id="privacy" required="true">
        <label for="privacy" class="checkable">
          <span class="required">
            I consent for Sheffield Hackspace to keep my details and use them in the ways detailed above
          </span>
        </label>


        <hr class="bottom" />
        <button class="submit" type="submit">Submit</button>
      </form>
    <?php
    }
    ?>
  </main>
  <footer>
    <p>
      Sheffield Hackspace Membership Form
    </p>
    <p>
      For questions, email <a href="mailto:trustees@sheffieldhackspace.org.uk">trustees@sheffieldhackspace.org.uk</a>
    </p>
    <p>Something broken or misleading? <a href="https://github.com/sheffieldhackspace/membership-form">suggest a change!</a></p>
    <p>
      <a href="https://github.com/sheffieldhackspace/membership-form">source</a>
    </p>
  </footer>
</body>

</html>