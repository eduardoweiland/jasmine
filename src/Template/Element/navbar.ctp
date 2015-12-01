<?php
echo $this->Navbar->create(
        [
            'name' => $this->Html->image('jasmine_icon_24.png') . ' JASMINE',
            'url' => 'https://github.com/eduardoweiland/jasmine',
            'options' => [
                'title' => 'Just Another SNMP Manager for Imaginary Networks',
                'escape' => false
            ]
        ],
        ['static' => true, 'responsive' => true]);

    echo $this->Navbar->beginMenu();

        echo $this->Navbar->link(
                $this->Html->icon('server') . '&nbsp;' . __('Devices'),
                ['controller' => 'Devices', 'action' => 'index'],
                [],
                ['escape' => false]);

        echo $this->Navbar->link(
                $this->Html->icon('area-chart') . '&nbsp;' . __('Monitoring'),
                ['controller' => 'Devices', 'action' => 'monitoring'],
                [],
                ['escape' => false]);

    echo $this->Navbar->endMenu();

    echo $this->Navbar->text('Trabalho de Administração e Gerência de Redes - UNISC 2015 - Eduardo Weiland', ['class' => 'navbar-right']);

echo $this->Navbar->end();
