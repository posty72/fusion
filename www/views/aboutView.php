<?php

// Displays the page which contains the FAQs and team member profiles
class AboutView extends View {
    
    public $profiles;
    
    // Returns HTML for the browser
    protected function displayContent() {
        
        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<div id="pageContent">'."\n";
        $html .=  $this -> displayFaqs();
        $html .=  $this -> displayProfiles();
        $html .= '</div>'."\n";
        
        $html .= '</section>'."\n";
        return $html;
    }
    
    private function displayProfiles() {
        
        
        $html .= '<h4><em>Employee Profiles</em></h4>'."\n";

        if($_SESSION['userType'] == 'admin') {
            $html .= '<p class="button inline"><a href="index.php?page=addMember">Add a new member</a></p>'."\n";
        }
        
        $this -> profiles = $this -> model -> getProfiles();
        //echo $this -> profiles;
        //print_r($this -> profiles);
        
        foreach($this -> profiles as $p) {
        
            $html .= '<article>'."\n";
            $html .= '<h1>'.stripslashes($p['profName']).'</h1>'."\n";
            $html .= '<p>'.stripslashes($p['profContent']).'</p>'."\n";
            $html .= '<p><i>'.$p['profEmail'].'</i></p>'."\n";
            $html .= '<img src="images/profiles/'.$p['profImg'].'" alt="'.stripslashes($p['profTitle']).'" />'."\n";

            if($_SESSION['userType'] == 'admin') {
                    $html .= '<form class="inline" method="post" action="index.php?page=deleteMember">
                    <input type="hidden" name="profID" value="'.$p['profID'].'" />
                    <input type="submit" class="button" name="submit" value="Delete" />
                    </form>'."\n";
                    $html .= '<form class="inline" method="post" action="index.php?page=editMember">
                    <input type="hidden" name="profID" value="'.$p['profID'].'" />
                    <input type="submit" class="button" name="submit" value="Edit" />
                    </form>'."\n";
            }

            $html .= '</article>'."\n";
            
        }
        
        return $html;
    }
    
    // Returns the HTML for every frequently asked question
    private function displayFaqs() {
        
        
        $html .= '<h4><em>Frequently Asked Questions</em></h4>'."\n";
        if($_SESSION['userType'] == 'admin') {
            $html .= '<p class="button"><a href="index.php?page=addFaq">Add a FAQ</a></p>'."\n";
        }

        $faqs = $this -> model -> getFaqs();
        
        //print_r($faqs);
        
        foreach($faqs as $f) {
            $html .= '<article>'."\n";
            $html .= '<h1>'.stripslashes($f['faqTitle']).'</h1>'."\n";
            $html .= '<p>'.stripslashes($f['faqContent']).'</p>'."\n";

            if($_SESSION['userType'] == 'admin') {
                $html .= '<form class="inline" method="post" action="index.php?page=deleteFaq">
                <input type="hidden" name="faqID" value="'.$f['faqID'].'" />
                <input class="button" type="submit" name="submit" value="Delete" />
                </form>'."\n";
                $html .= '<form class="inline" method="post" action="index.php?page=editFaq">
                <input type="hidden" name="faqID" value="'.$f['faqID'].'" />
                <input class="button" type="submit" name="submit" value="Edit" />
                </form>'."\n";
            }

            $html .= '</article>'."\n";
        }
        return $html;
    }
    
}



?>