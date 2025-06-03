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
    }

    form label.above {
      display: inline-block;
      margin-top: 1rem;
    }

    form .required::after {
      content: " *";
      color: red;
    }

    form input {
      max-width: 100%;
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
  <main>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $msg = "";
      foreach ($_POST as $key => $value) {
        $msg = $msg . htmlspecialchars($key) . ": " . htmlspecialchars($value) . ", ";
      }

      $email   = 'trustees@sheffieldhackspace.org.uk';
      $to      =  $email;
      $subject = 'New Membership Form Entry';
      $message = $msg;
      $headers = 'From: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

      $success = mail($to, $subject, $message, $headers);
      if ($success) {
        echo "sent an email to " . $to . "!";
      } else {
        echo "looks like the emailing failed";
      }
    } else {

    ?>
      <h2>Instructions</h2>
      <p>To become a member you must:</p>
      <ol>
        <li>fill in and submit this form</li>
        <li>
          pay the <span class="incomplete">current membership rates</span>
        </li>
      </ol>
      <p>To become a keyholder, <span class="incomplete">do this</span>.</p>
      <hr />
      <h2>Membership Sign-Up</h2>
      <form method="POST" action="">
        <label class="above required" for="name">Full Name</label>
        <input
          type="text"
          name="name"
          id="name"
          autocomplete="name"
          required="true" />

        <label class="above required" for="address">Address (home or
          <a href="https://en.wikipedia.org/wiki/Service_address">service</a>)</label>
        <input type="text" name="address" id="address" required="true" />

        <label class="above required" for="email">Email</label>
        <input
          type="text"
          name="email"
          id="email"
          autocomplete="email"
          required="true" />

        <p class="required">Have you been a member of a hackspace before?</p>
        <input
          type="radio"
          name="memberbefore"
          id="memberbefore_yes"
          value="yes"
          required="true" />
        <label for="memberbefore_yes" class="checkable">Yes</label>
        <input
          type="radio"
          name="memberbefore"
          id="memberbefore_no"
          value="no"
          required="true" />
        <label for="memberbefore_no" class="checkable">No</label>

        <label class="above optional" for="discord">Discord Username (optional)</label>
        <input
          class="optional"
          type="text"
          name="discord"
          id="discord"
          autocomplete="discord" />

        <label class="above optional" for="interests">
          Do you have any interests (optional)
        </label>
        <textarea class="optional" name="interests" id="interests"></textarea>

        <hr class="bottom" />
        <button class="submit" type="submit">Submit</button>
      </form>
  </main>
  <footer>source code</footer>
</body>

</html>
<?php
    }
