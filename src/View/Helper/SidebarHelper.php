<?php
namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Sidebar helper
 *
 * @property \Cake\View\Helper\FormHelper $Form
 * @property \Bootstrap\View\Helper\BootstrapHtmlHelper $Html
 */
class SidebarHelper extends Helper
{
    public $helpers = [
        'Form',
        'Html' => [
            'className' => 'Bootstrap.BootstrapHtml',
            'useFontAwesome' => true
        ]
    ];

    /**
     * Creates a link for use in actions panel in sidebar.
     *
     * @param string $title Title for the action.
     * @param string|array $route CakePHP-style URL.
     * @param string $icon Font Awesome icon name.
     * @param array $options Options for BootstrapHtmlHelper::link.
     * @return string HTML for the `<a />` element used on actions panel.
     */
    public function action($title, $route, $icon, $options = [])
    {
        $defaultOptions = ['class' => 'list-group-item', 'escape' => false];
        $text = $this->Html->icon($icon . ' fa-fw') . '&nbsp;' . $title;

        return $this->Html->link($text, $route, $options + $defaultOptions);
    }

    /**
     * Creates a post link for use in actions panel in sidebar.
     *
     * @param string $title Title for the action.
     * @param string|array $route CakePHP-style URL.
     * @param string $icon Font Awesome icon name.
     * @param array $options Options for FormHelper::postLink.
     * @return string HTML for the `<a />` element used on actions panel.
     */
    public function postAction($title, $route, $icon, $options = [])
    {
        $defaultOptions = ['class' => 'list-group-item', 'escape' => false];
        $text = $this->Html->icon($icon . ' fa-fw') . '&nbsp;' . $title;

        return $this->Form->postLink($text, $route, $options + $defaultOptions);
    }
}
