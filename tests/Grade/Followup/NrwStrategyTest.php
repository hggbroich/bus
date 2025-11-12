<?php

namespace App\Tests\Grade\Followup;

use App\Grade\Followup\NrwStrategy;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NrwStrategyTest extends TestCase {

    public function testSekIWithBothPrefixAndSuffix(): void {
        $resolver = new NrwStrategy();

        $this->assertEquals('06A', $resolver->resolveFollowupGrade('05A'));
        $this->assertEquals('07B', $resolver->resolveFollowupGrade('06B'));
        $this->assertEquals('08c', $resolver->resolveFollowupGrade('07c'));
        $this->assertEquals('09D', $resolver->resolveFollowupGrade('08D'));
        $this->assertEquals('10E', $resolver->resolveFollowupGrade('09E'));
    }

    public function testSekIWithPrefixOnly() {
        $resolver = new NrwStrategy();

        $this->assertEquals('06', $resolver->resolveFollowupGrade('05'));
        $this->assertEquals('07', $resolver->resolveFollowupGrade('06'));
        $this->assertEquals('08', $resolver->resolveFollowupGrade('07'));
        $this->assertEquals('09', $resolver->resolveFollowupGrade('08'));
        $this->assertEquals('10', $resolver->resolveFollowupGrade('09'));
    }

    public function testSekIWithSuffixOnly(): void {
        $resolver = new NrwStrategy();

        $this->assertEquals('6A', $resolver->resolveFollowupGrade('5A'));
        $this->assertEquals('7B', $resolver->resolveFollowupGrade('6B'));
        $this->assertEquals('8c', $resolver->resolveFollowupGrade('7c'));
        $this->assertEquals('9D', $resolver->resolveFollowupGrade('8D'));
        $this->assertEquals('10E', $resolver->resolveFollowupGrade('9E'));
    }

    public function testSekII() {
        $resolver = new NrwStrategy();

        $this->assertEquals('Q1', $resolver->resolveFollowupGrade('EF'));
        $this->assertEquals('Q2', $resolver->resolveFollowupGrade('Q1'));
    }

    public function testSekIToSekII() {
        $resolver = new NrwStrategy();

        $this->assertEquals('EF', $resolver->resolveFollowupGrade('10A'));
        $this->assertEquals('EF', $resolver->resolveFollowupGrade('10'));
        $this->assertEquals('EF', $resolver->resolveFollowupGrade('010A'));
    }

    public function testEmptyGrade() {
        $resolver = new NrwStrategy();

        $this->assertEquals('', $resolver->resolveFollowupGrade(''));
    }

    public function testInvalidGrade() {
        $resolver = new NrwStrategy();

        $this->expectException(InvalidArgumentException::class);
        $resolver->resolveFollowupGrade('Q3');
    }
}
