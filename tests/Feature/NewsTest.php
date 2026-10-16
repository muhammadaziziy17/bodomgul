<?php

use App\Support\DemoContent;

it('renders a news article with its body paragraphs', function () {
    $item = DemoContent::newsItem('kuzgi-tamlar-kolleksiyasi');

    $response = $this->get(route('news.show', ['news' => $item['slug']]));

    $response->assertOk()
        ->assertSee($item['title'])
        ->assertSee($item['date_label']);

    foreach ($item['body'] as $paragraph) {
        $response->assertSee($paragraph);
    }
});

it('returns 404 for an unknown news slug', function () {
    $this->get(route('news.show', ['news' => 'mavjud-emas']))->assertNotFound();
});

it('orders news newest first', function () {
    $dates = array_column(DemoContent::news(), 'date');
    $sorted = $dates;
    rsort($sorted);

    expect($dates)->toEqual($sorted);
});
