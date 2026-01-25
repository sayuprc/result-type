<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ResultType\Err;
use ResultType\Ok;
use ResultType\Result;

class ResultTest extends TestCase
{
    #[Test]
    public function combineWithAllOkReturnsOkWithArray(): void
    {
        $result = Result::combine(
            new Ok(1),
            new Ok('two'),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 'two'], $result->unwrap());
    }

    #[Test]
    public function combineWithFirstErrReturnsFirstErr(): void
    {
        $result = Result::combine(
            new Err('error1'),
            new Ok('two'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error1', $result->unwrapErr());
    }

    #[Test]
    public function combineWithSecondErrReturnsSecondErr(): void
    {
        $result = Result::combine(
            new Ok(1),
            new Err('error2'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error2', $result->unwrapErr());
    }

    #[Test]
    public function combineWithBothErrReturnsFirstErr(): void
    {
        $result = Result::combine(
            new Err('error1'),
            new Err('error2'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error1', $result->unwrapErr());
    }

    #[Test]
    public function combine3WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::combine3(
            new Ok(1),
            new Ok('two'),
            new Ok(3.0),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 'two', 3.0], $result->unwrap());
    }

    #[Test]
    public function combine3WithErrReturnsFirstErr(): void
    {
        $result = Result::combine3(
            new Ok(1),
            new Err('error2'),
            new Err('error3'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error2', $result->unwrapErr());
    }

    #[Test]
    public function combine4WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::combine4(
            new Ok(1),
            new Ok('two'),
            new Ok(3.0),
            new Ok(true),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 'two', 3.0, true], $result->unwrap());
    }

    #[Test]
    public function combine4WithErrReturnsFirstErr(): void
    {
        $result = Result::combine4(
            new Ok(1),
            new Ok('two'),
            new Err('error3'),
            new Ok(true),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error3', $result->unwrapErr());
    }

    #[Test]
    public function combine5WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::combine5(
            new Ok(1),
            new Ok('two'),
            new Ok(3.0),
            new Ok(true),
            new Ok([5]),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 'two', 3.0, true, [5]], $result->unwrap());
    }

    #[Test]
    public function combine5WithErrReturnsFirstErr(): void
    {
        $result = Result::combine5(
            new Ok(1),
            new Ok('two'),
            new Ok(3.0),
            new Ok(true),
            new Err('error5'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error5', $result->unwrapErr());
    }

    #[Test]
    public function combine6WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::combine6(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6], $result->unwrap());
    }

    #[Test]
    public function combine6WithErrReturnsFirstErr(): void
    {
        $result = Result::combine6(
            new Ok(1),
            new Err('error2'),
            new Ok(3),
            new Err('error4'),
            new Ok(5),
            new Ok(6),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error2', $result->unwrapErr());
    }

    #[Test]
    public function combine7WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::combine7(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6, 7], $result->unwrap());
    }

    #[Test]
    public function combine7WithErrReturnsFirstErr(): void
    {
        $result = Result::combine7(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Err('error4'),
            new Ok(5),
            new Ok(6),
            new Ok(7),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error4', $result->unwrapErr());
    }

    #[Test]
    public function combine8WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::combine8(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
            new Ok(8),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8], $result->unwrap());
    }

    #[Test]
    public function combine8WithErrReturnsFirstErr(): void
    {
        $result = Result::combine8(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Err('error5'),
            new Ok(6),
            new Ok(7),
            new Ok(8),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error5', $result->unwrapErr());
    }

    #[Test]
    public function combine9WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::combine9(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
            new Ok(8),
            new Ok(9),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9], $result->unwrap());
    }

    #[Test]
    public function combine9WithErrReturnsFirstErr(): void
    {
        $result = Result::combine9(
            new Ok(1),
            new Err('error2'),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
            new Ok(8),
            new Ok(9),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error2', $result->unwrapErr());
    }

    #[Test]
    public function combine10WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::combine10(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
            new Ok(8),
            new Ok(9),
            new Ok(10),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $result->unwrap());
    }

    #[Test]
    public function combine10WithErrReturnsFirstErr(): void
    {
        $result = Result::combine10(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
            new Ok(8),
            new Ok(9),
            new Err('error10'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame('error10', $result->unwrapErr());
    }

    #[Test]
    public function collectWithAllOkReturnsOkWithArray(): void
    {
        $result = Result::collect(
            new Ok(1),
            new Ok('two'),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 'two'], $result->unwrap());
    }

    #[Test]
    public function collectWithFirstErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect(
            new Err('error1'),
            new Ok('two'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame(['error1', null], $result->unwrapErr());
    }

    #[Test]
    public function collectWithSecondErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect(
            new Ok(1),
            new Err('error2'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame([null, 'error2'], $result->unwrapErr());
    }

    #[Test]
    public function collectWithBothErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect(
            new Err('error1'),
            new Err('error2'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame(['error1', 'error2'], $result->unwrapErr());
    }

    #[Test]
    public function collect3WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::collect3(
            new Ok(1),
            new Ok('two'),
            new Ok(3.0),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 'two', 3.0], $result->unwrap());
    }

    #[Test]
    public function collect3WithSomeErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect3(
            new Ok(1),
            new Err('error2'),
            new Err('error3'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame([null, 'error2', 'error3'], $result->unwrapErr());
    }

    #[Test]
    public function collect4WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::collect4(
            new Ok(1),
            new Ok('two'),
            new Ok(3.0),
            new Ok(true),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 'two', 3.0, true], $result->unwrap());
    }

    #[Test]
    public function collect4WithSomeErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect4(
            new Err('error1'),
            new Ok('two'),
            new Err('error3'),
            new Ok(true),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame(['error1', null, 'error3', null], $result->unwrapErr());
    }

    #[Test]
    public function collect5WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::collect5(
            new Ok(1),
            new Ok('two'),
            new Ok(3.0),
            new Ok(true),
            new Ok([5]),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 'two', 3.0, true, [5]], $result->unwrap());
    }

    #[Test]
    public function collect5WithSomeErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect5(
            new Ok(1),
            new Err('error2'),
            new Ok(3.0),
            new Err('error4'),
            new Err('error5'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame([null, 'error2', null, 'error4', 'error5'], $result->unwrapErr());
    }

    #[Test]
    public function collect6WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::collect6(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6], $result->unwrap());
    }

    #[Test]
    public function collect6WithSomeErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect6(
            new Err('error1'),
            new Ok(2),
            new Ok(3),
            new Err('error4'),
            new Ok(5),
            new Err('error6'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame(['error1', null, null, 'error4', null, 'error6'], $result->unwrapErr());
    }

    #[Test]
    public function collect7WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::collect7(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6, 7], $result->unwrap());
    }

    #[Test]
    public function collect7WithSomeErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect7(
            new Ok(1),
            new Err('error2'),
            new Ok(3),
            new Err('error4'),
            new Ok(5),
            new Err('error6'),
            new Ok(7),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame([null, 'error2', null, 'error4', null, 'error6', null], $result->unwrapErr());
    }

    #[Test]
    public function collect8WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::collect8(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
            new Ok(8),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8], $result->unwrap());
    }

    #[Test]
    public function collect8WithSomeErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect8(
            new Err('error1'),
            new Err('error2'),
            new Err('error3'),
            new Err('error4'),
            new Err('error5'),
            new Err('error6'),
            new Err('error7'),
            new Err('error8'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame(['error1', 'error2', 'error3', 'error4', 'error5', 'error6', 'error7', 'error8'], $result->unwrapErr());
    }

    #[Test]
    public function collect9WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::collect9(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
            new Ok(8),
            new Ok(9),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9], $result->unwrap());
    }

    #[Test]
    public function collect9WithSomeErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect9(
            new Ok(1),
            new Ok(2),
            new Err('error3'),
            new Ok(4),
            new Ok(5),
            new Err('error6'),
            new Ok(7),
            new Ok(8),
            new Err('error9'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame([null, null, 'error3', null, null, 'error6', null, null, 'error9'], $result->unwrapErr());
    }

    #[Test]
    public function collect10WithAllOkReturnsOkWithArray(): void
    {
        $result = Result::collect10(
            new Ok(1),
            new Ok(2),
            new Ok(3),
            new Ok(4),
            new Ok(5),
            new Ok(6),
            new Ok(7),
            new Ok(8),
            new Ok(9),
            new Ok(10),
        );

        $this->assertTrue($result->isOk());
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $result->unwrap());
    }

    #[Test]
    public function collect10WithSomeErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect10(
            new Err('error1'),
            new Ok(2),
            new Err('error3'),
            new Ok(4),
            new Err('error5'),
            new Ok(6),
            new Err('error7'),
            new Ok(8),
            new Err('error9'),
            new Ok(10),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame(['error1', null, 'error3', null, 'error5', null, 'error7', null, 'error9', null], $result->unwrapErr());
    }

    #[Test]
    public function collect10WithAllErrReturnsErrWithAllErrors(): void
    {
        $result = Result::collect10(
            new Err('e1'),
            new Err('e2'),
            new Err('e3'),
            new Err('e4'),
            new Err('e5'),
            new Err('e6'),
            new Err('e7'),
            new Err('e8'),
            new Err('e9'),
            new Err('e10'),
        );

        $this->assertTrue($result->isErr());
        $this->assertSame(['e1', 'e2', 'e3', 'e4', 'e5', 'e6', 'e7', 'e8', 'e9', 'e10'], $result->unwrapErr());
    }
}
