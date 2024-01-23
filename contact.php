<?php

    define("TITLES", array("mr."=>"Dhr.", "mrs."=>"Mvr.", "dr." => "Dr.", "prof." => "Prof."));
    define("COMM_PREFS", array("email" => "E-Mail", "phone" => "Telefoon", "mail" => "Post"));
    
    function contactHeader() {
        $header = 'Contact Pagina';
        return $header;
    }
    
    function showContactContent($data) {
        if ($data['valid']) {
            displayThanks($data);
        } else {
            displayContactForm($data);
        }
    }
    
    function displayContactForm($data) {
        showFormStart('contact');
        
        //selection for title
        showFormField('title', 'Kies uw aanhef:', 'select', $data, TITLES);
        
        //input for name
        showFormField('name', 'Naam:', 'text', $data);
        //input for email
        showFormField('email', 'Email:', 'email', $data);
        //input for phone
        showFormField('phone', 'Tel. nr.:', 'text', $data);

        echo '    <h4>Adres</h4>' . PHP_EOL;
        
        //input for address fields (street, number, postal code, city)
        showFormField('street', 'Straat:', 'text', $data);
        showFormField('streetNo', 'Nr. + Toevoeging:', 'text', $data);
        showFormField('postcode', 'Postcode:', 'text', $data);
        showFormField('city', 'Woonplaats:', 'text', $data);
        
        echo '        <br>' . PHP_EOL;
        
        //input for communication preference
        showFormField('preference', 'Communicatie Voorkeur', 'radio', $data, COMM_PREFS);
        
        echo '        <br>' . PHP_EOL;
        
        //textbox for message
        showFormField('message', 'Bericht', 'textarea', $data, ['rows' => 10, 'cols' => 30], 'Vul in waar u contact over wil opnemen.');
        
        showFormEnd('Verzenden');
    }
    
    function displayThanks($data) {
        echo '<p>Bedankt voor uw reactie<p>
<div>Naam: ' . $data['title'] . ' ' . $data['name'] . ' </div>
<div>Email: ' . $data['email'] . ' </div>
<div>Tel. nr.: ' . $data['phone'] . ' </div>
<div>Adres: ' . $data['street'] . ', ' . $data['streetNo'] . ', ' . $data['postcode'] . ', ' . $data['city'] . ' </div><br>';

    }
    
    function validateContact() {
        $title = $name = $message = $email = $phone = $preference = $street = $streetNo = $postcode = $city = '';
        $titleErr = $nameErr = $messageErr = $emailErr = $phoneErr = $preferenceErr = $streetErr = $streetNoErr = $postcodeErr = $cityErr = '';
        $valid = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //check title
            if (empty(getPostVar("title", ''))) {
                $titleErr = "Kies een aanhef";
            } else {
                $title = testInput(getPostVar("title"));
            }
            
            //check name (only allow letters, spaces, dashes and apostrophes)
            $name = testInput(getPostVar("name"));
            $nameErr = validateName($name);
            
            //check message
            if (empty(getPostVar("message"))) {
                $messageErr = "Vul een bericht in";
            } else {
                $message = testInput(getPostVar("message"));
            }
            
            $addressRequired = $emailRequired = $phoneRequired = false;
            //check preference
            if (empty(getPostVar("preference", ''))) {
                $preferenceErr = "Vul in hoe we met u contact op kunnen nemen";
            } else {
                $preference = testInput(getPostVar("preference"));
                if ($preference == "mail" || !empty(getPostVar("street")) || !empty(getPostVar("streetNo")) || !empty(getPostVar("postcode")) || !empty(getPostVar("city")) )  {
                    $addressRequired = true;
                }
                if ($preference == "email" || !empty(getPostVar("email"))) {
                    $emailRequired = true;
                }
                if ($preference == "phone" || !empty(getPostVar("phone"))) {
                    $phoneRequired = true;
                }
            }

            $street = testInput(getPostVar("street"));
            $streetNo = testInput(getPostVar("streetNo"));
            $postcode = testInput(getPostVar("postcode"));
            $city = testInput(getPostVar("city"));
            
            if (empty($street) && $addressRequired) {
                $streetErr = "vul een straat in";
            }
            if (empty($streetNo) && $addressRequired) {
                $streetNoErr = "vul een straat nummer in";
            }
            if (empty($postcode)) {
                if ($addressRequired) {
                    $postcodeErr = "vul een postcode in";
                }
            } elseif (strlen($postcode) != 6 || !is_numeric(substr($postcode, 0, 4)) || !preg_match("/^[a-zA-Z]{2}$/",substr($postcode, 4, 2))) {
                $postcodeErr = "vul een geldige Nederlandse postcode in";
            }
            if (empty($city) && $addressRequired) {
                    $cityErr = "vul een woonplaats in";
            }
            
            //check email
            $email = testInput(getPostVar("email"));
            $emailErr = validateEmail($email, $emailRequired);
            
            $phone = testInput(getPostVar("phone"));
            if ($phoneRequired) {
                if (empty($phone)) {
                    $phoneErr = "Vul uw telefoon nummer in";
                } elseif (!is_numeric($phone)) {
                    $phoneErr = "Ongeldig nummer";
                }
            }
            
            //update valid boolean after all error checking
            $valid = empty($titleErr) && empty($nameErr) && empty($messageErr) && empty($emailErr) && empty($phoneErr) && empty($preferenceErr) && empty($streetErr) && empty($streetNoErr) && empty($postcodeErr) && empty($cityErr);
        }
        $data = array('valid'=>$valid, 'title'=>$title, 'name'=>$name, 'message'=>$message, 'email'=>$email, 'phone'=>$phone, 'preference'=>$preference,
                        'street'=>$street, 'streetNo'=>$streetNo, 'postcode'=>$postcode, 'city'=>$city,
                        'titleErr'=>$titleErr, 'nameErr'=>$nameErr, 'messageErr'=>$messageErr, 'emailErr'=>$emailErr, 'phoneErr'=>$phoneErr, 'preferenceErr'=>$preferenceErr,
                        'streetErr'=>$streetErr, 'streetNoErr'=>$streetNoErr, 'postcodeErr'=>$postcodeErr, 'cityErr'=>$cityErr);
        return $data;
    }
?>
