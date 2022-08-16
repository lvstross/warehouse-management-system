<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagesTest extends TestCase
{
    /**
    * Test the home page
    *
    * @return void
    */
    public function testRoot()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test the dashboard Page
     *
     * @return void
     */
    public function testDashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
    * Session and http test for the settings page
    *
    * @return void
    */
    public function testSettings()
    {
        $response = $this->get('/settings');
        $response->assertRedirect('/login');
    }
}
