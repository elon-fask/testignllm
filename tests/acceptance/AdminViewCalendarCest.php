<?php


class AdminViewCalendarCest
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
        $I->amOnSubdomain('cso');
        $I->amOnPage('/admin');
        $I->see('Log In');
        $I->submitForm('#login-form', [
            'username' => 'admin',
            'password' => 'password'
        ]);
        $I->see('Dashboard');
        $I->amOnPage('/admin/calendar');
        $I->waitForText(date('F Y'), 30);
        $I->click('.rbc-event-content');
        $I->waitForText('Session Information', 30);
    }
}
