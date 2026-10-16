<?php

it('redirects back with validation errors when the contact form is empty', function () {
    $this->from(route('contacts'))
        ->post(route('contacts.store'), [])
        ->assertRedirect(route('contacts'))
        ->assertSessionHasErrors(['name', 'contact', 'message']);
});

it('shows the uzbek message when the name is missing', function () {
    $this->post(route('contacts.store'), [
        'contact' => 'aziz@example.com',
        'message' => 'Bu yetarlicha uzun sinov xabari.',
    ])->assertSessionHasErrors(['name' => 'Ismingizni kiriting.']);
});

it('shows the uzbek message when the message is too short', function () {
    $this->post(route('contacts.store'), [
        'name' => 'Aziz',
        'contact' => 'aziz@example.com',
        'message' => 'Qisqa',
    ])->assertSessionHasErrors(['message' => 'Xabar kamida 10 ta belgidan iborat bo‘lishi kerak.']);
});

it('redirects with a demo notice and sends nothing when the form is valid', function () {
    $response = $this->from(route('contacts'))->post(route('contacts.store'), [
        'name' => 'Aziz',
        'contact' => 'aziz@example.com',
        'message' => 'Bu portfolio demo formasi uchun sinov xabari.',
    ]);

    $response->assertRedirect(route('contacts'))
        ->assertSessionHas('demo_notice');

    $this->followRedirects($response)
        ->assertOk()
        ->assertSee('yuborilmadi')
        ->assertSee('Aziz');
});
