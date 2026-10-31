<?php

it('renders the combined welcome and login page at the home and login routes', function () {
    foreach (['/', '/login'] as $path) {
        $this->get($path)
            ->assertOk()
            ->assertViewIs('auth.portal')
            ->assertSee('Sistema de Gestión')
            ->assertSee('Contexto y trayectoria')
            ->assertSee('id="formLogin"', false)
            ->assertSee('name="username"', false)
            ->assertSee('name="password"', false)
            ->assertSee('id="formOlvido"', false)
            ->assertSee('css/portal.css');
    }
});
