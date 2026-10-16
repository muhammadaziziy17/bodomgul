<?php

it('formats whole som amounts with non-breaking spaces', function () {
    expect(format_price(185000))->toBe("185\u{00A0}000\u{00A0}so‘m")
        ->and(format_price(0))->toBe("0\u{00A0}so‘m")
        ->and(format_price(16000))->toBe("16\u{00A0}000\u{00A0}so‘m");
});
