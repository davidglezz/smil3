<?php
//error_reporting(0);
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
require '../php/class.Validate.php';

echo "Test Validate_Email<br>";

$emails = array(
        // with out the dns lookup
        'example@fluffffffrefrffrfrfrfrfrfr.is', // OK

        array('davidc@php.net', array('fullTLDValidation' => true, 'VALIDATE_GTLD_EMAILS' => true)),
        array('example (though bad)@example.com', array('use_rfc822' => true)), // OK
        'bugme@not./com', // OK

        // Some none english chars, those should fail until we fix the IDN stuff
        'hæjjæ@homms.com', // NOK
        'þæöð@example.com', // NOK
        'postmaster@tüv.de', // NOK

        // Test for various ways with _
        'mark_@example.com', // OK
        '_mark@example.com', // OK
        'mark_foo@example.com', // OK

        // Test for various ways with -
        'mark-@example.com', // OK
        '-mark@example.com', // OK
        'mark-foo@example.com', // OK

        // Test for various ways with .
        'mark.@example.com', // NOK
        '.mark@example.com', // NOK
        'mark.foo@example.com', // OK

        // Test for various ways with ,
        'mark,@example.com', // NOK
        ',mark@example.com', // NOK
        'mark,foo@example.com', // NOK

        // Test for various ways with :
        'mark:@example.com', // NOK
        ':mark@example.com', // NOK
        'mark:foo@example.com', // NOK

        // Test for various ways with ;
        'mark;@example.com', // NOK
        ';mark@example.com', // NOK
        'mark;foo@example.com', // NOK

        // Test for various ways with |
        'mark|@example.com', // OK
        '|mark@example.com', // OK
        'mark|foo@example.com', // OK

        // Test for various ways with double @
        'mark@home@example.com', // NOK
        'mark@example.home@com', // NOK
        'mark@example.com@home', // NOK

        // Killers ' tests
        'ha"ho@example.com', // NOK
        '<ha la la>blah</ha>@example.com', // NOK
        '<hablahha>@example.com', // NOK
        '"<ha la la>blah</ha>"@example.com', // OK
        '" "@example.com', // NOK
        '@example.com', // NOK

        // Minus ' tests (#5804)
        'minus@example-minus.com', // OK
        'minus@example.co-m', // OK
        'mi-nus@example-minus.co-m', // OK
        'minus@example-.com', // NOK
        'minus@-example.com', // NOK
        'minus@-.com', // NOK
        'minus@example.-com', // NOK
        'minus@-example.com-', // NOK

        // IP domain
        'ip@127.0.0.1', // OK
        '"the ip"@[127.0.0.1]', // OK
        'ip@127.0.333.1', // NOK
        'ip@[277.0.0.1]', // NOK
        'ip@[127.0.0.1', // NOK
        'ip@127.0.0.1]' // NOK
    );
$res = array(1,1,1,1,0,0,0,1,1,1,1,1,1,0,0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,1,0,0,1,1,1,0,0,0,0,0,1,1,0,0,0,0);
list($version) = explode(".", phpversion(), 2);
$i = 0;
foreach ($emails as $email)
{
    if (is_array($email)) {
        echo "{$email[0]}:";
        if (!is_array($email[1])) {
            echo " with". ($email[1] ? '' : 'out') . ' domain check :';
        }
		$t = Validate::email($email[0], $email[1]);
        echo ' ' . $noYes[$t]. ($t == $res[$i] ? '' : '#') ."<br>";
    } else {
        echo "{$email}: ";
        if ((int)$version > 4) {
            try {
				$t = Validate::email($email);
                echo $noYes[$t]. ($t == $res[$i] ? '' : '#')."<br>";
            } catch (Exception $e) {
                echo $e->getMessage()."<br>";
            }
        } else {
			$t = Validate::email($email);
            echo $noYes[$t]. ($t == $res[$i] ? '' : '#')."<br>";
        }
    }
	$i++;
}


?>

