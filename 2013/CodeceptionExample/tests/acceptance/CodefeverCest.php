<?php


class CodefeverCest
{

    public function _before()
    {
    }

    public function _after()
    {
    }

    // tests
    public function testSendContactFormWithWrongSecurityAnswerShowsError(\WebGuy $I)
    {
    	$I->am('Visitor');
    	$I->wantTo('navigate to contact area and send a request with wrong security number');
    	$I->lookForwardTo('get an error');

    	// Open code-fever.de
    	$I->amOnPage('');

    	// Assert that i'm on code-fever.de/
    	$I->seeCurrentUrlEquals('/');

    	// Assert that there is a link with 'kontakt'
    	$I->seeLink('kontakt');

    	// Click that link
    	$I->click('kontakt');

    	// Assert that current url is now code-fever.de/kontakt.html
    	$I->seeCurrentUrlEquals('/kontakt.html');

    	# second part - validate that an error is shown

    	// Fill fields by name
    	$I->fillField('name', 'codeception WebGuy');
    	$I->fillField('email', 'codeception@code-fever.de');
    	$I->fillField('subject', 'Codception is cool');
    	$I->fillField('message', 'This is a dummy contact request using your tutorial');

    	// Fill field by label
    	$I->fillField('Sicherheitsfrage', '4000');

    	// Click button 'Absenden'
    	$I->click('Absenden');

    	// Assert that Url is still /kontakt.html
    	$I->seeCurrentUrlEquals('/kontakt.html');

    	// Assert that the error is displayed
    	$I->see('Bitte beantworten Sie die Sicherheitsfrage!');
    }

}