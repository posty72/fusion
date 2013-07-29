<?php

// Displays the page which is used to contact the specified email
class ContactView extends View {

    public $email;
    
    // Returns HTML for the browser
    protected function displayContent() {

        if($_POST['submit'] == 'Send') {
            $this -> email = $this -> model -> processSendMessage();

        }
        
        //print_r($_POST);
        
        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '<div id="contactInfo">'."\n";
        $html .= $this -> displayContactInfo();
        $html .= '</div>'."\n";
        $html .= '</aside>'."\n";
        
        $html .= '<div id="pageContent">'."\n";
        $html .= '<p>'.$sent.'</p>';
        $html .= $this -> displayContactForm();
        $html .= '</div>'."\n";
        
        $html .= '</section>'."\n";
        
        return $html;
    }
    

    // Displays the form which allows a user to email the specifies address
    private function displayContactForm() {

        if(is_array($this -> email)) {
            extract($this -> email);
        }
        
        //echo $this -> email;
        print_r($this -> email);

        $html = '<article id="contact">'."\n";
        $html .= '<h1>Request to hear from our team</h1>'."\n";
        
        if(is_array($_POST) && $this -> email['ok'] == true) {
            $html .= '<p>'.$this -> email.'</p>';
        } else {
            $html .= '<p>Send us a message and we\'ll get back to you soon. From there we can assess your needs and set you up with an account.</p>'."\n";
        }
        // Contact Form for sending an initial message
        
        $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";

        $html .= '<input class="short" type="text" name="firstName" id="firstName" placeholder="First Name..." value="'.htmlentities(stripslashes($_POST['firstName'])).'"/>'."\n";
        $html .= '<input class="short" type="text" name="lastName" id="lastName" placeholder="Last Name..."  value="'.htmlentities(stripslashes($_POST['lastName'])).'"/>'."\n";
        $html .= '<p class="error" id="nameMsg">'.$nameMsg.'</p>'."\n";

        $html .= '<input class="long" type="text" name="companyName" id="companyName" placeholder="Company Name"  value="'.htmlentities(stripslashes($_POST['companyName'])).'"/>'."\n";
        $html .= '<p class="error" id="cnameMsg">'.$cnameMsg.'</p>'."\n";

        $html .= '<input class="long" type="text" name="email" id="email" placeholder="Email"  value="'.htmlentities(stripslashes($_POST['email'])).'"/>'."\n";
        $html .= '<p class="error" id="emailMsg">'.$emailMsg.'</p>'."\n";

        $html .= '<input class="long" type="text" name="phoneNumber" id="phoneNumber" placeholder="Phone Number"  value="'.htmlentities(stripslashes($_POST['phoneNumber'])).'"/>'."\n";
        $html .= '<p class="error" id="phnoMsg">'.$phnoMsg.'</p>'."\n";

        $html .= '<textarea class="long" name="message" id="message" rows="7" cols="35" placeholder="Enter Your Message" >'.htmlentities(stripslashes($_POST['message'])).'</textarea>'."\n";
        $html .= '<p class="error" id="msgMsg">'.$messageMsg.'</p>'."\n";

        $html .= '<input class="submitButton" type="submit" name="submit" id="submit" value="Send" />'."\n";
        $html .= '</form>'."\n";
        $html .= '</article>'."\n";
        
        return $html;
        
    }

    // Displays the nessecary contact information if users want to call, email or write to the comapany
    private function displayContactinfo() {
        
        $html = '<h2>Connect <em>Now >></em></h2>'."\n";
        $html .= '<p>Micheal Bignell / <strong>Technology Manager</strong></p>'."\n";
        $html .= '<p><i>mike@fusionnet.com.au</i></p>'."\n";
        $html .= '<p>04 876 04382</p>'."\n";
        $html .= '<p>08 927 82473</p>'."\n";
        $html .= '<p>Level 28, AMP Tower<br />140 St. Georges Terrace, Perth, WA</p>'."\n";
        
        return $html;
    }
    
}



?>