<?php

it('redirects the root path to the default locale', function () {
    $this->get('/')->assertRedirect(route('home'));
});
