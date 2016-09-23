<img src="<?php echo $logoUrl; ?>" alt="<?php echo $blogName; ?>"/>
 
<?php if ( $userFirstName != '' ) : ?>
    <h1><?php echo $userFirstName; ?>, Welcome to <?php echo $blogName; ?></h1>
<?php else : ?>
    <h1>Welcome to <?php echo $blogName; ?></h1>
<?php endif; ?>

<h2>You have been nominated for the Excellence in Mathematics and Science Teacher Award</h2>

<p>
    You have been nominated for the Barrett Family Foundation Excellence in Science and Mathematics Teacher Award. Please take a momment to set your password. Once you have set your password follow the below link "Nomination Questionaire" to complete the nominee questionaire. This will allow us to confirm your eligibility. Once your eligibility is confirmed we will instruct you to move to the application stage. 
</p>

<p>
    Please use the following credentials to login:<br />
    <?php echo 'Username: ' . $userLogin; ?><br />
    <?php echo 'Password: ' . $plaintext_pass; ?><br />
    <?php echo 'Login URL: ' . '<a href="' . $loginURL . '">' . $loginURL . '</a>'; ?>
</p>
 
<p>
    <a href="<?php echo $siteUrl . '/application/nominee-questionaire/'; ?>">Nominee Questionaire</a>
</p>
 
<p>
    Thank you,<br>
    <?php echo $blogName; ?>
</p>