<?php
namespace App\Test\TestCase\View\Helper;

use App\View\Helper\SidebarHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\SidebarHelper Test Case
 *
 * @property SidebarHelper $Sidebar
 */
class SidebarHelperTest extends TestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->Sidebar = new SidebarHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Sidebar);

        parent::tearDown();
    }

    /**
     * Test action method
     *
     * @return void
     */
    public function testAction()
    {
        // Should work with URL
        $google = $this->Sidebar->action('Google', 'http://www.google.com', 'google');
        $this->assertHtml([
            ['a' => ['class' => 'list-group-item', 'href' => 'http://www.google.com']],
            ['i' => ['class' => 'fa fa-google fa-fw']],
            '/i',
            '&nbsp;Google',
            '/a'
        ], $google);

        // Should work with CakePHP array route
        $home = $this->Sidebar->action('Home', ['controller' => 'pages', 'action' => 'display', 'home'], 'home');
        $this->assertHtml([
            ['a' => ['class' => 'list-group-item', 'href' => '/']],
            ['i' => ['class' => 'fa fa-home fa-fw']],
            '/i',
            '&nbsp;Home',
            '/a'
        ], $home);
    }

    /**
     * Test postAction method
     *
     * @return void
     */
    public function testPostAction()
    {
        $home = $this->Sidebar->postAction('Delete', '/delete', 'times');
        $this->assertHtml([
            ['form' => ['method' => 'post', 'action' => '/delete', 'name', 'style']],
            ['input' => ['name', 'value', 'type']],
            '/form',
            ['a' => ['class' => 'list-group-item', 'href', 'onclick']],
            ['i' => ['class' => 'fa fa-times fa-fw']],
            '/i',
            '&nbsp;Delete',
            '/a'
        ], $home);
    }
}
