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
      min-width: 100vw;
      min-height: 100vh;
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

    .privacy {
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

    .thanks {
      font-family: "Big Noodle Titling";
      font-size: 3rem;
      text-align: center;
    }

    .error {
      color: red;
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

      header h1 {
        display: flex;
        flex-direction: column;
        align-items: center;
      }

    }
  </style>
</head>

<body>
  <img id="bg-image" src="https://www.sheffieldhackspace.org.uk/assets/images/soldering-header.jpg" />
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
    <p>
      Come to any of the open evenings for free up to three times, to see if the space is what you want. If you think so, then become a member to keep on visiting, tinkering, and experimenting with us!
    </p>
    <p>To become a member you must:</p>
    <ol>
      <li>fill in and submit this form</li>
      <li>
        pay the monthly £10 membership fee — by setting up a monthly £10 standing order to the <a href="https://wiki.sheffieldhackspace.org.uk/members/info/bank">Sheffield Hackspace bank account</a> – on the 15th of the month – with a clear reference (e.g., <i>"Subs&nbsp;Holly&nbsp;R"</i>)</span>
      </li>
    </ol>
    <p class="privacy">
      Everyone should be able to be a part of the community, so if you are on low income/a student/a retiree/have trouble affording this rate, please reach out to <a href="mailto:trustees@sheffieldhackspace.org.uk">trustees@sheffieldhackspace.org.uk</a> to discuss a discounted rate.
    </p>
    <p class="privacy">For the process for becoming a keyholder (24/7 access), see <a href="https://wiki.sheffieldhackspace.org.uk/members/faq">the wiki</a>.</p>
    <hr id="result-top" />
    <?php

    // set up mailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    $env = parse_ini_file('..' . DIRECTORY_SEPARATOR . '.env');

    // form submit logic
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      // check important data exists
      function bad($what)
      {
        http_response_code(400);
    ?>
        <h2 class="error">Error!</h2>
        <p class="error">
          Your submission didn't have: <?= $what ?>
        </p>
        <p>
          If this continue to cause problems, please email <a href="mailto:trustees@sheffieldhackspace.org.uk">trustees@sheffieldhackspace.org.uk</a>
        </p>
        <?php
      }
      if (!array_key_exists("name", $_POST) || $_POST['name'] == "")
        bad("name");
      else if (!array_key_exists("address", $_POST) || $_POST['address'] == "")
        bad("address");
      else if (!array_key_exists("email", $_POST) || $_POST['email'] == "")
        bad("email");
      else if (!array_key_exists("privacy", $_POST) || $_POST['privacy'] == "")
        bad("privacy");
      else {
        $msg = "";
        foreach ($_POST as $key => $value) {
          $msg = $msg . htmlspecialchars($key) . ": " . htmlspecialchars($value) . ", ";
        }

        file_put_contents(
          ".." . DIRECTORY_SEPARATOR . "submissions" . DIRECTORY_SEPARATOR . date("Y-m-d\TH:i:s") . ($env["DISABLE_EMAIL"] ? "_TEST_" : "") . "-" . uniqid() . ".txt",
          $msg,
        );

        try {
          //Server settings
          $mail->isSMTP();
          $mail->Host       = $env["SMTP_HOST"];
          $mail->SMTPAuth   = true;
          $mail->Username   = $env["SMTP_USERNAME"];
          $mail->Password   = $env["SMTP_PASSWORD"];
          $mail->SMTPSecure = match ($env["SMTP_TLS"]) {
            "STARTTLS" => PHPMailer::ENCRYPTION_STARTTLS,
            "SMTPS" => PHPMailer::ENCRYPTION_SMTPS,
            default => PHPMailer::ENCRYPTION_SMTPS,
          };
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
          <p class="thanks">Thanks!</p>
          <h2>Your membership application was sent!</h2>
          <p>
            A director should be in contact via email in the next 7 days to confirm. If you do not get an email, please check your junk or email <a href="mailto:trustees@sheffieldhackspace.org.uk">trustees@sheffieldhackspace.org.uk</a>.
          </p>
          <p>
            If you haven't already, remember to set up a standing order for your subscription. See above for details — or read more on the <a href="https://wiki.sheffieldhackspace.org.uk/members/faq">Frequently Asked Questions</a> section of the wiki.
          </p>
        <?php
        } catch (Exception $e) {
        ?>
          <h2 class="error">Error!</h2>
          <p class="error">
            Message could not be sent. Mailer Error:
          <pre><?= $mail->ErrorInfo ?></pre>
          </p>
          <p>
            If this continue to cause problems, please email <a href="mailto:trustees@sheffieldhackspace.org.uk">trustees@sheffieldhackspace.org.uk</a>
          </p>
      <?php
        }
      } ?>
      <hr>
      <a href="/">back to form</a>
    <?php
    } else {

    ?>
      <h2>Membership Sign-Up</h2>
      <?php if ($env["DISABLE_EMAIL"]) {
      ?>
        <h2 class="error">test mode enabled!</h2>
        <p class="error">no emails will be sent. feel free to play around with submitting</p>
      <?php
      } ?>
      <form method="POST" action="/#result-top" autocomplete="off">
        <label class="above required" for="name">Full Name</label>
        <input
          type="text"
          name="name"
          id="name"
          required="true" />

        <label class="above required" for="address">Address ("<a href="https://en.wikipedia.org/wiki/Service_address">Service address</a>")</label>
        <input type="text" name="address" id="address" required="true" />

        <label class="above required" for="email">Email</label>
        <input
          type="text"
          name="email"
          id="email"
          pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
          title="Invalid email address"
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
          id="discord" />

        <label class="above optional" for="interests">
          Do you have any specific interests? (optional)
        </label>
        <textarea class="optional" name="interests" id="interests"></textarea>

        <p class="privacy">
          Sheffield Hackspace does not currently have a privacy policy (<a href="https://wiki.sheffieldhackspace.org.uk/members/info/privacy">why?</a>).
        </p>
        <p class="privacy">
          Sheffield Hackspace will use the data above to: manage membership, subscriptions, and space access; keep track of tool inductions; and to send emails about general meetings and other organisational changes.
        </p>
        <p class="privacy">
          Sheffield Hackspace stores this information on a password-protected <a href="https://nextcloud.com/">NextCloud</a> Drive. According to Chapter 2 (113 & 113A) of the <a href="https://www.legislation.gov.uk/ukpga/2006/46/part/8/chapter/2">Companies Act 2006</a>, we must legally collect a name and service address for each member, which must be kept for ten years. After termination of membership, any other data will be stored for up to three years in case of return, unless you request its deletion by emailing <a href="mailto:trustees@sheffieldhackspace.org.uk">trustees@sheffieldhackspace.org.uk</a>.
        </p>

        <input type="checkbox" id="privacy" name="privacy" required="true">
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
  <br>
</body>

</html>
