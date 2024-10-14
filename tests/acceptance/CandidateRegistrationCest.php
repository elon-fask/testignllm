<?php
use Codeception\Util\Locator;

class CandidateRegistrationCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnSubdomain('acs');
        $I->amOnPage('/');
        $I->see('American Crane School');
        $I->fillField('keyword', 'certify');
        $I->click('#submit-btn');
        $I->seeInCurrentUrl('/register');
        $I->see('Select a Class Location');
        $siteOptions = $I->grabMultiple('#choose-location > option');
        $I->selectOption('#choose-location', $siteOptions[1]);
        $I->waitForText('Select a Class Date', 30);
        $sessionOptions = $I->grabMultiple('input[name="sessionRadio"]', 'id');
        $lastSession = $sessionOptions[sizeOf($sessionOptions) - 1];
        $I->click('#' . $lastSession);
        $I->click('.btn-submit-date');
        $I->see('Provide Required Information for your Profile');
        $I->fillField('#candidates-first_name', 'Test');
        $I->fillField('#candidates-last_name', 'User');
        $I->fillField('#candidates-email', 'admin@tabletbasedtesting.com');
        $I->fillField('#candidates-confirmemail', 'admin@tabletbasedtesting.com');
        $I->fillField('#candidates-phone', '(111) 111-1111');
        $I->fillField('#candidates-address', '#111 Test Address');
        $I->fillField('#candidates-city', 'Test City 2');
        $I->selectOption('#candidates-state', 'CA');
        $I->fillField('#candidates-zip', '12344');
        $I->fillField('#candidates-company_name', 'Test Company');
        $I->fillField('#candidates-company_phone', '(222) 111-1111');
        $I->fillField('#candidates-company_address', '#123 Test Address Company');
        $I->fillField('#candidates-company_city', 'Test City');
        $I->selectOption('#candidates-company_state', 'CA');
        $I->fillField('#candidates-company_zip', '12345');
        $I->fillField('#candidates-contact_person', 'John Doe');
        $I->fillField('#candidates-contactemail', 'admin@tabletbasedtesting.com');
        $I->click('.btn-register');
        $I->waitForText('Last Step! Complete your Payment Method.', 30);
        $I->fillField('#promoCode', 'po');
        $I->click('.apply-promo');
        $I->waitForText('Proceed & Download Application');
        $I->click('#btn-po-form');
        $I->waitForText('Thank you for registering for the Crane Operator Certification Program.');
    }
}
